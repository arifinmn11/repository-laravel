<?php

namespace App\Services;

use App\Models\Menu;
use App\Models\User;
use App\Repositories\MenuIRepository;
use App\Repositories\UserIRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class MenuService
{
    protected $repository;
    protected $userRepository;

    public function __construct(
        MenuIRepository $repository,
        UserIRepository $userRepository
    ) {
        $this->repository = $repository;
        $this->userRepository = $userRepository;
    }

    public function getMenuById($id): Menu
    {
        return $this->repository->findById($id);
    }

    public function createMenu(array $data): Menu
    {
        return $this->repository->create($data);
    }

    public function updateMenuById(array $data, $id): Menu
    {
        return $this->repository->updateById($data, $id);
    }

    public function deleteMenuById($id): bool
    {
        return $this->repository->deleteById($id);
    }

    public function listMenu($limit = null, $search = null, $isActive = true): Collection
    {
        return $this->repository->getList($limit, $search, $isActive);
    }

    public function listMenuDetailByUser(User $user): Collection
    {
        return $this->repository->getListDetailByUser($user);
    }

    public function getPagination(int $limit = 10, ?string $search = null, string $sortBy = 'id|asc', $filters = [], $customFilters): LengthAwarePaginator
    {
        return $this->repository->getPagination($limit, $search, $sortBy, $filters, $customFilters);
    }
}
