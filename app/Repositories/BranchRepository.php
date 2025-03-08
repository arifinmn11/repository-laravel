<?php

namespace App\Repositories;

use App\Models\Branch;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class BranchRepository extends BaseRepository implements BranchIRepository
{
    protected array $searchableFields = ['name', 'code', 'address'];

    public function __construct(Branch $branch)
    {
        parent::__construct($branch);
    }

    /**
     * Get paginated branch data.
     */
    public function getPaginatedBranches(int $limit = 10, ?string $search = null, string $sortBy = 'id|asc', $filters = [], $customFilters = []): LengthAwarePaginator
    {
        $this->applySearchKeyword($search, $this->searchableFields);
        $this->applyFilters($filters);
        $this->applySortBy($sortBy);

        if ($customFilters['names'])
            $this->model = $this->model->whereIn('name', $customFilters['names']);

        return $this->getPaginatedResults($limit);
    }

    public function getPagination($limit = 10, $search = null, $page = 1, $filters = [], $sortBy = 'id|asc'): LengthAwarePaginator
    {
        $query = Branch::search($search)
            ->when(count($filters) > 0, function ($query) use ($filters) {
                foreach ($filters as $key => $value) {
                    $query->where($key, $value);
                }
                return $query;
            })
            ->sortOrderBy($sortBy)
            ->paginate($limit, ['*'], 'page', $page);

        return $query;
    }

    public function getList($limit = null, $search = null, $isActive = true): Collection
    {
        $query = Branch::where('is_active', $isActive)
            ->search($search)
            ->when($limit, function ($query) use ($limit) {
                return $query->limit($limit);
            })->get();

        return $query;
    }
    public function create(array $data): Branch
    {
        try {
            $result = Branch::create($data);

            return $result;
        } catch (\Throwable $th) {
            throw new \Exception('Create failed');
        }
    }
    public function updateById(array $data, $id): Branch
    {
        try {
            $branch = Branch::findOrFail($id);
            $branch->update($data);
            return $branch;
        } catch (\Throwable $th) {
            throw new \Exception('Update failed');
        }
    }
    public function deleteById($id): bool
    {
        try {
            $branch = Branch::findOrFail($id);
            $branch->delete();
            return true;
        } catch (\Throwable $th) {
            throw new \Exception('Delete failed');
        }
    }
    public function findById($id): Branch
    {
        try {
            return Branch::findOrFail($id);
        } catch (\Throwable $th) {
            throw new \Exception('Branch not found');
        }
    }
}
