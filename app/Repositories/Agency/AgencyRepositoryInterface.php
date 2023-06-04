<?php

namespace App\Repositories\Agency;

use App\Repositories\BaseRepositoryInterface;
use Illuminate\Support\Collection;

interface AgencyRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * @param array $data
     * @return mixed
     */
    public function listAgency(array $data): mixed;

    /**
     * @param array $data
     * @return mixed
     */
    public function createAgency(array $data): mixed;

    /**
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateAgency(int $id, array $data): mixed;

    /**
     * Get list of agency name
     * @return Collection
     */
    public function getAllAgencyName(): Collection;

    /**
     * @param int $id
     * @return mixed
     */
    public function getDetailById(int $id): mixed;
}
