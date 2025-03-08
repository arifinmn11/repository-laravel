<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BasePaginationReq extends FormRequest
{

    public function rules(): array
    {
        return [
            'limit' => 'integer|min:1|max:100',
            'search' => 'nullable|string',
            'page' => 'integer|min:1',
            'sort' => 'string',
            'order' => 'string|in:asc,desc',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    public function validatedData(): array
    {
        return array_merge($this->validator->validated(), [
            'limit' => $this->input('limit', 10),
            'search' => $this->input('search', null),
            'page' => $this->input('page', 1),
            'sort' => $this->input('sort', 'id'),
            'order' => $this->input('order', 'desc'),
        ]);
    }
}
