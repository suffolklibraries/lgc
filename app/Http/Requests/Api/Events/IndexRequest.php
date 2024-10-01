<?php

namespace App\Http\Requests\Api\Events;

use Illuminate\Foundation\Http\FormRequest;

class IndexRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $apiKey = '123456';
        return true;//$this->get('api_key') === $apiKey;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // 'api_key' => ['required', 'string'],
            'search' => ['nullable', 'string'],
            'status' => ['nullable', 'in:published,draft'],
            'category' => ['nullable', 'string'],
            'type' => ['nullable', 'string', 'in:virtual,free'],
            'start_date' => ['nullable', 'date:Y-m-d'],
        ];
    }
}
