<?php

namespace App\Http\Requests\SavedAddress;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class DeleteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $addressId = $this->route('id');

        $user = Auth::user();

        $org = $user->linked_organisations;

        if(!$org) {
            return false;
        }

        $addresses = $org->get('addresses');

        $address = collect($addresses)->filter(function($address) use ($addressId) {
            return $address['id'] === $addressId;
        })->first();

        if(!$address) {
            return false;
        }

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
            //
        ];
    }
}
