<?php

namespace App\Http\Controllers;

use App\Http\Requests\Facility\FacilityRequest;
use App\Repositories\Facility\FacilityRepositoryInterface;
use App\Services\FacilityService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Prettus\Validator\Exceptions\ValidatorException;
use Illuminate\Support\Facades\Log;

class FacilityController extends BaseApiController
{

    /**
     * Class instance
     * @param FacilityService $facilityService
     * @return void
     */
    public function __construct(
        protected FacilityService             $facilityService,
        protected FacilityRepositoryInterface $facilityRepository
    )
    {
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function list(Request $request): JsonResponse
    {
        $facilities = $this->facilityService->list($request->only(
            'searchKey',
            'prefectureId',
            'agencyId',
            'companyId',
            'perPage'
        ));

        return $this->responseSuccess($facilities);
    }

    /**
     * Create facility
     *
     * @param FacilityRequest $request
     * @return JsonResponse
     * @throws ValidatorException
     */
    public function create(FacilityRequest $request): JsonResponse
    {
        $facility = $this->facilityService->create($request->validated());

        return $this->responseSuccess([
            'success' => true,
            'message' => 'Create Facility Successfully',
            'data' => $facility
        ]);
    }

    /**
     * @param FacilityRequest $request
     * @param int $id
     * @return JsonResponse
     * @throws ValidatorException
     */
    public function update(FacilityRequest $request, int $id): JsonResponse
    {
        $facility = $this->facilityService->update($request->validated(), $id);
        return $this->responseSuccess([
            'success' => true,
            'message' => 'Update Facility Successfully',
            'data' => $facility
        ]);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function detail(int $id): JsonResponse
    {
        $facility = $this->facilityService->detail($id);
        if ($facility) {
            return $this->responseSuccess([
                'success' => true,
                'message' => 'Get Facility Successfully',
                'data' => $facility
            ]);
        }

        return $this->responseSuccess([
            'success' => false,
            'message' => 'Can Not Found Facility',
            'data' => []
        ]);
    }

    /**
     * Get all id, name of all facilities
     */
    public function getAllFacilityName(): JsonResponse
    {
        $data = $this->facilityService->getAllFacilityName();
        return $this->responseSuccess($data);
    }

    /**
     * @param int $agency_id
     * @param int $company_id
     * @return JsonResponse
     */
    public function filterFacilityByParent(int $agency_id, int $company_id): JsonResponse
    {
        $data = $this->facilityRepository->getFacilityByParent($agency_id, $company_id);
        return $this->responseSuccess($data);
    }

    public function delete(int $id): JsonResponse
    {
        if ($this->facilityService->deleteFacility($id)) {
            return $this->responseSuccess([
                'status' => true,
                'message' => 'Delete Facility Successfully'
            ]);
        }
        return $this->responseError([
            'status' => false,
            'message' => 'Delete Facility Fail'
        ]);
    }
}
