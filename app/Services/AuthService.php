<?php

namespace App\Services;

use App\Http\Resources\LoginResponse;
use App\Repositories\UserIRepository;
use Illuminate\Validation\ValidationException;

class AuthService
{
    protected $userRepository;

    public function __construct(UserIRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function createToken(array $data): LoginResponse
    {
        $requestToken = $this->userRepository->createToken($data);

        if (!$requestToken) {
            throw new ValidationException(null, 'Email or password is not valid!', 401);
        }
        $result = (object) [
            'name' => $requestToken['name'],
            'email' => $requestToken['email'],
            'token' => $requestToken['token'],
            'type' => $requestToken['type']
        ];

        return new LoginResponse($result);
    }
}
