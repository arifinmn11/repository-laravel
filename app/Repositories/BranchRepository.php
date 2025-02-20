<?php

namespace App\Repositories;

use App\Models\Branch;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class BranchRepository implements BranchIRepository
{
    public function getPagination($limit = 10, $search = null, $page = 1, $filters = [], $sortBy = 'id|asc'): LengthAwarePaginator
    {

        $query = Branch::when($search, function ($query, $search) {
            return $query->where(function ($query) use ($search) {
                foreach (Branch::searchAbleFields() as $field) {
                    $query->orWhere($field, 'like', "%$search%");
                }
            });
        })
        ->paginate($limit, ['*'], 'page', $page);

        return $query;
    }
    public function getList($limit = null, $search = null): Collection
    {
        // $query = Branch::when($search, function ($query, $search) {
        //     return $query->where('name', 'like', "%$search%");
        // })->limit($limit)->get();

        $query = Cache::remember('branches', 10, function () {
            return Branch::all();
            // return Branch::when($search, function ($query, $search) {
            //     return $query->where(function ($query) use ($search) {
            //         foreach (Branch::searchAbleFields() as $field) {
            //             $query->orWhere($field, 'like', "%$search%");
            //         }
            //     });
        });

        // return $query->filter(function ($branch) use ($search) {
        //     foreach ($branch->toArray() as $value) {
        //         if (stripos($value, $search) !== false) {
        //             return true;
        //         }
        //     }
        //     return false;
        // })->values();  // Reset array keys



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
