<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEventSubmissionRequest extends FormRequest
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
            'title' => ['required'],
            'description' => [],
            'start' => [],
            'end' => [],
            'free' => [],
            'virtual' => [],
            'cost_details' => [],
            'attendance_information' => [],
            'accessibility_information' => [],
            'address_line_1' => [],
            'address_line_2' => [],
            'town' => [],
            'postcode' => [],
            'lat' => [],
            'lng' => [],
            'content' => [],
            'booking_link' => [],
            'cta' => [],
            'categories' => [],
            'organsisers' => [],
            'image' => []
        ];
    }
}
