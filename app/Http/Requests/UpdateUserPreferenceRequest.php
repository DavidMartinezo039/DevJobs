<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserPreferenceRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'salary' => 'nullable|exists:salaries,id',
            'category' => 'nullable|exists:categories,id',
            'company' => 'nullable|string|max:255',
            'keyword' => 'nullable|string|max:255',
        ];
    }
}
