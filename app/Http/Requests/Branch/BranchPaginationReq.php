<?php

namespace App\Http\Requests\Branch;

use App\Http\Requests\BasePaginationReq;
use Illuminate\Foundation\Http\FormRequest;

class BranchPaginationReq extends BasePaginationReq
{
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'name' => 'string',
            'address' => 'string',
            'phone' => 'string',
            'email' => 'string',
            'is_active' => 'boolean',
        ]);
    }
}
