<?php

namespace App\Http\Controllers;

use App\Enums\ResponseCode;
use App\Http\Requests\Agency\SearchAgencyRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Services\AgencyService;
use App\Http\Requests\Agency\CreateAgencyRequest;
//use App\Http\Requests\Agency\UpdateAgencyRequest;


class AgencyController extends BaseApiController
{
    protected AgencyService $agencyService;

    /**
     * create new class instance
     *
     * @param AgencyService $agencyService
     * @return void
     */
    public function __construct(AgencyService $agencyService)
    {
        $this->agencyService = $agencyService;
    }

    /**
     * get list agency
     *
     * @param SearchAgencyRequest $request
     * @return JsonResponse
     */
    public function list(SearchAgencyRequest $request): JsonResponse
    {
        $data = $request->validated();
        $agency = $this->agencyService->listAgency($data);

        return $this->responseSuccess($agency);
    }

    /**
     * crete new agency
     *
     * @param CreateAgencyRequest $request
     * @return JsonResponse
     */
    public function create(CreateAgencyRequest $request): JsonResponse
    {
        $data = $request->validated();
        $this->agencyService->createAgency($data);

        return $this->responseSuccess(['success' => true, 'message' => 'Create Agency Successfully']);
    }

    /**
     * agency detail
     *
     * @param int $id
     * @return JsonResponse
     */
    public function detail(int $id): JsonResponse
    {
        $agency = $this->agencyService->getAgencyById($id);

        if ($agency) {
            return $this->responseSuccess([
                'success' => true,
                'message' => 'Get Agency Successfully',
                'data' => $agency
            ]);
        }
        return $this->responseSuccess([
            'success' => false,
            'message' => 'Can Not Found Agency'
        ]);
    }

    /**
     * @param int $id
     * @param CreateAgencyRequest $request
     * @return JsonResponse
     */
    public function update(int $id, CreateAgencyRequest $request): JsonResponse
    {
        $data = $request->validated();
        $this->agencyService->updateAgency($id, $data);

        return $this->responseSuccess(['success' => true, 'message' => 'Update Agency Successfully']);
    }

    /**
     * Get all id, name of all agencies
     */
    public function getAllAgencyName()
    {
        $data = $this->agencyService->getAllAgencyName();
        return $this->responseSuccess($data);
    }

    /**
     * @param int $id
     * @return void
     */
    public function delete(int $id): JsonResponse
    {
        if ($this->agencyService->deleteAgency($id)) {
            return $this->responseSuccess([
                'status' => true,
                'message' => 'Delete Agency Successfully'
            ]);
        }
        return $this->responseError([
            'status' => false,
            'message' => 'Delete Agency Fail'
        ]);
    }
}
