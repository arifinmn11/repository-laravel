<?php

namespace App\Repositories;

interface UserIRepository
{
    public function createToken(array $data): array|null;
}
