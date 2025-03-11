<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\Permission\Models\Permission;

interface PermissionIRepository
{
    public function getPagination(int $limit = 10, ?string $search = null, string $sortBy = 'id|asc', $filters = [], $customFilters = []): LengthAwarePaginator;
    public function getList($limit = null, $search = null, $isActive = true): Collection;
    public function create(array $data): Permission;
    public function updateById(array $data, $id): Permission;
    public function deleteById($id): bool;
    public function findById($id): Permission;
}
