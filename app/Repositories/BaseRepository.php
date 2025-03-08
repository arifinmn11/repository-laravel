<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

abstract class BaseRepository
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Apply search filters dynamically.
     */
    public function applySearchKeyword(?string $search, array $searchableFields = []): void
    {
        if (!empty($search) && !empty($searchableFields)) {
            $this->model = $this->model->where(function ($query) use ($search, $searchableFields) {
                foreach ($searchableFields as $field) {
                    $query->orWhereLike($field, "%{$search}%");
                }
            });
        }
    }

    /**
     * Apply sorting dynamically.
     */
    public function applySortBy(string $sort = 'id|asc'): void
    {
        [$column, $direction] = explode('|', $sort) + ['id', 'asc'];

        try {

            if (!empty($column)) {
                $this->model = $this->model->orderBy($column, $direction);
            }
        } catch (\Throwable $th) {
            $this->model = $this->model->orderBy('id', 'asc');
        }
    }

    public function applyFilters(array $filters): void
    {
        $this->model = $this->model->when(count($filters), function ($query) use ($filters) {
            foreach ($filters as $filter_column => $filter_value) {
                if (is_numeric($filter_value) || $filter_value) { // is_numeric agar angka 0 masuk, dan or filter_value agar string tanggal masuk
                    if (strpos($filter_column, ".")) {
                        $filter_column_array = explode(".", $filter_column);
                        if (count($filter_column_array) >= 2) {
                            // ambil last param sebagai where param, sisanya sebagai where has
                            // dengan skema ini sudah support multiple deep relation, karena di whereHas di isikan dot notation dari relasi
                            $where_param = array_pop($filter_column_array);
                            $where_has_param = implode(".", $filter_column_array);
                            $query->whereHas($where_has_param, function ($query) use ($where_param, $filter_value) {
                                $query->where($where_param, $filter_value);
                            });
                        }
                    } else {
                        $query->where($filter_column, $filter_value);
                    }
                }
            }
        });
    }

    /**
     * Get paginated results.
     */
    public function getPaginatedResults(int $limit = 10): LengthAwarePaginator
    {
        return $this->model->paginate($limit);
    }
}
