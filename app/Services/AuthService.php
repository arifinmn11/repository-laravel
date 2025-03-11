<?php

namespace App\Services;

use App\Http\Resources\LoginResponse;
use App\Models\User;
use App\Repositories\UserIRepository;
use Illuminate\Support\Facades\Hash;
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

    public function createUser(array $data): User
    {
        try {
            $result = $this->userRepository->create($data);

            $result->assignRole($data['roles']);
        } catch (\Throwable $th) {
            throw new \Exception('Create failed');
        }
        return $result;
    }

    public function updateUserRoles(array $data, $id): User
    {
        try {

            $result = $this->userRepository->findById($id);

            $result->syncRoles($data['roles']);

            return $result;
        } catch (\Throwable $th) {
            throw new \Exception('Update failed');
        }
    }
}
