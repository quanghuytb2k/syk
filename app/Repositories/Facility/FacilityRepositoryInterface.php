<?php

namespace App\Repositories\Facility;

use Illuminate\Support\Collection;

interface FacilityRepositoryInterface {
    /**
     * @param array $params
     * @return mixed
     */
    public function facilityFilter(array $params): mixed;

    /**
     * @param int $id
     * @return mixed
     */
    public function getFacilityById(int $id): mixed;

    /**
     * Get list of facility name
     * @return Collection
     */
    public function getAllFacilityName(): Collection;

    /**
     * @param int $agency_id
     * @param int $company_id
     * @return mixed
     */
    public function getFacilityByParent(int $agency_id, int $company_id): mixed;
}
