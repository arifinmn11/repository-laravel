<?php

namespace App\Http\Requests\Branch;

use App\Http\Requests\BaseFormRequest;

class BranchCreateRequest extends BaseFormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:100|unique:branch,code',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:100',
            'email' => 'required|string|max:100',
            'is_active' => 'nullable|string|max:100',
        ];
    }
}
