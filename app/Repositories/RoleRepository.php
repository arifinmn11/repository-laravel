<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleRepository extends BaseRepository implements RoleIRepository
{
    protected array $searchableFields = [];

    public function __construct(Role $model)
    {
        parent::__construct($model);
    }

    /**
     * Get paginated Role data.
     */
    public function getPagination(int $limit = 10, ?string $search = null, string $sortBy = 'id|asc', $filters = [], $customFilters = []): LengthAwarePaginator
    {
        $this->applySearchKeyword($search, $this->searchableFields);
        $this->applyFilters($filters);
        $this->applySortBy($sortBy);


        return $this->getPaginatedResults($limit);
    }

    public function getList($limit = null, $search = null, $isActive = true): Collection
    {
        $query = Role::where('is_active', $isActive)
            ->search($search)
            ->when($limit, function ($query) use ($limit) {
                return $query->limit($limit);
            })->get();

        return $query;
    }
    public function create(array $data): Role
    {


        if (array_key_exists('guard_name', $data)) {
            $data['guard_name'] = 'api';
        }

        if (!isset($data['guard_name'])) {
            $data['guard_name'] = 'api';
        }

        $check = Permission::where('name', $data['name'])
            ->where('guard_name', $data['guard_name'] ?? 'api')
            ->first();

        if ($check) {
            throw new \Exception('Role already exists');
        }

        try {
            $result = Role::create($data);

            return $result;
        } catch (\Throwable $th) {
            throw new \Exception('Create failed');
        }
    }
    public function updateById(array $data, $id): Role
    {
        try {
            $query = Role::findOrFail($id);
            $query->update($data);
            return $query;
        } catch (\Throwable $th) {
            throw new \Exception('Update failed');
        }
    }
    public function deleteById($id): bool
    {
        try {
            $query = Role::findOrFail($id);
            $query->delete();
            return true;
        } catch (\Throwable $th) {
            throw new \Exception('Delete failed');
        }
    }
    public function findById($id): Role
    {
        try {
            return Role::findOrFail($id);
        } catch (\Throwable $th) {
            throw new \Exception('Role not found');
        }
    }

    public function getPermissionByRoleId($id): Collection
    {
        try {
            $results =  Permission::join("role_has_permissions", "role_has_permissions.permission_id", "=", "permissions.id")
                ->where("role_has_permissions.role_id", $id)
                ->get();

            return $results;
        } catch (\Throwable $th) {
            throw new \Exception('Role not found');
        }
    }
}
