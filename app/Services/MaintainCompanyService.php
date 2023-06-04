<?php

namespace App\Services;

use App\Repositories\MaintainCompany\MaintainCompanyRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MaintainCompanyService
{
    /**
     * @param MaintainCompanyRepositoryInterface $maintainCompanyRepository
     */
    public function __construct(
        protected MaintainCompanyRepositoryInterface $maintainCompanyRepository
    )
    {
    }

    /**
     * @param array $params
     * @return bool
     */
    public function store(array $params): bool
    {
        try {
            $params['created_by'] = auth()->id();
            $params['address'] = $params['address'] ?? '';
            $this->maintainCompanyRepository->create($params);

            return true;
        } catch (\Exception $exception) {
            Log::error($exception);
            return false;
        }
    }

    /**
     * @param array $params
     * @param int $maintain_id
     * @return bool
     */
    public function update(array $params, int $maintain_id): bool
    {
        try {
            $this->maintainCompanyRepository->update($params, $maintain_id);

            return true;
        } catch (\Exception $exception) {
            Log::error($exception);
            return false;
        }
    }

    public function deleteCompany(int $id): bool
    {
        DB::beginTransaction();
        try {
            $this->maintainCompanyRepository->delete($id);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            Log::error($e);
            return false;
        }
    }
}
