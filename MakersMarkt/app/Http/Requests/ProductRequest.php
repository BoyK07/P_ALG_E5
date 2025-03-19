<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:1000'],
            'type' => ['required', 'string', 'max:255'],
            'material' => ['required', 'string', 'max:255'],
            'production_time' => ['required', 'integer', 'min:1'],
            'complexity' => ['required', 'string', 'max:255'],
            'durability' => ['required', 'string', 'max:255'],
            'unique_features' => ['nullable', 'string', 'max:1000'],
            'contains_external_links' => ['required', 'boolean'],
        ];
    }
}
