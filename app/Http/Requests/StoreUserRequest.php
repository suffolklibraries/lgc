<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised()
            ],
            'password_confirmation' => ['required'],
            'organisation_name' => ['required', 'string', 'max:255'],
            'organisation_email' => ['required', 'email', 'string', 'max:255'],
            'organisation_tel' => [ 'max:255'],
            'organisation_website' => ['sometimes', 'url:http,https', 'max:255']
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
