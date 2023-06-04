<?php

namespace App\Repositories\EnergyContractFile;

interface EnergyFileRepositoryInterface
{
    /**
     * @param array $pathData
     * @return void
     */
    public function createMultiple(array $pathData): void;
}
