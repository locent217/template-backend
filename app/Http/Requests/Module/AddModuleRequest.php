<?php

namespace App\Http\Requests\Module;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AddModuleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Routes enforce permissions.
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:255', Rule::unique('modules', 'code')],
            'description' => ['sometimes', 'string', 'max:255'],
            'category' => ['sometimes', Rule::enum(\App\Enums\ModuleCategory::class)],
            'active' => ['sometimes', 'boolean'],
        ];
    }
}
