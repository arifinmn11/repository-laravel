<?php

namespace App\Repositories;

use App\Models\Menu;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface MenuIRepository
{
    public function getPagination(int $limit = 10, ?string $search = null, string $sortBy = 'id|asc', $filters = [], $customFilters = []): LengthAwarePaginator;
    public function getList($limit = null, $search = null, $isActive = true): Collection;
    public function getListDetailByUser(User $user): Collection;
    public function create(array $data): Menu;
    public function updateById(array $data, $id): Menu;
    public function deleteById($id): bool;
    public function findById($id): Menu;
}
