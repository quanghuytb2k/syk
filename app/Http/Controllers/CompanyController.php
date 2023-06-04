<?php

namespace App\Http\Controllers;

use App\Http\Requests\Company\CompanyRequest;
use App\Repositories\Company\CompanyRepositoryInterface;
use App\Services\CompanyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Prettus\Validator\Exceptions\ValidatorException;

class CompanyController extends BaseApiController
{
    /**
     * Class instance
     * @param CompanyService $companyService
     * @param CompanyRepositoryInterface $companyRepository
     */
    public function __construct(
        protected CompanyService $companyService,
        protected CompanyRepositoryInterface $companyRepository
    ) {
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function list(Request $request): JsonResponse
    {
        $companies = $this->companyService->list($request->only(
            'searchKey',
            'agencyId',
            'listType',
            'perPage'
        ));

        return $this->responseSuccess($companies);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function detail(int $id): JsonResponse
    {
        $company = $this->companyService->detail($id);
        if ($company) {
            return $this->responseSuccess([
                'success' => true,
                'message' => 'Get Company Successfully',
                'data' => $company
            ]);
        }

        return $this->responseSuccess([
            'success' => false,
            'message' => 'Can Not Found Company',
            'data' => []
        ]);
    }

    /**
     * @param CompanyRequest $request
     * @return JsonResponse
     * @throws ValidatorException
     */
    public function create(CompanyRequest $request): JsonResponse
    {
        $company = $this->companyService->create($request->all());

        return $this->responseSuccess([
            'success' => true,
            'message' => 'Create Company Successfully',
            'data' => $company
        ]);
    }

    /**
     * @param CompanyRequest $request
     * @param int $id
     * @return JsonResponse
     * @throws ValidatorException
     */
    public function update(CompanyRequest $request, int $id): JsonResponse
    {
        $company = $this->companyService->update($request->all(), $id);

        return $this->responseSuccess([
            'success' => true,
            'message' => 'Update Company Successfully',
            'data' => $company
        ]);
    }

    /**
     * Get all id, name of all companies
     */
    public function getAllCompanyName()
    {
        $data = $this->companyService->getAllCompanyName();
        return $this->responseSuccess($data);
    }

    /**
     * @param int $agency_id
     * @return JsonResponse
     */
    public function getCompanyByAgency(int $agency_id): JsonResponse
    {
        $companies = $this->companyRepository->getCompanyByAgencyId($agency_id);
        return $this->responseSuccess($companies);
    }

    /**
     * @param int $company_id
     * @return JsonResponse
     */
    public function delete(int $company_id): JsonResponse
    {
        if ($this->companyService->deleteCompany($company_id))
        {
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
