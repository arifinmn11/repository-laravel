<?php

namespace App\Repositories;

use App\Models\Branch;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface BranchIRepository
{
    public function getPagination($limit = 10, $search = null, $page = 1): LengthAwarePaginator;
    public function getList($limit = null, $search = null, $isActive = true): Collection;
    public function create(array $data): Branch;
    public function updateById(array $data, $id): Branch;
    public function deleteById($id): bool;
    public function findById($id): Branch;
}
