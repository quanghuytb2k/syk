<?php

namespace App\Repositories\MasterConfigure;

use App\Models\MasterConfigure;
use Prettus\Repository\Eloquent\BaseRepository;

class MasterConfigureRepository extends BaseRepository implements MasterConfigureRepositoryInterface
{
    /**
     * @return string
     */
    public function model(): string
    {
        return MasterConfigure::class;
    }

    public function getListMasterTable()
    {
        return $this->model->select('name')->show()->get()->map(function ($item) {
            return $item->name;
        });
    }
}
