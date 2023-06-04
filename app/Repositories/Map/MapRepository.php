<?php

namespace App\Repositories\Map;


use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface Map.
 *
 * @package namespace App\Repositories\Map;
 */
interface MapRepository
{
    public function list(array $params): LengthAwarePaginator;

    public function listDrawingType(): mixed;

    public function getMapById(int $id): mixed;
}
