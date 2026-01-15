<?php

namespace App\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // or add your logic
    }

    public function rules(): array
    {
        return [
            'email' => 'required|email|unique:users,email',
            'firstName' => 'required|string',
            'lastName' => 'required|string',
            'password' => 'required|min:8',
        ];
    }

}
