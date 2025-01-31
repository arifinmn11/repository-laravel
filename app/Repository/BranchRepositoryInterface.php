<?php

namespace App\Repository;

use App\Models\Branch;
use Illuminate\Pagination\LengthAwarePaginator;

interface BranchRepositoryInterface
{
    public function pagination($limit = 10, $search = null, $page = 1): LengthAwarePaginator;

    public function options($limit = null, $search = null): array;

    public function create($data): Branch;

    public function updateById(array $data, $id): Branch;

    public function deleteById($id): void;

    public function getById($id): Branch;
}
