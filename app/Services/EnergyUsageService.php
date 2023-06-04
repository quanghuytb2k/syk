<?php

namespace App\Services;

use App\Models\EnergyUsage;
use App\Repositories\EnergyContractFile\EnergyFileRepositoryInterface;
use App\Repositories\EnergyUsage\EnergyUsageRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EnergyUsageService
{
    /**
     * Constructor
     * @param EnergyUsageRepositoryInterface $energyUsageRepository
     */
    public function __construct(
        protected EnergyUsageRepositoryInterface $energyUsageRepository,
        protected EnergyFileRepositoryInterface  $energyFileRepository
    )
    {
    }

    /**
     * @param array $params
     * @return bool
     */
    public function createEnergyUsage(array $params): mixed
    {
        return $this->energyUsageRepository->create($params);
    }

    /**
     * @param array $params
     * @param int $usageId
     * @return bool
     */
    public function updateEnergyUsage(array $params, int $usageId): bool
    {
        try {
            $this->energyUsageRepository->update($params, $usageId);

            return true;
        } catch (\Exception $exception) {
            Log::error($exception);
            return false;
        }
    }

    public function deleteFile(int $id, string $path)
    {
        $currentRecord = EnergyUsage::select('files')->find($id);
        $files = [];
        $files['files'] = collect($currentRecord['files'])->map(function ($items) use ($path) {
            if ($items['path'] != $path) {
                return $items;
            }
        })->filter(function ($items) {
            return $items;
        })->values()->toArray();

        return $this->energyUsageRepository->update($files, $id);
    }

    public function updateFile(int $id, array $params): mixed
    {
        $currentRecord = EnergyUsage::select('files')->find($id);
        $currentRecord['files'] = @$currentRecord['files'] ?: [];
        $files = [];
        $files['files'] = array_merge($currentRecord['files'], $params);

        return $this->energyUsageRepository->update($files, $id);
    }

    public function deleteUsage(int $id): bool
    {
        DB::beginTransaction();
        try {
            $this->energyUsageRepository->delete($id);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            Log::error($e);
            return false;
        }
    }
}
