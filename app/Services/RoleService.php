<?php

namespace App\Services;

use App\Repositories\RoleIRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\Permission\Models\Role;

class RoleService
{
    protected $repository;

    public function __construct(RoleIRepository $repository)
    {
        $this->repository = $repository;
    }

    public function findRoleById($id): Role
    {
        return $this->repository->findById($id);
    }

    public function createRole(array $data): Role
    {
        $result = $this->repository->create($data);

        $permissionsID = array_map(
            function ($value) {
                return (int)$value;
            },
            $data['permission']
        );

        $result->syncPermissions($permissionsID);

        return $result;
    }

    public function updateRoleById(array $data, $id): Role
    {

        $result = $this->repository->updateById($data, $id);


        $permissionsID = array_map(
            function ($value) {
                return (int)$value;
            },
            $data['permission']
        );

        $result->syncPermissions($permissionsID);

        return $result;
    }

    public function deleteRoleById($id): bool
    {
        return $this->repository->deleteById($id);
    }

    public function listRole($limit = null, $search = null, $isActive = true): Collection
    {
        return $this->repository->getList($limit, $search, $isActive);
    }

    public function getPagination(int $limit = 10, ?string $search = null, string $sortBy = 'id|asc', $filters = [], $customFilters): LengthAwarePaginator
    {
        return $this->repository->getPagination($limit, $search, $sortBy, $filters, $customFilters);
    }

    public function getPermissionByRoleId($id): Collection
    {
        return $this->repository->getPermissionByRoleId($id);
    }
}
