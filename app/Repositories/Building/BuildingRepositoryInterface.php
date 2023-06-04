<?php

namespace App\Repositories\Building;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface BuildingRepositoryInterface
{
    /**
     * @param array $params
     * @return LengthAwarePaginator
     */
    public function filterBuilding(array $params): LengthAwarePaginator;

    /**
     * @param int $id
     * @return mixed
     */
    public function getBuildingById(int $id): mixed;

    /**
     * @param array $params
     * @return mixed
     */
    public function getListBuilding(array $params): mixed;

}
