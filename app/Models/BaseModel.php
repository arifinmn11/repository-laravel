<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Laravel\Scout\Searchable;

class BaseModel extends Model
{
    /**
     * The model being queried.
     *
     * @var Model
     */
    protected $model;

    /**
     * Get all column names of the model's table
     *
     * @return array
     */
    public static function getTableColumns()
    {
        return Schema::getColumnListing((new static)->getTable());
    }

    /**
     * Perform a search query on all columns
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $searchTerm
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch($query, $searchTerm)
    {
        return $query->where(function ($q) use ($searchTerm) {
            foreach (self::getTableColumns() as $column) {
                $q->orWhere($column, 'LIKE', "%{$searchTerm}%");
            }
        });
    }


    public function applyFilters(array $filters = [])
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

        return $this->model;
    }

    public function scopeSortOrderBy($query, $sortOrder = 'id|asc')
    {
        $sortBy = explode('|', $sortOrder);

        return $query->orderBy($sortBy[0], $sortBy[1]);
    }
}
