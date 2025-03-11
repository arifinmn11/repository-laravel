<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\UserRoleUpdateRequest;
use App\Http\Resources\LoginResponse;
use App\Http\Resources\UserRegisterResource;
use App\Repositories\UserIRepository;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuthControllerApi extends BaseController
{

    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    // /**
    //  * Register api
    //  *
    //  * @return \Illuminate\Http\Response
    //  */
    // public function register(Request $request): JsonResponse {}

    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(LoginRequest $request): JsonResponse
    {

        $loginRequest = $request->validated();

        $result = $this->authService->createToken($loginRequest);

        if (!$result) {
            return $this->errorResponse(null, 'Email or password is not valid!', 401);
        }

        return $this->successResponse(new LoginResponse($result));
    }

    public function register(UserRegisterRequest $request): JsonResponse
    {

        DB::beginTransaction();

        $registerRequest = $request->all();

        try {
            $result = $this->authService->createUser($registerRequest);

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();

            return $this->errorResponse(null, 'Failed to register user!', 401);
        }

        return $this->successResponse(new UserRegisterResource($result));
    }

    public function updateUserRole(UserRoleUpdateRequest $request, $id): JsonResponse
    {
        $validated = $request->validated();

        dd($validated);

        $result = $this->authService->updateUserRoles($validated, $id);

        return $this->successResponse($result, 'Successfully logged out');
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return $this->successResponse(null, 'Successfully logged out');
    }
}
