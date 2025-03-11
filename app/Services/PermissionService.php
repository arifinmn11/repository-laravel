<?php

namespace App\Services;

use App\Repositories\PermissionIRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\Permission\Models\Permission;

class PermissionService
{
    protected $repository;

    public function __construct(PermissionIRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getPermissionById($id): Permission
    {
        return $this->repository->findById($id);
    }

    public function createPermission(array $data): Permission
    {
        return $this->repository->create($data);
    }

    public function updatePermissionById(array $data, $id): Permission
    {
        return $this->repository->updateById($data, $id);
    }

    public function deletePermissionById($id): bool
    {
        return $this->repository->deleteById($id);
    }

    public function listPermission($limit = null, $search = null, $isActive = true): Collection
    {
        return $this->repository->getList($limit, $search, $isActive);
    }

    public function getPagination(int $limit = 10, ?string $search = null, string $sortBy = 'id|asc', $filters = [], $customFilters): LengthAwarePaginator
    {
        return $this->repository->getPagination($limit, $search, $sortBy, $filters, $customFilters);
    }
}
