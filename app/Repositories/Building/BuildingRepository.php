<?php

namespace App\Repositories\Building;

use App\Enums\BuildingEnums;
use App\Enums\Constants;
use App\Models\Building;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Prettus\Repository\Eloquent\BaseRepository;

class BuildingRepository extends BaseRepository implements BuildingRepositoryInterface
{

    /**
     * @return string
     */
    public function model(): string
    {
        return Building::class;
    }

    /**
     * @param array $params
     * @return LengthAwarePaginator
     */
    public function filterBuilding(array $params): LengthAwarePaginator
    {
        $perPage = $params['perPage'] ?? Constants::PER_PAGE;
        return $this->model->with('agency', 'company', 'facility.prefecture', 'buildingType', 'construction')
            ->when(isset($params['searchKey']), function ($query) use ($params) {
                $query->where('name', 'like', '%' . $params['searchKey'] . '%');
            })
            ->when(isset($params['buildingType']), function ($query) use ($params) {
                $query->where('building_type_id', $params['buildingType']);
            })
            ->when(isset($params['constructionType']), function ($query) use ($params) {
                $query->where('building_construction_type_id', $params['constructionType']);
            })
            ->when(isset($params['agencyId']), function ($query) use ($params) {
                $query->where('agency_id', $params['agencyId']);
            })
            ->when(isset($params['companyId']), function ($query) use ($params) {
                $query->where('company_id', $params['companyId']);
            })
            ->when(isset($params['facilityId']), function ($query) use ($params) {
                $query->where('facility_id', $params['facilityId']);
            })
            ->when(isset($params['directProfit']) && in_array($params['directProfit'], [BuildingEnums::INDIRECT, BuildingEnums::DIRECT]), function ($query) use ($params) {
                $query->where('is_direct_profit', $params['directProfit']);
            })
            ->orderBy('created_at', 'DESC')
            ->paginate($perPage);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function getBuildingById(int $id): mixed
    {
        return $this->model->find($id);
    }

    /**
     * @param array $params
     * @return mixed
     */
    public function getListBuilding(array $params): mixed
    {
        return $this->model->select('id', 'name')
            ->when(isset($params['agency_id']), function ($query) use($params) {
                $query->where('agency_id', $params['agency_id']);
            })
            ->when(isset($params['company_id']), function ($query) use($params) {
                $query->where('company_id', $params['company_id']);
            })
            ->when(isset($params['facility_id']), function ($query) use($params) {
                $query->where('facility_id', $params['facility_id']);
            })
            ->get();
    }
}
