<?php

namespace App\Http\Requests\Territory;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTerritoryRequest extends FormRequest
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
            'active' => ['sometimes', 'boolean'],
            'island_id' => ['nullable', 'uuid', Rule::exists('islands', 'id')],
        ];
    }
}
