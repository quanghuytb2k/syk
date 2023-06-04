<?php

namespace App\Http\Controllers;

use App\Services\AlarmService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AlarmController extends BaseApiController
{
    private AlarmService $alarmService;

    public function __construct(AlarmService $alarmService)
    {
        $this->alarmService = $alarmService;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function list(Request $request): JsonResponse
    {
        $alarms = $this->alarmService->list($request->all());

        return $this->responseSuccess($alarms);
    }
}
