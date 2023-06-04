<?php

namespace App\Repositories\MaintainCompany;

use App\Models\MaintainCompanyType;
use Illuminate\Database\Eloquent\Collection;
use Prettus\Repository\Eloquent\BaseRepository;

class MaintainCompanyTypeRepository extends BaseRepository implements MaintainCompanyTypeRepositoryInterface
{
    /**
     * @return string
     */
    public function model(): string
    {
        return MaintainCompanyType::class;
    }

    /**
     * @return Collection
     */
    public function getList(): Collection
    {
        return $this->model->all();
    }
}
