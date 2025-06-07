<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'sometimes|string|exists:roles,name',
        ];
    }

    public function messages()
    {
        return [
            'role.exists' => __('The selected role is invalid.'),
        ];
    }
}
