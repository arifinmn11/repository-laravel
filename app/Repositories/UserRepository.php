<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserRepository implements UserIRepository
{
    public function createToken(array $data): array|null
    {
        if (Auth::attempt($data)) {

            $auth = Auth::user();
            $user = User::find($auth->id);

            $token = $user->createToken('auth_token')->plainTextToken;

            return [
                'name' => $user->name,
                'email' => $user->email,
                'token' => $token,
                'type' => 'Bearer'
            ];
        } else {
            return null;
        }
    }

    public function create(array $data): User
    {
        try {

            $user = [
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ];

            $result = User::create($user);

            return $result;
        } catch (\Throwable $th) {
            throw new \Exception('Create failed');
        }
    }

    public function updateById(array $data, $id): User
    {
        try {
            $result = User::findOrFail($id);
            $result->update($data);

            if (isset($data['role'])) {
                $result->syncRoles([$data['role']]);
            }

            return $result;
        } catch (\Throwable $th) {
            throw new \Exception('Create failed');
        }
    }

    public function deleteById($id): bool
    {
        try {
            $result = User::findOrFail($id);
            $result->delete();

            return true;
        } catch (\Throwable $th) {
            throw new \Exception('Delete failed');
        }
    }

    public function findById($id): User
    {
        try {
            $result = User::findOrFail($id);

            return $result;
        } catch (\Throwable $th) {
            throw new \Exception('Get failed');
        }
    }
}
