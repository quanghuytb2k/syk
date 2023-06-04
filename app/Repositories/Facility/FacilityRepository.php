<?php

namespace App\Repositories\Facility;

use App\Enums\Constants;
use App\Models\Facility;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Prettus\Repository\Eloquent\BaseRepository;

class FacilityRepository extends BaseRepository implements FacilityRepositoryInterface
{

    /**
     * @return string
     */
    public function model(): string
    {
        return Facility::class;
    }

    /**
     * @param array $params
     * @return LengthAwarePaginator
     */
    public function facilityFilter(array $params): LengthAwarePaginator
    {
        $perPage = $params['perPage'] ?? Constants::PER_PAGE;
        return $this->model->with(
                'company:id,name',
                'agency:id,name',
                'prefecture:id,prefecture_name'
            )->withCount('users', 'buildings')
            ->when(isset($params['searchKey']), function ($query) use ($params) {
                $query->where(function ($query) use ($params) {
                    $query->where('name', 'like', '%' . $params['searchKey'] . '%')
                        ->orWhere('email', 'like', '%' . $params['searchKey'] . '%')
                        ->orWhere('phone', 'like', '%' . $params['searchKey'] . '%')
                        ->orWhere('address', 'like', '%' . $params['searchKey'] . '%');
                });
            })
            ->when(isset($params['prefectureId']), function ($query) use ($params) {
                $query->where('prefecture_id', $params['prefectureId']);
            })
            ->when(isset($params['agencyId']), function ($query) use ($params) {
                $query->where('agency_id', $params['agencyId']);
            })
            ->when(isset($params['companyId']), function ($query) use($params) {
                $query->where('company_id', $params['companyId']);
            })
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function getFacilityById(int $id): mixed
    {
        return $this->model->find($id);
    }

    /**
     * Get list of facility name
     * @return Collection
     */
    public function getAllFacilityName(): Collection
    {
        return $this->model
            ->select([
                'id',
                'name',
            ])
            ->orderBy('name', 'asc')
            ->get();
    }

    /**
     * @param int $agency_id
     * @param int $company_id
     * @return mixed
     */
    public function getFacilityByParent(int $agency_id, int $company_id): mixed
    {
        return $this->model->select('name', 'id')
            ->when($agency_id != 0, function ($query) use($agency_id) {
                $query->where('agency_id', $agency_id);
            })
            ->when($company_id != 0, function ($query) use($company_id) {
                $query->where('company_id', $company_id);
            })
            ->get();
    }
}
