<?php

namespace App\Http\Controllers;

use App\Http\Requests\Building\BuildingRequest;
use App\Repositories\Building\BuildingRepositoryInterface;
use App\Services\BuildingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Prettus\Validator\Exceptions\ValidatorException;

class BuildingController extends BaseApiController
{
    /**
     * Class instance
     * @param BuildingService $buildingService
     */
    public function __construct(
        protected BuildingService $buildingService,
        protected BuildingRepositoryInterface $buildingRepository
    )
    {}

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function list(Request $request): JsonResponse
    {
        $buildings = $this->buildingService->list($request->only(
            'searchKey',
            'buildingType',
            'constructionType',
            'agencyId',
            'companyId',
            'facilityId',
            'directProfit',
            'page',
            'perPage'
        ));

        return $this->responseSuccess($buildings);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function detail(int $id): JsonResponse
    {
        $building = $this->buildingService->detail($id);

        if ($building)
        {
            return $this->responseSuccess([
                'success' => true,
                'message' => 'Get Building Successfully',
                'data' => $building
            ]);
        }

        return $this->responseSuccess([
            'success' => false,
            'message' => 'Can Not Found Building',
            'data' => []
        ]);
    }

    /**
     * @param BuildingRequest $request
     * @return JsonResponse
     * @throws ValidatorException
     */
    public function create(BuildingRequest $request): JsonResponse
    {
        $building = $this->buildingService->create($request->all());

        return $this->responseSuccess([
            'success' => true,
            'message' => 'Create Building Successfully',
            'data' => $building
        ]);
    }

    /**
     * @param BuildingRequest $request
     * @param int $id
     * @return JsonResponse
     * @throws ValidatorException
     */
    public function update(BuildingRequest $request, int $id): JsonResponse
    {
        $building = $this->buildingService->update($request->validated(), $id);

        return $this->responseSuccess([
            'success' => true,
            'message' => 'Update Building Successfully',
            'data' => $building
        ]);
    }

    /**
     * @return JsonResponse
     */
    public function listBuildingType(): JsonResponse
    {
        $listBuildingType = $this->buildingService->getListBuildingType();

        return $this->responseSuccess($listBuildingType);
    }

    /**
     * @return JsonResponse
     */
    public function listConstruction(): JsonResponse
    {
        $listConstruction = $this->buildingService->getListConstruction();

        return $this->responseSuccess($listConstruction);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function listBuilding(Request $request): JsonResponse
    {
        $listBuilding = $this->buildingRepository->getListBuilding($request->all());

        return $this->responseSuccess($listBuilding);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function delete(int $id): JsonResponse
    {
        if ($this->buildingService->deleteCompany($id)) {
            return $this->responseSuccess([
                'status' => true,
                'message' => 'Delete Building Successfully'
            ]);
        }

        return $this->responseError([
            'status' => false,
            'message' => 'Delete Building Fail'
        ]);
    }
}
