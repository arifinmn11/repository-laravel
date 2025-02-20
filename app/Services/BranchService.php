<?php

namespace App\Services;

use App\Models\Branch;
use App\Repositories\BranchIRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class BranchService
{
    protected $branchRepository;

    public function __construct(BranchIRepository $branchRepository)
    {
        $this->branchRepository = $branchRepository;
    }

    public function getBranchById($id): Branch
    {
        return $this->branchRepository->findById($id);
    }

    public function createBranch(array $data): Branch
    {
        return $this->branchRepository->create($data);
    }

    public function updateBranchById(array $data, $id): Branch
    {
        return $this->branchRepository->updateById($data, $id);
    }

    public function deleteBranchById($id): bool
    {
        return $this->branchRepository->deleteById($id);
    }

    public function listBranch($limit = null, $search = null, $isActive = true): Collection
    {
        return $this->branchRepository->getList($limit, $search, $isActive);
    }

    public function paginationBranch($limit = 10, $search = null, $page = 1): LengthAwarePaginator
    {
        return $this->branchRepository->getPagination($limit, $search, $page);
    }
}
