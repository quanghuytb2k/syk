<?php

namespace App\Http\Controllers;

use App\Enums\ResponseCode;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\Http\Requests\User\CreateUserRequest;
use App\Http\Requests\User\UpdateUserRequest;

class UserController extends BaseApiController
{
    protected UserService $userService;

    /**
     * Create new class instance
     *
     * @param UserService $userService
     * @return void
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function list(Request $request): JsonResponse
    {
        $data = $this->userService->getListPaginate($request);
        return $this->responseSuccess($data);
    }

    public function listScreenData(): JsonResponse
    {
        $data = $this->userService->getListScreenData();
        return $this->responseSuccess($data);
    }

    /**
     * Create new user
     *
     * @param CreateUserRequest $request
     * @return JsonResponse
     */
    public function create(CreateUserRequest $request): JsonResponse
    {
        if ($this->userService->createUser($request)) {
            return $this->responseSuccess(['success' => true, 'message' => 'Create User Successfully']);
        }

        return $this->responseSuccess(['error' => false, 'message' => 'Create User Fail!']);
    }

    /**
     * Update user
     *
     * @param UpdateUserRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(UpdateUserRequest $request, int $id): JsonResponse
    {
        $this->userService->updateUser($id, $request);

        return $this->responseSuccess(['success' => true, 'message' => 'Update User Successfully']);
    }

    /**
     * Get detail info by id
     *
     * @param int $id
     * @return JsonResponse
     */
    public function detail(int $id): JsonResponse
    {
        $data = $this->userService->getUserById($id);
        return $this->responseSuccess($data);
    }

    public function delete(int $id): JsonResponse
    {
        if ($this->userService->deleteUser($id)) {
            return $this->responseSuccess([
                'status' => true,
                'message' => 'Delete Agency Successfully'
            ]);
        }
        return $this->responseError([
            'status' => false,
            'message' => 'Delete Agency Fail'
        ]);
    }
}
