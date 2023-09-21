<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\User;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [

            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'lat' => $this->lat,
            'lng' => $this->lng,
            'country' => $this->country->name,
            'nationality' => $this->nationality->name,
            'role_id' => $this->role->role,

        ];
    }
}
