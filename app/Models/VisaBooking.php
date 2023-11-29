<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisaBooking extends Model
{
    use HasFactory;
    protected $fillable = [
        'visas_id',
        'user_id',
        'status'
    ];





    
}
