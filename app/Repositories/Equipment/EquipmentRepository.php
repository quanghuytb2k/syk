<?php

namespace App\Repositories\Equipment;

use App\Enums\Constants;
use App\Models\Equipment;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Prettus\Repository\Eloquent\BaseRepository;

class EquipmentRepository extends BaseRepository implements EquipmentRepositoryInterface
{

    /**
     * @return string
     */
    public function model(): string
    {
        return Equipment::class;
    }

    /**
     * @param array $params
     * @return LengthAwarePaginator
     */
    public function filterEquipment(array $params): LengthAwarePaginator
    {
        $perPage = $params['perPage'] ?? Constants::PER_PAGE;
        return $this->model->with(
                'agency:id,name',
                'company:id,name',
                'facility:id,name',
                'building:id,name',
                'equipment_type:id,name'
            )
            ->when(isset($params['searchKey']), function ($query) use ($params) {
                $query->where(function ($query) use ($params) {
                    $query->where('name', 'like', '%' . $params['searchKey'] . '%')
                        ->orWhere('maker', 'like', '%' . $params['searchKey'] . '%')
                        ->orWhere('model', 'like', '%' . $params['searchKey'] . '%');
                });
            })
            ->when(isset($params['contract_type']), function ($query) use ($params) {
                $query->where('contract_type', $params['contract_type']);
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
            ->when(isset($params['equipment_type_id']) && $params['equipment_type_id'] != [], function ($query) use ($params) {
                $query->whereIn('equipment_type_id', $params['equipment_type_id']);
            })
            ->when(isset($params['next_maintenance_period_from']), function ($query) use ($params) {
                $query->whereDate('next_maintenance_period', '>=', $params['next_maintenance_period_from']);
            })
            ->when(isset($params['next_maintenance_period_to']), function ($query) use ($params) {
                $query->whereDate('next_maintenance_period', '<=', $params['next_maintenance_period_to']);
            })
            ->paginate($perPage);
    }
}
