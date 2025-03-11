<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\Permission\Models\Permission;

class PermissionRepository extends BaseRepository implements PermissionIRepository
{
    protected array $searchableFields = [];

    public function __construct(Permission $model)
    {
        parent::__construct($model);
    }

    /**
     * Get paginated Permission data.
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
        $query = Permission::where('is_active', $isActive)
            ->search($search)
            ->when($limit, function ($query) use ($limit) {
                return $query->limit($limit);
            })->get();

        return $query;
    }
    public function create(array $data): Permission
    {
        $check = Permission::where('name', $data['name'])
            ->where('guard_name', $data['guard_name'] ?? 'api')
            ->first();

        if ($check) {
            throw new \Exception('Permission already exists');
        }

        try {

            $result = Permission::create($data);

            return $result;
        } catch (\Throwable $th) {
            throw new \Exception('Create failed');
        }
    }

    public function updateById(array $data, $id): Permission
    {
        try {
            $query = Permission::findOrFail($id);
            $query->update($data);
            return $query;
        } catch (\Throwable $th) {
            throw new \Exception('Update failed');
        }
    }
    public function deleteById($id): bool
    {
        try {
            $query = Permission::findOrFail($id);
            $query->delete();
            return true;
        } catch (\Throwable $th) {
            throw new \Exception('Delete failed');
        }
    }
    public function findById($id): Permission
    {
        try {
            return Permission::findOrFail($id);
        } catch (\Throwable $th) {
            throw new \Exception('Permission not found');
        }
    }
}
