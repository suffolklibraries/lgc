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
            'image' => ['sometimes', 'mimetypes:image/jpeg,image/png,image/gif,image/bmp,image/tiff,image/webp', 'max:5120'],
            'alternative_text' => [],
            'name' => ['required'],
            'email' => ['required', 'email'],
        ];
    }

    public function messages(): array
    {
        return [
            'image.max' => "Image must be 5MB or smaller"
        ];
    }
}
