<?php

namespace App\Services;

use App\Repositories\Facility\FacilityRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Prettus\Validator\Exceptions\ValidatorException;

class FacilityService
{
    /**
     * Facility constructor.
     * @param FacilityRepositoryInterface $facilityRepository
     */
    public function __construct(
        protected FacilityRepositoryInterface $facilityRepository
    )
    {
    }

    /**
     * @param array $searchParams
     * @return mixed
     */
    public function list(array $searchParams): mixed
    {
        return $this->facilityRepository->facilityFilter($searchParams);
    }

    /**
     * @param array $data
     * @return mixed
     * @throws ValidatorException
     */
    public function create(array $data): mixed
    {
        return $this->facilityRepository->create($data);
    }

    /**
     * @param array $data
     * @param int $id
     * @return LengthAwarePaginator|Collection|mixed
     * @throws ValidatorException
     */
    public function update(array $data, int $id): mixed
    {
        return $this->facilityRepository->update($data, $id);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function detail(int $id): mixed
    {
        return $this->facilityRepository->getFacilityById($id);
    }

    /**
     *
     */
    public function getAllFacilityName()
    {
        return $this->facilityRepository->getAllFacilityName();
    }

    public function deleteFacility(int $id): bool
    {
        DB::beginTransaction();
        try {
            $this->facilityRepository->delete($id);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            Log::error($e);
            return false;
        }
    }
}
