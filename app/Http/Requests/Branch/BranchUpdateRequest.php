<?php

namespace App\Http\Requests\Branch;

use App\Http\Requests\BaseFormRequest;

class BranchUpdateRequest extends BaseFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'code' => ['required|string|max:100', 'unique:branch,code,' . $this->id,],
            'address' => 'required|string|max:255',
            'phone' => 'nullable|string|max:100',
            'email' => 'nullable|string|max:100',
            'is_active' => 'nullable|string|max:100',
        ];
    }
}
