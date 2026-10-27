<?php

namespace App\Http\Requests\DepartmentUserTypePermission;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDepartmentUserTypePermissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Routes enforce permissions.
    }

    public function rules(): array
    {
        return [
            'department_id' => ['required', 'uuid', Rule::exists('departments', 'id')],
            'user_type_id' => ['required', 'uuid', Rule::exists('user_types', 'id')],
            'permission_id' => ['required', 'uuid', Rule::exists('permissions', 'id')],
            // Duplicate combinations are rejected by the database's composite UNIQUE index.
        ];
    }
}
