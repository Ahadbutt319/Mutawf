<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class bookpackage extends Model
{
    use HasFactory;
    protected $fillable = [
        'booking_id',
        'package_id',
    ];
}
