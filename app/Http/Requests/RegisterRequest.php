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

            'phone' => ['required', 'string', 'min:11'],

            'dob' => ['required', 'date', 'date_format:Y-m-d'],

            'gender' => ['required', 'in:0,1'],
        ];
    }
}
