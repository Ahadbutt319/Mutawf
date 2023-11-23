<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransportCar extends Model
{
    protected $table = 'transport_cars';
    use HasFactory;
    protected $fillable = [
        'image','type','name' ,'bags','transport_id'
    ];
}
