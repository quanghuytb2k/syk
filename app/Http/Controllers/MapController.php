<?php

namespace App\Http\Controllers;

use App\Http\Requests\Map\MapRequest;
use App\Http\Requests\Map\UploadFileRequest;
use App\Repositories\Map\MapRepository;
use App\Services\MapService;
use App\Services\StorageService;
use Carbon\Carbon;
use Illuminate\Http\File;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use mysql_xdevapi\Collection;

class MapController extends BaseApiController
{

    /**
     * @param MapService $mapService
     * @param MapRepository $mapRepository
     */
    public function __construct(
        protected MapService     $mapService,
        protected MapRepository  $mapRepository,
        protected StorageService $storageService
    )
    {
    }


    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function list(Request $request): JsonResponse
    {
        $listMap = $this->mapService->list($request->all());
        return $this->responseSuccess($listMap);
    }

    public function create(MapRequest $request): JsonResponse
    {
        $data = $request->all();
        $pathFiles = [];
        $pathFiles[] = $this->storageService->uploadFiles($data['files']);

        if (!empty($pathFiles)) {
            $pathFiles = collect($pathFiles)->flatten(1)->toArray();
            $data['files'] = $pathFiles;
        }

        $map = $this->mapService->create($data);

        if ($map) {
            return $this->responseSuccess([
                'success' => true,
                'message' => 'Create Map Successfully',
                'data' => $map
            ]);
        } else {
            foreach ($pathFiles as $path) {
                Storage::disk('s3')->delete($path['path']);
            }

            return $this->responseError([
                'status' => false,
                'message' => 'Has occurred, please try again'
            ]);
        }
    }

    public function listDrawingType(): JsonResponse
    {
        $listDrawingType = $this->mapService->listDrawingType();
        return $this->responseSuccess($listDrawingType);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function detail(int $id): JsonResponse
    {
        $map = $this->mapService->detail($id);

        if ($map) {
            return $this->responseSuccess([
                'success' => true,
                'message' => 'Get Map Successfully',
                'data' => $map
            ]);
        }

        return $this->responseSuccess([
            'success' => false,
            'message' => 'Can Not Found Map',
            'data' => []
        ]);
    }

    public function delete(int $id): JsonResponse
    {
        if ($this->mapService->deleteMap($id)) {
            return $this->responseSuccess([
                'status' => true,
                'message' => 'Delete Map Successfully'
            ]);
        }

        return $this->responseError([
            'status' => false,
            'message' => 'Delete Map Fail'
        ]);
    }

    public function update(MapRequest $request, int $id): JsonResponse
    {
        $this->mapRepository->update($request->all(), $id);

        return $this->responseSuccess([
            'success' => true,
            'message' => 'Update Map Successfully',
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function downloadFile(Request $request, $id)
    {
        $path = $request->get('path');
        $map = $this->mapService->detail($id);
        $file = collect($map['files'])->map(function ($items) use ($path) {
            if ($items['path'] == $path) {
                return $items;
            }
        })->filter(function ($items) {
            return $items;
        })->values()->flatten(1)->toArray();

        if (Storage::disk('s3')->exists(@$file[1])) {
            return $this->responseSuccess([
                'status' => true,
                'message' => 'Get File Successfully',
                'data' => (object)[
                    'url' => Storage::temporaryUrl(
                        $path,
                        now()->addMinutes(5),
                        [
                            'ResponseContentType' => 'application/octet-stream',
                            'ResponseContentDisposition' => 'attachment; filename=' . @$file[0],
                        ]
                    )
                ]
            ]);
        }

        return $this->responseError([
            'status' => false,
            'message' => 'File does not exist'
        ]);
    }

    public function deleteFile(Request $request, $id)
    {
        $path = $request->get('path') ?: '';
        Storage::disk('s3')->delete($path);

        if ($this->mapService->deleteFile($id, $path)) {
            return $this->responseSuccess([
                'status' => true,
                'message' => 'Delete File Successfully',
            ]);
        }

        return $this->responseError([
            'status' => false,
            'message' => 'File does not exist'
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function uploadFile(Request $request): JsonResponse
    {
        try {
            $id = $request->get('id');
            $dataInsert = [];
            foreach ($request->file('files') as $file) {
                $path = Carbon::now()->format('Y-m-d') . '/' . Auth::user()->id;
                $fileName = Carbon::now()->getTimestamp() . '_' . Str::random(10) . '.' . $file->extension();
                $dataInsert[] = [
                    'name' => $fileName,
                    'path' => Storage::disk('s3')->putFileAs($path, new File($file), $fileName)
                ];
            }
            $this->mapService->updateFile($id, $dataInsert);

            return $this->responseSuccess([
                'status' => true,
                'message' => 'Upload file Successfully'
            ]);
        } catch (\Exception $exception) {
            Log::error($exception);
            return $this->responseError([
                'status' => false,
                'message' => 'Upload file fail'
            ]);
        }
    }
}
