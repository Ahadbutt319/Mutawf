<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;
    protected $fillable = [
        'checkin_time',
        'checkout_time',
        'name',
        'user_id',
        'payment_id',
        'email',
    ];

    // Define the polymorphic relationship
    public function bookable()
    {
        return $this->morphTo();
    }
}
