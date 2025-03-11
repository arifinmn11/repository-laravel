<?php

namespace App\Repositories;

use Spatie\Permission\Models\Role;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface RoleIRepository
{
    public function getPagination(int $limit = 10, ?string $search = null, string $sortBy = 'id|asc', $filters = [], $customFilters = []): LengthAwarePaginator;
    public function getList($limit = null, $search = null, $isActive = true): Collection;
    public function create(array $data): Role;
    public function updateById(array $data, $id): Role;
    public function deleteById($id): bool;
    public function findById($id): Role;
    public function getPermissionByRoleId($id): Collection;
}
