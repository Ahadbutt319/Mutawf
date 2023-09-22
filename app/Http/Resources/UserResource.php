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

            'name' => $this->name ? $this->name : '',
            'email' => $this->email ? $this->email : '',
            'phone' => $this->phone ? $this->phone : '',
            'lat' => $this->lat ? $this->lat : '',
            'lng' => $this->lng ? $this->lng : '',
            'country' => $this->country->name ? $this->country->name : '',
            'nationality' => $this->nationality->name ? $this->nationality->name : '',
            'role_id' => $this->role->role ? $this->role->role : '',

        ];
    }
}
