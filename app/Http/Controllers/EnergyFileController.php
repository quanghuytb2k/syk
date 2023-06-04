<?php

namespace App\Http\Controllers;

use App\Enums\FileEnums;
use App\Http\Requests\EnergyFileRequest;
use App\Repositories\EnergyContract\EnergyContractRepositoryInterface;
use App\Repositories\EnergyContractFile\EnergyFileRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\File;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EnergyFileController extends BaseApiController
{
    public function __construct(
        protected EnergyFileRepositoryInterface $energyFileRepository
    )
    {}

    /**
     * @param EnergyFileRequest $request
     * @return JsonResponse
     */
    public function uploadFile(EnergyFileRequest $request): JsonResponse
    {
        try {
            $dataInsert = [];
            $fileTypeParam = FileEnums::getFileParam($request->input('file_type'));
            foreach ($request->file('files') as $file)
            {
                $path = Carbon::now()->format('Y-m-d') .'/'. Auth::user()->id;
                $fileName = Carbon::now()->getTimestamp() . '_' . Str::random(10) . '.' . $file->extension();
                $dataInsert[] = [
                    $fileTypeParam => $request->input($fileTypeParam),
                    'name' => $fileName,
                    'path' => Storage::disk('s3')->putFileAs($path, new File($file), $fileName)
                ];
            }
            $this->energyFileRepository->createMultiple($dataInsert);

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

    /**
     * @param int $fileId
     * @return JsonResponse
     */
    public function downloadFile(int $fileId): JsonResponse
    {
        $file = $this->energyFileRepository->find($fileId);

        if ($file && Storage::disk('s3')->exists($file->path)) {
            return $this->responseSuccess([
                'status' => true,
                'message' => 'Get File Successfully',
                'data' => (object)[
                    'url' => Storage::temporaryUrl(
                        $file->path,
                        now()->addMinutes(5),
                        [
                            'ResponseContentType' => 'application/octet-stream',
                            'ResponseContentDisposition' => 'attachment; filename='.$file->name,
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

    /**
     * @param int $fileId
     * @return JsonResponse
     */
    public function deleteFile(int $fileId): JsonResponse
    {
        $file = $this->energyFileRepository->find($fileId);
        if ($file) {
            if (Storage::disk('s3')->exists($file->path)) {
                Storage::disk('s3')->delete($file->path);
            }

            $file->delete();

            return $this->responseSuccess([
                'status' => true,
                'message' => 'Delete file Successfully'
            ]);
        }

        return $this->responseError([
            'status' => false,
            'message' => 'File does not exist'
        ]);
    }
}
