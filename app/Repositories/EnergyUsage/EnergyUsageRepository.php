<?php

namespace App\Repositories\EnergyUsage;

use App\Enums\Constants;
use App\Models\EnergyUsage;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Prettus\Repository\Eloquent\BaseRepository;

class EnergyUsageRepository extends BaseRepository implements EnergyUsageRepositoryInterface
{
    /**
     * @return string
     */
    public function model(): string
    {
        return EnergyUsage::class;
    }

    /**
     * @param array $params
     * @return LengthAwarePaginator
     */
    public function filterUsage(array $params): LengthAwarePaginator
    {
        $perPage = $params['perPage'] ?? Constants::PER_PAGE;
        return $this->model->select('energy_usages.*')->with([
                'company:id,name',
                'facility:id,name',
                'building:id,name',
                'contract.energyType:id,name'
            ])
            ->when(isset($params['searchKey']), function ($query) use ($params) {
                $query->leftJoin('agencies', 'energy_usages.agency_id', '=', 'agencies.id')
                    ->leftJoin('companies', 'energy_usages.company_id', '=', 'companies.id')
                    ->where('agencies.name', 'like', '%' . $params['searchKey'] . '%')
                    ->where('companies.name', 'like', '%' . $params['searchKey'] . '%');
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
}
