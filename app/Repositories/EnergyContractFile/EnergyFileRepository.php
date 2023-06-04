<?php

namespace App\Repositories\EnergyContractFile;

use App\Models\EnergyFile;
use Prettus\Repository\Eloquent\BaseRepository;

class EnergyFileRepository extends BaseRepository implements EnergyFileRepositoryInterface
{
    /**
     * @return string
     */
    public function model(): string
    {
        return EnergyFile::class;
    }

    /**
     * @param array $pathData
     * @return void
     */
    public function createMultiple(array $pathData): void
    {
        $this->model->insert($pathData);
    }
}
