<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required'],

            'email' => ['required' , 'email' , Rule::unique('users')->ignore($this->user()->id)],

            'profile_image' => ['image|mimes:jpeg,png,jpg|max:2048'],

            'phone' => ['required'],
        ];
    }
}
