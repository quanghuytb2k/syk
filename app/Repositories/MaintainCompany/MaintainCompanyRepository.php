<?php

namespace App\Repositories\MaintainCompany;

use App\Enums\Constants;
use App\Models\MaintainCompany;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Prettus\Repository\Eloquent\BaseRepository;

class MaintainCompanyRepository extends BaseRepository implements MaintainCompanyRepositoryInterface
{
    /**
     * @return string
     */
    public function model(): string
    {
        return MaintainCompany::class;
    }

    /**
     * @param array $params
     * @return LengthAwarePaginator
     */
    public function filterMaintainCompany(array $params): LengthAwarePaginator
    {
        $perPage = $params['perPage'] ?? Constants::PER_PAGE;

        return $this->model->with([
                'prefecture:id,prefecture_name',
                'type:id,name',
                'detailType:id,name'
            ])
            ->where('created_by', auth()->id())
            ->when(isset($params['prefecture']), function ($query) use ($params) {
                $query->where('prefecture_id', $params['prefecture']);
            })
            ->when(isset($params['company_type']), function ($query) use ($params) {
                $query->where('company_type', $params['company_type']);
            })
            ->when(isset($params['type']), function ($query) use ($params) {
                $query->where('maintain_company_type_id', $params['type']);
            })
            ->when(isset($params['detailType']), function ($query) use ($params) {
                $query->where('maintain_company_detail_type_id', $params['detailType']);
            })
            ->orderBy('created_at', 'DESC')
            ->paginate($perPage);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function getMaintainCompanyById(int $id): mixed
    {
        return $this->model->find($id);
    }

    /**
     * @return mixed
     */
    public function getAll(): mixed
    {
        return $this->model->select('name', 'id')->get();
    }
}
