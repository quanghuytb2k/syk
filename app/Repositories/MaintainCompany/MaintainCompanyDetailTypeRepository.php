<?php

namespace App\Repositories\MaintainCompany;

use App\Models\MaintainCompanyDetailType;
use Illuminate\Database\Eloquent\Collection;
use Prettus\Repository\Eloquent\BaseRepository;

class MaintainCompanyDetailTypeRepository extends BaseRepository implements MaintainCompanyDetailTypeRepositoryInterface
{
    /**
     * @return string
     */
    public function model(): string
    {
        return MaintainCompanyDetailType::class;
    }

    /**
     * @return Collection
     */
    public function getList(): Collection
    {
        return $this->model->all();
    }
}
