<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\UserRegisterRequest;
use App\Http\Requests\Auth\UserRoleUpdateRequest;
use App\Http\Resources\LoginResponse;
use App\Http\Resources\User\UserRoleResource;
use App\Http\Resources\UserRegisterResource;
use App\Repositories\UserIRepository;
use App\Services\AuthService;
use App\Services\MenuService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthControllerApi extends BaseController
{

    protected $authService;
    protected $menuService;

    public function __construct(
        AuthService $authService,
        MenuService $menuService
    ) {
        $this->authService = $authService;
        $this->menuService = $menuService;
    }

    // /**
    //  * Register api
    //  *
    //  * @return \Illuminate\Http\Response
    //  */
    // public function register(Request $request): JsonResponse {}

    /**
     * Login
     *
     * @return \Illuminate\Http\Response
     */
    public function login(LoginRequest $request): JsonResponse
    {

        $loginRequest = $request->validated();

        try {
            $result = $this->authService->createToken($loginRequest);
        } catch (\Throwable $th) {
            return $this->errorResponse(null, 'Failed to login!', 401);
        }

        if (!$result) {
            return $this->errorResponse(null, 'Email or password is not valid!', 401);
        }

        return $this->successResponse(new LoginResponse($result));
    }

    /**
     * Register
     *
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Update User Role
     *
     * @return \Illuminate\Http\Response
     */
    public function updateUserRole(UserRoleUpdateRequest $request, $id): JsonResponse
    {
        $validated = $request->validated();

        try {
            $result = $this->authService->updateUserRoles($validated, $id);
        } catch (\Throwable $th) {
            return $this->errorResponse(null, 'Failed to update user role!', 401);
        }
        return $this->successResponse(new UserRoleResource($result), 'Successfully logged out');
    }


    /**
     * Logout api
     *
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return $this->successResponse(null, 'Successfully logged out');
    }


    public function getMenus()
    {
        try {
            $user = Auth::user();

            $result = $this->menuService->listMenuDetailByUser($user);

            return $this->successResponse($result);
        } catch (\Throwable $th) {
            return $this->errorResponse(null, 'Failed to get menus!', 401);
        }
    }
}
