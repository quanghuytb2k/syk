<?php

namespace App\Http\Controllers;

use App\Http\Requests\EnergyUsage\EnergyUsageRequest;
use App\Models\EnergyUsage;
use App\Repositories\EnergyUsage\EnergyUsageRepositoryInterface;
use App\Services\EnergyUsageService;
use App\Services\StorageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Http\File;

class EnergyUsageController extends BaseApiController
{
    /**
     * @param EnergyUsageService $energyUsageService
     * @param EnergyUsageRepositoryInterface $energyUsageRepository
     */
    public function __construct(
        protected EnergyUsageService             $energyUsageService,
        protected EnergyUsageRepositoryInterface $energyUsageRepository,
        protected StorageService                 $storageService
    )
    {
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function listUsage(Request $request): JsonResponse
    {
        $listUsage = $this->energyUsageRepository->filterUsage($request->only(
            'searchKey',
            'agency',
            'company',
            'facility',
            'building',
            'page',
            'perPage'
        ));

        return $this->responseSuccess($listUsage);
    }

    /**
     * @param int $usageId
     * @return JsonResponse
     */
    public function usageDetail(int $usageId): JsonResponse
    {
        $usage = $this->energyUsageRepository->with([
            'company:id,name',
            'facility:id,name',
            'building:id,name',
            'contract.energyType:id,name',
        ])->where('id', $usageId)->first();

        if ($usage) {
            return $this->responseSuccess([
                'success' => true,
                'message' => 'Get Usage Successfully',
                'data' => $usage
            ]);
        }

        return $this->responseSuccess([
            'success' => false,
            'message' => 'Can Not Found Usage'
        ]);
    }

    /**
     * @param EnergyUsageRequest $request
     * @return JsonResponse
     */
    public function create(EnergyUsageRequest $request): JsonResponse
    {
        $pathFiles = [];
        $data = $request->all();
        if (@$data['files']) {
            $pathFiles[] = $this->storageService->uploadFiles($data['files']);
            $pathFiles = collect($pathFiles)->flatten(1)->toArray();
            $data['files'] = $pathFiles;
        }

        $createUsage = $this->energyUsageService->createEnergyUsage($data);
        if ($createUsage) {
            return $this->responseSuccess([
                'success' => true,
                'message' => 'Create Usage Successfully'
            ]);
        } else {
            foreach ($pathFiles as $path) {
                Storage::disk('s3')->delete($path['path']);
            }
        }

        return $this->responseError([
            'status' => false,
            'message' => 'Create Usage fail'
        ]);
    }

    /**
     * @param EnergyUsageRequest $request
     * @param $usageId
     * @return JsonResponse
     */
    public function update(EnergyUsageRequest $request, $usageId): JsonResponse
    {
        if ($this->energyUsageService->updateEnergyUsage($request->validated(), $usageId)) {
            return $this->responseSuccess([
                'success' => true,
                'message' => 'Update Usage Successfully'
            ]);
        }

        return $this->responseError([
            'status' => false,
            'message' => 'Update Usage fail'
        ]);
    }

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
            $this->energyUsageService->updateFile($id, $dataInsert);

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

    public function deleteFile(Request $request, $id)
    {
        $path = $request->get('path') ?: '';
        Storage::disk('s3')->delete($path);

        if ($this->energyUsageService->deleteFile($id, $path)) {
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

    public function downloadFile(Request $request, $id)
    {
        $path = $request->get('path');
        $map = EnergyUsage::find($id);
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

    public function delete(int $id): JsonResponse
    {
        if ($this->energyUsageService->deleteUsage($id)) {
            return $this->responseSuccess([
                'status' => true,
                'message' => 'Delete Energy Usage Successfully'
            ]);
        }
        return $this->responseError([
            'status' => false,
            'message' => 'Delete Energy Usage Fail'
        ]);
    }
}
