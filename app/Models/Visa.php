<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visa extends Model
{
   protected $table = 'visas';
    use HasFactory;
    protected $fillable = [
        'passport_number',
        'nationality',
        'id_number',
        'visa_number',
        'passport_image',
        'photo',
        'booking_id',
        
    ];
}
