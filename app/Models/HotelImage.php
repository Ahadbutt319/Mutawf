<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelImage extends Model
{
    protected $table= 'hotel_images';
    use HasFactory;
    protected $fillable = [
        'hotel_id',
        'image',
        'image_type'
    ];
}
