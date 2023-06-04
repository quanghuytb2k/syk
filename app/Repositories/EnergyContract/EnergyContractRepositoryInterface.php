<?php

namespace App\Repositories\EnergyContract;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface EnergyContractRepositoryInterface
{
    /**
     * @param array $params
     * @return LengthAwarePaginator
     */
    public function filterContract(array $params): LengthAwarePaginator;

    /**
     * @param array $params
     * @return mixed
     */
    public function getContractByCondition(array $params): mixed;
}
