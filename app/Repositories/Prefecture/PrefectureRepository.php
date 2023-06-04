<?php

namespace App\Repositories\Prefecture;

use App\Models\Prefecture;
use Prettus\Repository\Eloquent\BaseRepository;

class PrefectureRepository extends BaseRepository implements PrefectureRepositoryInterface
{

    /**
     * @return string
     */
    public function model(): string
    {
        return Prefecture::class;
    }
}
