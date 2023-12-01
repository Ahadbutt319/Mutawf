<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroundBooking extends Model
{
    protected $table = 'ground_bookings';
    use HasFactory;
    protected $fillable = [
        'user_id', 'ground_id' ,'pu_date', 'name', 'email','payment_id','persons','details','total_price'
    ];
}

