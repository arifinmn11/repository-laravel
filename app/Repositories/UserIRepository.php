<?php

namespace App\Repositories;

use App\Models\User;

interface UserIRepository
{
    public function createToken(array $data): array|null;
    public function create(array $data): User;
    public function updateById(array $data, $id): User;
    public function findById($id): User;
    public function deleteById($id): bool;
}
