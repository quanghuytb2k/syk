<?php

namespace App\Repositories\EnergyType;

use App\Models\EnergyType;
use Prettus\Repository\Eloquent\BaseRepository;

class EnergyTypeRepository extends BaseRepository implements EnergyTypeRepositoryInterface
{
    /**
     * @return string
     */
    public function model(): string
    {
        return EnergyType::class;
    }

    /**
     * @return mixed
     */
    public function getListEnergyType(): mixed
    {
        return $this->model->select('id', 'name')->get();
    }
}
