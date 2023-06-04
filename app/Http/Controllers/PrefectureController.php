<?php

namespace App\Http\Controllers;

use App\Services\PrefectureService;
use Illuminate\Http\Request;

class PrefectureController extends BaseApiController
{
    private PrefectureService $prefectureService;

    public function __construct(PrefectureService $prefectureService)
    {
        $this->prefectureService = $prefectureService;
    }

    public function all()
    {
        $data = $this->prefectureService->getAll();
        return $this->responseSuccess($data);
    }
}
