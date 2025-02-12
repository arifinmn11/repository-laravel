<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Branch extends Model
{
    use Uuid, HasFactory;

    protected $table = 'branch';

    protected $fillable = [
        'id',
        'uuid',
        'name',
        'code',
        'address',
        'phone',
        'email',
        'is_active',
        'user_created',
        'user_updated',
    ];

    protected $hidden = [
        'id',
    ];

    public static function searchAbleFields(): array
    {
        return ['name', 'code', 'address', 'phone', 'email'];
    }
}
