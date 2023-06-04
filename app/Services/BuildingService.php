<?php

namespace App\Services;

use App\Repositories\Building\BuildingRepository;
use App\Repositories\Building\BuildingRepositoryInterface;
use App\Repositories\BuildingConstructionType\BuildingConstructionTypeRepositoryInterface;
use App\Repositories\BuildingType\BuildingTypeRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Prettus\Validator\Exceptions\ValidatorException;

class BuildingService
{
    /**
     * Building constructor.
     * @param BuildingRepositoryInterface $buildingRepository
     * @param BuildingTypeRepositoryInterface $buildingTypeRepository
     * @param BuildingConstructionTypeRepositoryInterface $buildingConstructionTypeRepository
     */
    public function __construct(
        protected BuildingRepositoryInterface $buildingRepository,
        protected BuildingTypeRepositoryInterface $buildingTypeRepository,
        protected BuildingConstructionTypeRepositoryInterface $buildingConstructionTypeRepository
    )
    {}

    /**
     * @param array $searchParams
     * @return LengthAwarePaginator
     */
    public function list(array $searchParams): LengthAwarePaginator
    {
        return $this->buildingRepository->filterBuilding($searchParams);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function detail(int $id): mixed
    {
        return $this->buildingRepository->getBuildingById($id);
    }

    /**
     * @param array $params
     * @return LengthAwarePaginator|Collection|mixed
     * @throws ValidatorException
     */
    public function create(array $params): mixed
    {
        return $this->buildingRepository->create($params);
    }

    /**
     * @param array $params
     * @param int $id
     * @return LengthAwarePaginator|Collection|mixed
     * @throws ValidatorException
     */
    public function update(array $params, int $id): mixed
    {
        return $this->buildingRepository->update($params, $id);
    }

    /**
     * @return mixed
     */
    public function getListBuildingType(): mixed
    {
        return $this->buildingTypeRepository->listBuildingType();
    }

    /**
     * @return mixed
     */
    public function getListConstruction(): mixed
    {
        return $this->buildingConstructionTypeRepository->listConstruction();
    }

    /**
     * @param int $id
     * @return boolean
     */
    public function deleteCompany(int $id): bool
    {
        try {
            $this->buildingRepository->delete($id);
            return true;
        } catch (\Exception $e) {
            Log::error($e);
            return false;
        }
    }
}
