<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEventRequest extends FormRequest
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
            'start' => ['required', 'date', 'after_or_equal:tomorrow'],
            'end' => ['required', 'date', 'after:start'],
            'free' => [],
            'virtual' => [],
            'cost_details' => ['max:255'],
            'attendance_information' => [],
            'accessibility_information' => [],
            'address_line_1' => ['required_without:virtual'],
            'address_line_2' => [],
            'town' => ['required_without:virtual'],
            'postcode' => ['required_without:virtual'],
            'lat' => [],
            'lng' => [],
            'content' => [],
            'booking_link' => ['sometimes', 'nullable', 'url:https,http'],
            'cta' => ['max:1000'],
            'categories' => ['required', 'array', 'min:1'],
            'organisers' => ['required', 'array', 'min:1'],
            'image' => ['sometimes', 'image', 'max:5120'],
        ];
    }

    public function messages(): array
    {
        return [
            'image.max' => "Image must be 5MB or smaller",
            'address_line_1.required_without' => "The Address line 1 field is required when the event is not virtual",
            'town.required_without' => "The town field is required when the event is not virtual",
            'postcode.required_without' => "The post code field is required when the event is not virtual",
        ];
    }
}
