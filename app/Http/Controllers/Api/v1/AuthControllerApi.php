<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\LoginResponse;
use App\Repositories\UserIRepository;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
}
