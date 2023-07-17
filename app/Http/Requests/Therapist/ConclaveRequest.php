<?php

namespace App\Http\Requests\Therapist;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class ConclaveRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->status == User::THERAPIST;
    }

    public function rules(): array
    {
        return [
            'name' => ['required'],
            'image' => ['nullable','image','mimes:jpeg,jpg,png'],
            'time' => ['required','date_format:H:i'],
            'duration' => ['required','integer', 'min:1'],
            'date' => ['required','date_format:Y-m-d'],
            'price' => ['required','numeric', 'min:0'],
            'notes' => ['nullable','string']
        ];
    }
}
