<?php

namespace App\Http\Requests\Region;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AddRegionRequest extends FormRequest
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
            'territory_id' => ['nullable', 'uuid', Rule::exists('territories', 'id')],
        ];
    }
}
