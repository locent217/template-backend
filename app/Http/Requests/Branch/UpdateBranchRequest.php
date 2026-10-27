<?php

namespace App\Http\Requests\Branch;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBranchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Routes enforce permissions.
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:255'],
            'address' => ['sometimes', 'string', 'max:255'],
            'active' => ['sometimes', 'boolean'],
            'region_id' => ['nullable', 'uuid', Rule::exists('regions', 'id')],
        ];
    }
}
