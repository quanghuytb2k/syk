<?php

namespace App\Services;

use App\Models\EnergyContract;
use App\Repositories\EnergyContract\EnergyContractRepositoryInterface;
use App\Repositories\EnergyContractFile\EnergyFileRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EnergyContractService
{
    /**
     * constructor
     */
    public function __construct(
        protected EnergyContractRepositoryInterface $energyContractRepository,
        protected EnergyFileRepositoryInterface $energyFileRepository
    )
    {}

    /**
     * @param array $params
     * @param array $pathFiles
     * @return bool
     */
    public function createContract(array $params): bool
    {
        DB::beginTransaction();
        try {
            $pathData = [];
            $contract = $this->energyContractRepository->create($params);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return false;
        }
    }

    /**
     * @param int $id
     * @param array $params
     * @return mixed
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function updateFile(int $id, array $params): mixed
    {
        $currentRecord = EnergyContract::select('files')->find($id);
        $currentRecord['files'] = @$currentRecord['files'] ?: [];
        $files = [];
        $files['files'] = array_merge($currentRecord['files'], $params);

        return $this->energyContractRepository->update($files, $id);
    }

    /**
     * @param int $id
     * @param string $path
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Support\Collection|mixed
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function deleteFile(int $id, string $path)
    {
        $currentRecord = EnergyContract::select('files')->find($id);
        $files = [];
        $files['files'] = collect($currentRecord['files'])->map(function ($items) use ($path) {
            if ($items['path'] != $path) {
                return $items;
            }
        })->filter(function ($items) {
            return $items;
        })->values()->toArray();

        return $this->energyContractRepository->update($files, $id);
    }
}
