<?php

namespace App\Http\Controllers;

use App\Http\Requests\EnergyContract\EnergyContractRequest;
use App\Models\EnergyContract;
use App\Repositories\EnergyContract\EnergyContractRepositoryInterface;
use App\Repositories\EnergyContractFile\EnergyFileRepositoryInterface;
use App\Repositories\EnergyType\EnergyTypeRepositoryInterface;
use App\Services\EnergyContractService;
use App\Services\StorageService;
use Carbon\Carbon;
use Illuminate\Http\File;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Prettus\Validator\Exceptions\ValidatorException;

class EnergyContractController extends BaseApiController
{
    public function __construct(
        protected EnergyContractService $energyContractService,
        protected EnergyTypeRepositoryInterface $energyTypeRepository,
        protected EnergyContractRepositoryInterface $energyContractRepository,
        protected EnergyFileRepositoryInterface $energyFileRepository,
        protected StorageService $storageService
    )
    {}

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function listContract(Request $request): JsonResponse
    {
        $listContract = $this->energyContractRepository->filterContract($request->only(
            'searchKey',
            'energy_type_id',
            'agency',
            'company',
            'facility',
            'building',
            'page',
            'perPage'
        ));

        return $this->responseSuccess($listContract);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function detailContract(int $id): JsonResponse
    {
        $contract = $this->energyContractRepository->find($id);

        if ($contract) {
            return $this->responseSuccess([
                'success' => true,
                'message' => 'Get Contract Successfully',
                'data' => $contract
            ]);
        } else {
            return $this->responseSuccess([
                'success' => false,
                'message' => 'Can Not Found Contract'
            ]);
        }
    }

    /**
     * @param EnergyContractRequest $request
     * @return JsonResponse
     */
    public function createContract(Request $request): JsonResponse
    {
        $pathFiles = [];
        $data = $request->all();
        if (isset($data['files'])) {
            $pathFiles[] = $this->storageService->uploadFiles($data['files']);
            $pathFiles = collect($pathFiles)->flatten(1)->toArray();
            $data['files'] = $pathFiles;
        }

        $createContract = $this->energyContractService->createContract($data);

        if ($createContract) {
            return $this->responseSuccess([
                'success' => true,
                'message' => 'Create Contract Successfully'
            ]);
        } else {
            foreach ($pathFiles as $path)
            {
                Storage::disk('s3')->delete($path['path']);
            }

            return $this->responseError([
                'status' => false,
                'message' => 'Has occurred, please try again'
            ]);
        }
    }

    /**
     * @param EnergyContractRequest $request
     * @param int $id
     * @return JsonResponse
     * @throws ValidatorException
     */
    public function updateContract(EnergyContractRequest $request, int $id): JsonResponse
    {
        $this->energyContractRepository->update($request->all(), $id);

        return $this->responseSuccess([
            'success' => true,
            'message' => 'Update Company Successfully'
        ]);
    }

    /**
     * @return JsonResponse
     */
    public function listEnergyType(): JsonResponse
    {
        $listEnergyType = $this->energyTypeRepository->getListEnergyType();

        return $this->responseSuccess($listEnergyType);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getContractByCondition(Request $request): JsonResponse
    {
        $contract = $this->energyContractRepository->getContractByCondition($request->only(
            'energy_type',
            'company_id',
            'facility_id',
            'building_id'
        ));

        return $this->responseSuccess($contract);
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
            $this->energyContractService->updateFile($id, $dataInsert);

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

        if ($this->energyContractService->deleteFile($id, $path)) {
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
        $map = EnergyContract::find($id);
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
}
