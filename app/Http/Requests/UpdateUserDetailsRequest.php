<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserDetailsRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:225'],
            'email' => ['required', 'email', 'max:255'],
            'password' => ['sometimes', 'nullable', 'required_with:new_password,new_password_confirmation'],
            'new_password' => [
                'sometimes',
                'nullable',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised()
            ],
            'new_password_confirmation' => ['sometimes', 'nullable', 'required_with:new_password']
        ];
    }

    public function messages(): array
    {
        return [
            'password.letters' => 'The :attribute must contain at least one letter.',
            'password.mixed' => 'The :attribute must contain at least one uppercase and one lowercase letter.',
            'password.numbers' => 'The :attribute must contain at least one number.',
            'password.symbols' => 'The :attribute must contain at least one symbol.',
            'password.uncompromised' => 'The given :attribute has appeared in a data leak. Please choose a different :attribute.',
        ];
    }
}
