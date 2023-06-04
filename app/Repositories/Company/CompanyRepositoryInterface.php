<?php

namespace App\Repositories\Company;

use Illuminate\Support\Collection;

interface CompanyRepositoryInterface
{
    /**
     * @param array $params
     * @return mixed
     */
    public function companyFilter(array $params): mixed;

    /**
     * Get list of company name
     * @return Collection
     */
    public function getAllCompanyName(): Collection;

    /**
     * @param int $id
     * @return mixed
     */
    public function getCompanyByID(int $id): mixed;

    /**
     * @param int $agencyId
     * @return mixed
     */
    public function getCompanyByAgencyId(int $agencyId): mixed;
}
