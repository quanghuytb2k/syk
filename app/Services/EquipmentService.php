<?php

namespace App\Services;

use App\Repositories\Equipment\EquipmentRepositoryInterface;
use App\Repositories\MaintainHistory\MaintainHistoryRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EquipmentService
{
    public function __construct(
        protected EquipmentRepositoryInterface       $equipmentRepository,
        protected MaintainHistoryRepositoryInterface $maintainHistoryRepository,
        protected StorageService                     $storageService
    )
    {
    }

    /**
     * @param array $params
     * @return bool
     */
    public function createEquipment(array $params): bool
    {
        try {
            $this->equipmentRepository->create($params);

            return true;
        } catch (\Exception $exception) {
            Log::error($exception);
            return false;
        }
    }

    /**
     * @param array $params
     * @param int $id
     * @return bool
     */
    public function updateEquipment(array $params, int $id): bool
    {
        try {
            $this->equipmentRepository->update($params, $id);

            return true;
        } catch (\Exception $exception) {
            Log::error($exception);
            return false;
        }
    }

    /**
     * @param array $params
     * @return bool
     */
    public function createMaintainHistory(array $params): bool
    {
        try {
            $this->maintainHistoryRepository->create($params);
            return true;
        } catch (\Throwable $throwable) {
            Log::error($throwable);
            return false;
        }
    }

    /**
     * @param array $params ['id', 'files']
     * @return bool
     */
    public function uploadHistoryFiles(array $params): bool
    {
        try {
            $history = $this->maintainHistoryRepository->find($params['id']);
            if ($history) {
                $updateFiles = $history->files ?? [];
                $files = $this->storageService->uploadFiles($params['files']);

                $updateFiles = array_merge($updateFiles, $files);

                $this->maintainHistoryRepository->update([
                    'files' => $updateFiles
                ], $history->id);
            }

            return true;
        } catch (\Throwable $throwable) {
            Log::error($throwable);
            return false;
        }
    }

    /**
     * @param array $params
     * @return bool
     */
    public function deleteHistoryFile(array $params): bool
    {
        try {
            $history = $this->maintainHistoryRepository->find($params['id']);
            if ($history) {
                $updateFiles = $history->files;
                unset($updateFiles[$params['index']]);
                $this->maintainHistoryRepository->update([
                    'files' => $updateFiles
                ], $history->id);
            }

            return true;
        } catch (\Throwable $throwable) {
            Log::error($throwable);
            return false;
        }
    }

    /**
     * @param array $params
     * @param int $id
     * @return bool
     */
    public function updateMaintainHistory(array $params, int $id): bool
    {
        try {
            $this->maintainHistoryRepository->update($params, $id);
            return true;
        } catch (\Throwable $throwable) {
            Log::error($throwable);
            return false;
        }
    }

    public function deleteEquipment(int $id): bool
    {
        DB::beginTransaction();
        try {
            $this->equipmentRepository->delete($id);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            Log::error($e);
            return false;
        }
    }
}
