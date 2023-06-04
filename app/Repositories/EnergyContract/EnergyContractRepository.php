<?php

namespace App\Repositories\EnergyContract;

use App\Enums\Constants;
use App\Models\EnergyContract;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Prettus\Repository\Eloquent\BaseRepository;

class EnergyContractRepository extends BaseRepository implements EnergyContractRepositoryInterface
{
    /**
     * @return string
     */
    public function model(): string
    {
        return EnergyContract::class;
    }

    /**
     * @param array $params
     * @return LengthAwarePaginator
     */
    public function filterContract(array $params): LengthAwarePaginator
    {
        $perPage = $params['perPage'] ?? Constants::PER_PAGE;
        return $this->model->with('energyType', 'company', 'facility', 'building')
            ->when(isset($params['energy_type_id']), function ($query) use ($params) {
                $query->where('energy_type_id', $params['energy_type_id']);
            })
            ->when(isset($params['agency']), function ($query) use ($params) {
                $query->where('agency_id', $params['agency']);
            })
            ->when(isset($params['company']), function ($query) use ($params) {
                $query->where('company_id', $params['company']);
            })
            ->when(isset($params['facility']), function ($query) use ($params) {
                $query->where('facility_id', $params['facility']);
            })
            ->when(isset($params['building']), function ($query) use ($params) {
                $query->where('building_id', $params['building']);
            })
            ->paginate($perPage);
    }

    /**
     * @param array $params
     * @return mixed
     */
    public function getContractByCondition(array $params): mixed
    {
        $energy_type = $params['energy_type'] ?? null;
        $company = $params['company_id'] ?? null;
        $facility = $params['facility_id'] ?? null;
        $building = $params['building_id'] ?? null;

        return $this->model
            ->where('energy_type_id', $energy_type)
            ->where('company_id', $company)
            ->where('facility_id', $facility)
            ->where('building_id', $building)
            ->orderBy('created_at', 'desc')
            ->first();
    }
}
