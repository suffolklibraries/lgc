<?php

namespace App\Http\Requests;

use Statamic\Facades\User;
use Statamic\Facades\Entry;
use Illuminate\Foundation\Http\FormRequest;

class UpdateEventRequest extends FormRequest
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
            'title' => ['required'],
            'description' => [],
            'start' => ['required', 'date', 'after_or_equal:tomorrow'],
            'end' => ['required', 'date', 'after:start'],
            'free' => [],
            'virtual' => [],
            'cost_details' => ['max:255'],
            'attendance_information' => [],
            'accessibility_information' => [],
            'address_line_1' => ['required'],
            'address_line_2' => [],
            'town' => ['required'],
            'postcode' => ['required'],
            'lat' => [],
            'lng' => [],
            'content' => [],
            'booking_link' => ['url:https,http'],
            'cta' => ['max:1000'],
            'categories' => ['required', 'array', 'min:1'],
            'organisers' => ['required', 'array', 'min:1'],
            'image' => ['sometimes', 'image', 'max:5120'],
        ];
    }
}
