<?php

namespace App\Services;

use App\Repositories\Company\CompanyRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Prettus\Validator\Exceptions\ValidatorException;

class CompanyService
{
    /**
     * Company constructor.
     * @param CompanyRepositoryInterface $companyRepository
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(
        protected CompanyRepositoryInterface $companyRepository,
        protected UserRepositoryInterface $userRepository
    )
    {}

    /**
     * @param array $searchParams
     * @return mixed
     */
    public function list(array $searchParams): mixed
    {
        return $this->companyRepository->companyFilter($searchParams);
    }

    /**
     * @param array $params
     * @return mixed
     * @throws ValidatorException
     */
    public function create(array $params): mixed
    {
        return $this->companyRepository->create($params);
    }

    /**
     * @param array $params
     * @param int $id
     * @return LengthAwarePaginator|Collection|mixed
     * @throws ValidatorException
     */
    public function update(array $params, int $id): mixed
    {
        return $this->companyRepository->update($params, $id);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function detail(int $id): mixed
    {
        return $this->companyRepository->getCompanyByID($id);
    }

    /**
     *
     */
    public function getAllCompanyName()
    {
        return $this->companyRepository->getAllCompanyName();
    }

    /**
     * @param int $company_id
     * @return boolean
     */
    public function deleteCompany(int $company_id): bool
    {
        DB::beginTransaction();
        try {
            $this->userRepository->removeUserByConditional(null, $company_id);
            $this->companyRepository->delete($company_id);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            Log::error($e);
            return false;
        }
    }
}
