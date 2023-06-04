<?php

namespace App\Services;

use App\Enums\Constants;
use App\Repositories\Agency\AgencyRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AgencyService
{
    /**
     * @var AgencyRepositoryInterface
     */
    protected AgencyRepositoryInterface $agencyRepository;

    /**
     * AgencyService constructor.
     * @param AgencyRepositoryInterface $agencyRepository
     */
    public function __construct(AgencyRepositoryInterface $agencyRepository)
    {
        $this->agencyRepository = $agencyRepository;
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function listAgency(array $data): mixed
    {
        $listAgency = $this->agencyRepository->listAgency($data)->toArray();
        $data = [];
        foreach ($listAgency['data'] as $key => $agency) {
            $data[$key]['id'] = $agency['id'];
            $data[$key]['name'] = $agency['name'];
            $data[$key]['status'] = Constants::AGENCY_STATUS[$agency['status']];;
            $data[$key]['prefecture'] = isset($agency['prefecture']) ? $agency['prefecture']['prefecture_name'] : '';
            $data[$key]['users_count'] = $agency['users_count'];
            $data[$key]['corporations_count'] = $agency['corporations_count'];
        }
        $listAgency['data'] = $data;
        return $listAgency;
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function createAgency(array $data): mixed
    {
        return $this->agencyRepository->createAgency($data);
    }

    /**
     * @param array $data
     * @param int $id
     * @return mixed
     */
    public function updateAgency(int $id, array $data): mixed
    {
        return $this->agencyRepository->updateAgency($id, $data);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function getAgencyById(int $id): mixed
    {
        return $this->agencyRepository->getDetailById($id);
    }

    /**
     *
     */
    public function getAllAgencyName()
    {
        return $this->agencyRepository->getAllAgencyName();
    }

    public function deleteAgency(int $id): bool
    {
        DB::beginTransaction();
        try {
            $this->agencyRepository->delete($id);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            Log::error($e);
            return false;
        }
    }
}
