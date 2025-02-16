<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

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
}
