<?php

namespace App\Http\Controllers;

use App\Http\Requests\Eqipment\EquipmentRequest;
use App\Http\Requests\MaintainHistoryRequest;
use App\Repositories\Equipment\EquipmentRepositoryInterface;
use App\Repositories\MaintainHistory\MaintainHistoryRepositoryInterface;
use App\Services\EquipmentService;
use App\Services\StorageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EquipmentController extends BaseApiController
{
    public function __construct(
        protected EquipmentService                   $equipmentService,
        protected EquipmentRepositoryInterface       $equipmentRepository,
        protected StorageService                     $storageService,
        protected MaintainHistoryRepositoryInterface $maintainHistoryRepository
    )
    {
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function listEquipment(Request $request): JsonResponse
    {
        $listEquipment = $this->equipmentRepository->filterEquipment($request->only(
            'searchKey',
            'agency',
            'company',
            'facility',
            'building',
            'contract_type',
            'next_maintenance_period_from',
            'next_maintenance_period_to',
            'equipment_type_id',
            'page',
            'perPage'
        ));

        return $this->responseSuccess($listEquipment);
    }

    /**
     * @param $equipment_id
     * @return JsonResponse
     */
    public function equipmentDetail($equipment_id): JsonResponse
    {
        $equipment = $this->equipmentRepository->where('id', $equipment_id)->first();

        if ($equipment) {
            return $this->responseSuccess([
                'success' => true,
                'message' => 'Get Equipment Successfully',
                'data' => $equipment
            ]);
        }

        return $this->responseSuccess([
            'success' => false,
            'message' => 'Can Not Found Equipment'
        ]);
    }

    /**
     * @param EquipmentRequest $request
     * @return JsonResponse
     */
    public function createEquipment(EquipmentRequest $request): JsonResponse
    {
        $createEquipment = $this->equipmentService->createEquipment($request->validated());
        if ($createEquipment) {
            return $this->responseSuccess([
                'success' => true,
                'message' => 'Create Equipment Successfully'
            ]);
        }
        return $this->responseError([
            'success' => false,
            'message' => 'Create fail'
        ]);
    }

    /**
     * @param EquipmentRequest $request
     * @param $equipment_id
     * @return JsonResponse
     */
    public function updateEquipment(EquipmentRequest $request, $equipment_id): JsonResponse
    {
        $updateEquipment = $this->equipmentService->updateEquipment($request->validated(), $equipment_id);
        if ($updateEquipment) {
            return $this->responseSuccess([
                'success' => true,
                'message' => 'Update Equipment Successfully'
            ]);
        }
        return $this->responseError([
            'success' => false,
            'message' => 'Update fail'
        ]);
    }

    /**
     * create maintain history and upload file to s3
     * @param MaintainHistoryRequest $request
     * @return JsonResponse
     */
    public function createMaintainHistory(MaintainHistoryRequest $request): JsonResponse
    {
        $insertParams = $request->validated();
        $files = [];
        if ($request->hasFile('files')) {
            $files = $this->storageService->uploadFiles($request->file('files'));
            $insertParams['files'] = $files;
        }

        $createHistory = $this->equipmentService->createMaintainHistory($insertParams);
        if ($createHistory) {
            return $this->responseSuccess([
                'success' => true,
                'message' => 'Create History Successfully'
            ]);
        } else {
            foreach ($files as $file) {
                Storage::disk('s3')->delete($file['path'] ?? '');
            }
        }
        return $this->responseError([
            'success' => false,
            'message' => 'Create History fail'
        ]);
    }

    /**
     * get list maintain history
     * @param Request $request
     * @param int $equipment_id
     * @return JsonResponse
     */
    public function getListMaintainHistory(Request $request, int $equipment_id): JsonResponse
    {
        $maintainHistory = $this->maintainHistoryRepository->filterMaintainHistory($request->only('page', 'perPage'), $equipment_id);
        return $this->responseSuccess($maintainHistory);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function uploadMaintainHistoryFiles(Request $request): JsonResponse
    {
        $uploadFiles = false;

        if ($request->hasFile('files')) {
            $uploadFiles = $this->equipmentService->uploadHistoryFiles($request->all());
        }

        if ($uploadFiles) {
            return $this->responseSuccess([
                'success' => true,
                'message' => 'Upload File Successfully'
            ]);
        }

        return $this->responseError([
            'success' => false,
            'message' => 'Upload File fail'
        ]);
    }

    /**
     * @param int $history_id
     * @return JsonResponse
     */
    public function getHistoryDetail(int $history_id): JsonResponse
    {
        $history = $this->maintainHistoryRepository->getDetailById($history_id);
        return $this->responseSuccess($history);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteHistoryFile(Request $request): JsonResponse
    {
        $delete = $this->equipmentService->deleteHistoryFile($request->all());
        if ($delete) {
            return $this->responseSuccess([
                'success' => true,
                'message' => 'Delete File Successfully'
            ]);
        }

        return $this->responseError([
            'success' => false,
            'message' => 'Delete File fail'
        ]);
    }

    /**
     * @param MaintainHistoryRequest $request
     * @param int $history_id
     * @return JsonResponse
     */
    public function updateMaintainHistory(MaintainHistoryRequest $request, int $history_id): JsonResponse
    {
        $updateHistory = $this->equipmentService->updateMaintainHistory($request->validated(), $history_id);
        if ($updateHistory) {
            return $this->responseSuccess([
                'success' => true,
                'message' => 'Update Successfully'
            ]);
        }

        return $this->responseError([
            'success' => false,
            'message' => 'Update fail'
        ]);
    }

    public function delete(int $id): JsonResponse
    {
        if ($this->equipmentService->deleteEquipment($id)) {
            return $this->responseSuccess([
                'status' => true,
                'message' => 'Delete Equipment Successfully'
            ]);
        }
        return $this->responseError([
            'status' => false,
            'message' => 'Delete Equipment Fail'
        ]);
    }
}
