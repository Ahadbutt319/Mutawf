<?php

namespace App\Http\Resources;

use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'lat' => $this->lat,
            'lng' => $this->lng,
            'country_id' => Country::where('id', $this->country_id)->value('name'),
            'nationality_country_id' => $this->nationality_country_id,
            'created_at' => date(config("app.date_format"), strtotime($this->created_at)),
        ];
    }
}
