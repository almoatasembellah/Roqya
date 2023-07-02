<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required'],

            'email' => ['required', 'email', Rule::unique('users', 'email')],

            'password' => ['required', 'min:8'],

            'gender' => ['required', 'in:0,1'],
        ];
    }
}
