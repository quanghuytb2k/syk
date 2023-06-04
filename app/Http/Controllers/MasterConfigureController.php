<?php

namespace App\Http\Controllers;

use App\Http\Requests\MasterTableRequest;
use App\Repositories\MasterConfigure\MasterConfigureRepositoryInterface;
use App\Services\MasterConfigureService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MasterConfigureController extends BaseApiController
{
    public function __construct(
        protected MasterConfigureRepositoryInterface $masterConfigureRepository,
        protected MasterConfigureService $masterConfigureService
    )
    {}

    /**
     * @return JsonResponse
     */
    public function getListMasterTable(): JsonResponse
    {
        return $this->responseSuccess($this->masterConfigureRepository->getListMasterTable());
    }

    /**
     * @param MasterTableRequest $request
     * @return JsonResponse
     */
    public function getMasterTable(MasterTableRequest $request): JsonResponse
    {
        $data = $this->masterConfigureService->getMasterTable($request->input('table'));
        return $this->responseSuccess($data);
    }

    /**
     * @param MasterTableRequest $request
     * @return JsonResponse
     */
    public function updateRecordMasterTable(MasterTableRequest $request): JsonResponse
    {
        if ($this->masterConfigureService->updateRecordMasterTable($request->input('table'), $request->input('id'), $request->except('id', 'table'))) {
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

    /**
     * @param MasterTableRequest $request
     * @return JsonResponse
     */
    public function createRecordMasterTable(MasterTableRequest $request): JsonResponse
    {
        if ($this->masterConfigureService->createRecordMasterTable($request->input('table'), $request->except('id', 'table'))) {
            return $this->responseSuccess([
                'success' => true,
                'message' => 'Create Successfully'
            ]);
        }
        return $this->responseError([
            'success' => false,
            'message' => 'Create fail'
        ]);
    }

    /**
     * @param MasterTableRequest $request
     * @return JsonResponse
     */
    public function deleteRecordMasterTable(MasterTableRequest $request): JsonResponse
    {
        if ($this->masterConfigureService->deleteRecordMasterTable($request->input('table'), $request->input('id'))) {
            return $this->responseSuccess([
                'success' => true,
                'message' => 'Delete Successfully'
            ]);
        }
        return $this->responseError([
            'success' => false,
            'message' => 'Delete fail'
        ]);
    }

    /**
     * @return JsonResponse
     */
    public function getDivisionTreeView(): JsonResponse
    {
        return $this->responseSuccess($this->masterConfigureService->getDivisionTreeView());
    }
}
