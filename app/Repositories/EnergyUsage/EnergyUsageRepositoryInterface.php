<?php

namespace App\Repositories\EnergyUsage;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface EnergyUsageRepositoryInterface
{
    /**
     * @param array $params
     * @return LengthAwarePaginator
     */
    public function filterUsage(array $params): LengthAwarePaginator;
}
