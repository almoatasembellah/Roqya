<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        $profileImageUrl = $this['profile_image'] === 'default-image-profile.png' ? asset('images/default-image-profile.png') : asset("storage/{$this['profile_image']}");

        $data = [
            'id' => $this['id'],
            'name' => $this['name'],
            'email' => $this['email'],
            'status' => $this['status'],
            'profile_image' => $profileImageUrl,
            'dob' => $this['dob'],
            'phone' => $this['phone'],
        ];

        if ($this['status'] === User::THERAPIST) {
            $data['overall_rating'] = $this['overall_rating'];
            $data['rate'] = $this['rate'];
        }

        return array_filter($data);
    }
}
