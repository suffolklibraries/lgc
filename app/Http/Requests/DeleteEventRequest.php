<?php

namespace App\Http\Requests;

use Statamic\Facades\User;
use Statamic\Facades\Entry;
use Illuminate\Foundation\Http\FormRequest;

class DeleteEventRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $entryId = $this->route('entryId');
        $entry = Entry::find($entryId);
        $userId = User::current()?->id;

        return $entry->created_by?->id === $userId;
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
