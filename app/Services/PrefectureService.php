<?php

namespace App\Services;

use App\Repositories\Prefecture\PrefectureRepositoryInterface;

class PrefectureService
{
    private PrefectureRepositoryInterface $prefectureRepository;

    public function __construct(
        PrefectureRepositoryInterface $prefectureRepository,
    ) {
        $this->prefectureRepository = $prefectureRepository;
    }

    public function getAll()
    {
        return $this->prefectureRepository
            ->orderBy('order', 'asc')
            ->all();
    }
}
