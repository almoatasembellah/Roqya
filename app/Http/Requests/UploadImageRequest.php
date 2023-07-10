<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadImageRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'profile_image' => ['required' , 'image']
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
