<?php

namespace App\Repositories\MaintainHistory;

use App\Enums\Constants;
use App\Models\MaintainHistory;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Prettus\Repository\Eloquent\BaseRepository;

class MaintainHistoryRepository extends BaseRepository implements MaintainHistoryRepositoryInterface
{

    /**
     * @return string
     */
    public function model(): string
    {
        return MaintainHistory::class;
    }

    /**
     * @param array $params
     * @param int $equipmentId
     * @return LengthAwarePaginator
     */
    public function filterMaintainHistory(array $params, int $equipmentId): LengthAwarePaginator
    {
        $perPage = $params['perPage'] ?? Constants::PER_PAGE;
        return $this->model->with([
                'maintainCompany:name,id'
            ])
            ->where('equipment_id', $equipmentId)
            ->paginate($perPage);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function getDetailById(int $id): mixed
    {
        return $this->model->find($id);
    }
}
