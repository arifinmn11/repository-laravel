<?php

namespace App\Repositories;

use App\Models\Menu;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class MenuRepository extends BaseRepository implements MenuIRepository
{
    protected array $searchableFields = [];

    public function __construct(Menu $model)
    {
        parent::__construct($model);
    }

    /**
     * Get paginated Menu data.
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
        $query = Menu::where('is_active', $isActive)
            ->search($search)
            ->when($limit, function ($query) use ($limit) {
                return $query->limit($limit);
            })->get();

        return $query;
    }

    public function getListDetailByUser(User $user): Collection
    {
        $permissions = $user->getAllPermissions();

        $allkeyMenus = collect();
        $menuAccess = $permissions->map(function ($p) use (&$allkeyMenus) {
            $explodeMenu = explode(':', $p->name);
            if ($explodeMenu[0] === 'menu') {
                $allkeyMenus->push($explodeMenu[1]);

                return str_replace('menu:', '', $p->name);
            }
        })->whereNotNull();

        $filterAccess = $permissions->map(function ($p) {
            if (explode(':', $p->name)[0] === 'filter') {
                $menuFilter = explode(':', $p->name);
                $filter = explode('-', $menuFilter[1]);
                return $filter;
            }
        })->whereNotNull();

        $viewAccess = $permissions->map(function ($p) {
            if (explode(':', $p->name)[0] === 'view') {
                $menuView = explode(':', $p->name);
                $filter = explode('-', $menuView[1]);
                return $filter;
            }
        })->whereNotNull();

        $query = Menu::whereIn('code', $allkeyMenus->toArray())->get();

        $result = self::buildMenuTree($query, $filterAccess->toArray(), $viewAccess->toArray());

        return new Collection($result);
    }

    private function buildMenuTree($menus, $filterAccess = [], $viewAccess = [], $parentId = null)
    {
        $tree = [];


        foreach ($menus as $menu) {

            if ($menu['parent_id'] == $parentId) {
                $matchedFilters = [];
                $matchedViews = [];

                if (is_array($filterAccess)) {
                    foreach ($filterAccess as $filter) {
                        if (is_array($filter) && count($filter) > 1 && $filter[0] === $menu['code']) {
                            $matchedFilters[] = $filter[1];
                        }
                    }
                }

                if (is_array($viewAccess)) {
                    foreach ($viewAccess as $view) {
                        if (is_array($view) && count($view) > 1 && $view[0] === $menu['code']) {
                            $matchedViews[] = $view[1];
                        }
                    }
                }


                // Recursively find children
                $children = self::buildMenuTree($menus, $filterAccess, $viewAccess, $menu['id']);
                $menu['menus'] = $children; // Add children to current node
                $menu['filters'] = $matchedFilters;
                $menu['views'] = $matchedViews;

                $tree[] = $menu;
            }
        }

        return $tree;
    }

    public function create(array $data): Menu
    {
        try {
            $result = Menu::create($data);

            return $result;
        } catch (\Throwable $th) {
            throw new \Exception('Create failed');
        }
    }

    public function updateById(array $data, $id): Menu
    {
        try {
            $query = Menu::findOrFail($id);
            $query->update($data);
            return $query;
        } catch (\Throwable $th) {
            throw new \Exception('Update failed');
        }
    }

    public function deleteById($id): bool
    {
        try {
            $query = Menu::findOrFail($id);
            $query->delete();
            return true;
        } catch (\Throwable $th) {
            throw new \Exception('Delete failed');
        }
    }

    public function findById($id): Menu
    {
        try {
            return Menu::findOrFail($id);
        } catch (\Throwable $th) {
            throw new \Exception('Menu not found');
        }
    }
}
