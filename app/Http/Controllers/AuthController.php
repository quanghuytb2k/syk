<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\ChangepassRequest;
use App\Http\Requests\Auth\ForgotpassRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\ResetpassRequest;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class AuthController extends BaseApiController
{

    public function __construct(
        protected AuthService $authService
    )
    {
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        $credentials = request(['email', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            return $this->responseError(['error' => 'Unauthorized'], 401);
        }

        return $this->responseSuccess([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    /**
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        auth()->logout();
        return $this->responseSuccess(true);
    }

    /**
     * @param RegisterRequest $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $this->authService->register($request);
        return $this->responseSuccess(true);
    }

    /**
     * @return JsonResponse
     */
    public function me(): JsonResponse
    {
        return $this->responseSuccess(auth()->user());
    }

    /**
     * Refresh a token.
     *
     * @return JsonResponse
     */
    public function refresh(): JsonResponse
    {
        return $this->responseSuccess([
            'access_token' => auth()->refresh(),
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function activate(Request $request): JsonResponse
    {
        $res = $this->authService->activate($request);
        if ($res) {
            return $this->responseSuccess(true);
        } else {
            return $this->responseError([]);
        }
    }

    /**
     * @param ChangepassRequest $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function changepass(ChangepassRequest $request): JsonResponse
    {
        $this->authService->changepass($request);
        return $this->responseSuccess(true);
    }

    /**
     * @param ForgotpassRequest $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function forgotpass(ForgotpassRequest $request): JsonResponse
    {
        $res = $this->authService->forgotpass($request);
        if ($res) {
            return $this->responseSuccess(true);
        } else {
            return $this->responseError([]);
        }
    }

    /**
     * @param ResetpassRequest $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function resetpass(ResetpassRequest $request): JsonResponse
    {
        $res = $this->authService->resetpass($request);
        if ($res) {
            return $this->responseSuccess(true);
        } else {
            return $this->responseError([]);
        }
    }

    /**
     * @return JsonResponse
     */
    public function getRole(): JsonResponse
    {
        $role = User::find(auth()->id())->getRoleNames();

        return $this->responseSuccess($role);
    }

    /**
     * @return JsonResponse
     */
    public function getAllRoles(): JsonResponse
    {
        $roles = $this->authService->getAllRoles();

        return $this->responseSuccess($roles);
    }
}
