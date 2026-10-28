<?php
namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class FirstUseCredentialsRequest extends FormRequest
{
    public function authorize(): bool { return $this->user() !== null; }
    public function rules(): array
    {
        return [
            'current_password' => ['required', 'string'],
            'username' => ['required', 'string', 'max:255', Rule::unique('users', 'username')->ignore($this->user()->getKey())],
            'password' => ['required', 'confirmed', Password::min(12)],
        ];
    }
}
