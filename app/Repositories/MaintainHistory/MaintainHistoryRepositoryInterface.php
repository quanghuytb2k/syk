<?php

namespace App\Repositories\MaintainHistory;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface MaintainHistoryRepositoryInterface
{
    /**
     * @param array $params
     * @param int $equipmentId
     * @return LengthAwarePaginator
     */
    public function filterMaintainHistory(array $params, int $equipmentId): LengthAwarePaginator;

    /**
     * @param int $id
     * @return mixed
     */
    public function getDetailById(int $id): mixed;
}
