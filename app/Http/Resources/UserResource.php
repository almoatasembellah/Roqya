<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return array_filter([
            'id' => $this['id'],
            'name' => $this['name'],
            'email' => $this['email'],
            'gender' => $this['gender'],
        ]);
    }
}
