<?php

namespace App\Http\Requests\User;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AddUserRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'last_name' => ['required', 'string', 'max:45'],
            'first_name' => ['required', 'string', 'max:45'],
            'middle_name' => ['required', 'string', 'max:45'],
            'email' => ['required','string','unique:users'],

            'username' => [
                'required',
                'string',
                'max:100',
                Rule::unique('users', 'username'),
            ],

            'password' => [
                'required',
                'string',
                'confirmed',
            ],

            'department_id' => [
                'nullable',
                'uuid',
                Rule::exists('departments', 'id'),
            ],

            'user_type_id' => [
                'required',
                'uuid',
                Rule::exists('user_types', 'id'),
            ],
        ];
    }
}
