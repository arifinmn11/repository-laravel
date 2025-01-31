<?php

namespace App\Repository;

use App\Models\Branch;
use Illuminate\Pagination\LengthAwarePaginator;

class BranchRepository implements BranchRepositoryInterface
{
    protected $model;

    public function __construct(Branch $model)
    {
        $this->model = $model;
    }

    public function pagination($limit = 10, $search = null, $page = 1): LengthAwarePaginator
    {
        $query = $this->model::when($search, function ($query, $search) {
            return $query->where(function ($query) use ($search) {
                $query->orwhere('name', 'like', "%$search%")
                    ->orwhere('code', 'like', "%$search%")
                    ->orwhere('address', 'like', "%$search%")
                    ->orwhere('phone', 'like', "%$search%")
                    ->orwhere('email', 'like', "%$search%");
            });
        })->paginate($limit, 'id', $page);

        return $query;
    }

    public function options($search = null, $limit = null): array
    {
        $query = $this->model::when($search, function ($query, $search) {
            return $query->where('name', 'like', "%$search%");
        })->limit($limit)->get();

        return $query;
    }

    public function create($data): Branch
    {
        return $this->model::create($data);
    }

    public function updateById($data, $id): Branch
    {
        try {

            $branch = $this->model::findOrFail($id);
            $branch->update($data);

            return $branch;
        } catch (\Throwable $th) {
            throw new \Exception('Branch not found');
        }
    }

    public function deleteById($id): void
    {
        try {
            $branch = $this->model::findOrFail($id);
            $branch->delete();
        } catch (\Throwable $th) {
            throw new \Exception('Branch not found');
        }
    }

    public function getById($id): Branch
    {
        try {
            return $this->model::findOrFail($id);
        } catch (\Throwable $th) {
            throw new \Exception('Branch not found');
        }
    }
}
