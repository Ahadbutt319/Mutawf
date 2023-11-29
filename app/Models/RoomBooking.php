<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomBooking extends Model
{
    use HasFactory;
    protected $fillable = ['room_category_id', 'room_hotel_id', 'sku', 'price_per_night', 'floor_number', 'bed_type', 'is_available', 'capacity', 'added_by', 'room_number','name'];

    public function roomImages()
    {
        return $this->hasMany(RoomImage::class, 'room_id');
    }
}
