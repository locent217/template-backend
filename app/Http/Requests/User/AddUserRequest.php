<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\UserType;

class AddUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:45'],
            'middle_name' => ['nullable', 'string', 'max:45'],
            'last_name' => ['required', 'string', 'max:45'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            'username' => ['required', 'string', 'max:255', Rule::unique('users', 'username')],
            'password' => ['required', 'string', 'min:12', 'confirmed'],
            'department_id' => [Rule::requiredIf(fn () => !$this->isClient()), 'nullable', 'uuid', Rule::exists('departments', 'id')],
            'user_type_id' => ['required', 'uuid', Rule::exists('user_types', 'id')],
            'branch_id' => ['nullable', 'uuid', Rule::exists('branches', 'id')],
            'region_id' => ['nullable', 'uuid', Rule::exists('regions', 'id')],
            'territory_id' => ['nullable', 'uuid', Rule::exists('territories', 'id')],
            'island_id' => ['nullable', 'uuid', Rule::exists('islands', 'id')],
            'active' => ['sometimes', 'boolean'],
            'first_use' => ['sometimes', 'boolean'],
        ];
    }
    private function isClient(): bool
    {
        return UserType::query()
            ->whereKey($this->input('user_type_id'))
            ->where('code', 'CLIENT')
            ->exists();
    }

}
