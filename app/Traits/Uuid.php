<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait Uuid
{
    protected static function boot(): void
    {
        parent::boot();
        static::creating(function ($model) {
            $model->uuid = (string)Str::uuid();
        });
    }

}
