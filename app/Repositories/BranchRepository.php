<?php

namespace App\Repositories;

use App\Models\Branch;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class BranchRepository implements BranchIRepository
{
    public function getPagination($limit = 10, $search = null, $page = 1): LengthAwarePaginator
    {
        $query = Branch::when($search, function ($query, $search) {
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
    public function getList($limit = null, $search = null): Collection
    {
        $query = Branch::when($search, function ($query, $search) {
            return $query->where('name', 'like', "%$search%");
        })->limit($limit)->get();

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
