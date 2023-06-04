<?php

namespace App\Repositories\MaintainCompany;

interface MaintainCompanyRepositoryInterface {

    public function filterMaintainCompany(array $params);

    /**
     * @param int $id
     * @return mixed
     */
    public function getMaintainCompanyById(int $id): mixed;

    /**
     * @return mixed
     */
    public function getAll(): mixed;
}
