<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomImage extends Model
{
    protected $table= 'rooms_images';
    use HasFactory;
    protected $fillable = [
        'room_id',
        'image',
        'room_image_type'
    ];
}
