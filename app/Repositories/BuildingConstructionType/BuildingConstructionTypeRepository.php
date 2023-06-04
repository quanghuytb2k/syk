<?php

namespace App\Repositories\BuildingConstructionType;

use App\Models\BuildingConstructionType;
use Prettus\Repository\Eloquent\BaseRepository;

class BuildingConstructionTypeRepository extends BaseRepository implements BuildingConstructionTypeRepositoryInterface
{
    /**
     * @return string
     */
    public function model(): string
    {
        return BuildingConstructionType::class;
    }

    /**
     * @return mixed
     */
    public function listConstruction(): mixed
    {
        return $this->model->pluck('name', 'id')->all();
    }
}
