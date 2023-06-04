<?php

namespace App\Http\Controllers;

use App\Http\Requests\MaintainCompanyRequest;
use App\Repositories\MaintainCompany\MaintainCompanyDetailTypeRepositoryInterface;
use App\Repositories\MaintainCompany\MaintainCompanyRepositoryInterface;
use App\Repositories\MaintainCompany\MaintainCompanyTypeRepositoryInterface;
use App\Services\MaintainCompanyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MaintainCompanyController extends BaseApiController
{
    public function __construct(
        protected MaintainCompanyService                       $maintainCompanyService,
        protected MaintainCompanyRepositoryInterface           $maintainCompanyRepository,
        protected MaintainCompanyTypeRepositoryInterface       $maintainCompanyTypeRepository,
        protected MaintainCompanyDetailTypeRepositoryInterface $maintainCompanyDetailTypeRepository
    )
    {
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function list(Request $request): JsonResponse
    {
        $listMaintainCompany = $this->maintainCompanyRepository->filterMaintainCompany($request->only(
            'prefecture',
            'type',
            'detailType',
            'page',
            'perPage',
            'company_type'
        ));

        return $this->responseSuccess($listMaintainCompany);
    }

    /**
     * @param int $maintain_id
     * @return JsonResponse
     */
    public function detail(int $maintain_id): JsonResponse
    {
        $maintainCompany = $this->maintainCompanyRepository->getMaintainCompanyById($maintain_id);

        if ($maintainCompany) {
            return $this->responseSuccess([
                'success' => true,
                'message' => 'Get Maintain Company Successfully',
                'data' => $maintainCompany
            ]);
        }

        return $this->responseSuccess([
            'success' => false,
            'message' => 'Can Not Found Maintain Company'
        ]);
    }

    /**
     * @param MaintainCompanyRequest $request
     * @return JsonResponse
     */
    public function store(MaintainCompanyRequest $request): JsonResponse
    {
        $createMaintainCompany = $this->maintainCompanyService->store($request->all());
        if ($createMaintainCompany) {
            return $this->responseSuccess([
                'success' => true,
                'message' => 'Create Maintain company Successfully'
            ]);
        }

        return $this->responseError([
            'status' => false,
            'message' => 'Create Maintain company fail'
        ]);
    }

    /**
     * @param MaintainCompanyRequest $request
     * @param int $maintain_id
     * @return JsonResponse
     */
    public function update(MaintainCompanyRequest $request, int $maintain_id): JsonResponse
    {
        $updateMaintainCompany = $this->maintainCompanyService->update($request->all(), $maintain_id);
        if ($updateMaintainCompany) {
            return $this->responseSuccess([
                'success' => true,
                'message' => 'Update Maintain company Successfully'
            ]);
        }

        return $this->responseError([
            'status' => false,
            'message' => 'Update Maintain company fail'
        ]);
    }

    /**
     * @return JsonResponse
     */
    public function getTypeList(): JsonResponse
    {
        $typeList = $this->maintainCompanyTypeRepository->getList();
        return $this->responseSuccess($typeList);
    }

    /**
     * @return JsonResponse
     */
    public function getDetailTypeList(): JsonResponse
    {
        $detailTypeList = $this->maintainCompanyDetailTypeRepository->getList();
        return $this->responseSuccess($detailTypeList);
    }

    /**
     * @return JsonResponse
     */
    public function getAllMaintainCompany(): JsonResponse
    {
        $allMaintainCompany = $this->maintainCompanyRepository->getAll();
        return $this->responseSuccess($allMaintainCompany);
    }

    public function delete(int $id): JsonResponse
    {
        if ($this->maintainCompanyService->deleteCompany($id)) {
            return $this->responseSuccess([
                'status' => true,
                'message' => 'Delete Company Successfully'
            ]);
        }
        return $this->responseError([
            'status' => false,
            'message' => 'Delete Company Fail'
        ]);
    }
}
