<?php

namespace App\Repositories\Company;

use App\Enums\CompanyEnums;
use App\Enums\Constants;
use App\Models\Company;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Prettus\Repository\Eloquent\BaseRepository;

class CompanyRepository extends BaseRepository implements CompanyRepositoryInterface
{

    /**
     * @return string
     */
    public function model(): string
    {
        return Company::class;
    }

    /**
     * @param array $params
     * @return mixed
     */
    public function companyFilter(array $params): mixed
    {
        $perPage = $params['perPage'] ?? Constants::PER_PAGE;
        return $this->model
            ->select(
                'companies.*',
                DB::raw('(SELECT count(users.id) FROM facilities LEFT JOIN users ON facilities.id = users.facility_id WHERE companies.id = facilities.company_id) as facility_users')
            )
            ->with('prefecture')
            ->withCount('user')
            ->when(isset($params['searchKey']), function ($query) use ($params) {
                $query->where(function ($query) use ($params) {
                    $query->where('companies.name', 'like', '%' . $params['searchKey'] . '%')
                        ->orWhere('companies.email', 'like', '%' . $params['searchKey'] . '%')
                        ->orWhere('companies.job_title', 'like', '%' . $params['searchKey'] . '%')
                        ->orWhere('companies.phone', 'like', '%' . $params['searchKey'] . '%');
                });
            })
            ->when(isset($params['agencyId']), function ($query) use ($params) {
                $query->where('companies.agency_id', $params['agencyId']);
            })
            ->when(isset($params['listType']) && in_array($params['listType'], [CompanyEnums::NON_LIST, CompanyEnums::TABLE_LIST]), function ($query) use ($params) {
                $query->where('companies.is_stock_listing', $params['listType']);
            })
            ->paginate($perPage);
     }

     /**
     * Get list of company name
     * @return Collection
     */
    public function getAllCompanyName(): Collection
    {
        return $this->model
            ->select([
                'id',
                'name',
            ])
            ->orderBy('name', 'asc')
            ->get();
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function getCompanyByID(int $id): mixed
    {
        return $this->model->find($id);
    }

    /**
     * @param int $agencyId
     * @return mixed
     */
    public function getCompanyByAgencyId(int $agencyId): mixed
    {
        return $this->model->select('name', 'id')->where('agency_id', $agencyId)->get();
    }
}
