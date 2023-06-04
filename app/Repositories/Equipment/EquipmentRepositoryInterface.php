<?php

namespace App\Repositories\Equipment;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface EquipmentRepositoryInterface
{
    /**
     * @param array $params
     * @return LengthAwarePaginator
     */
    public function filterEquipment(array $params): LengthAwarePaginator;
}
