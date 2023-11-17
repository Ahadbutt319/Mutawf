<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelPackage extends Model
{
    protected $table = 'hotel_package';
    use HasFactory;
    protected $fillable  = [
        'hotel_id','package_id'
    ];
}
