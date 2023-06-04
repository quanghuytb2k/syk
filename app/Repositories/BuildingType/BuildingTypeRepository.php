<?php

namespace App\Repositories\BuildingType;

use App\Models\BuildingType;
use Prettus\Repository\Eloquent\BaseRepository;

class BuildingTypeRepository extends BaseRepository implements BuildingTypeRepositoryInterface
{
    /**
     * @return string
     */
    public function model(): string
    {
        return BuildingType::class;
    }

    /**
     * @return mixed
     */
    public function listBuildingType(): mixed
    {
        return $this->model->pluck('name', 'id')->all();
    }
}
