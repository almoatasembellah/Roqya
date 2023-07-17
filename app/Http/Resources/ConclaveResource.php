<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ConclaveResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $imagePath = $this['image'] ? asset("storage/{$this['image']}") : null;

        return [
            'id' => $this->id,
            'name' => $this->name,
            'time' => $this->time,
            'date' => $this->date,
            'price' => $this->price,
            'image' => $imagePath,
            'notes' => $this->notes,
            'user_id' => $this->user_id
        ];
    }
}
