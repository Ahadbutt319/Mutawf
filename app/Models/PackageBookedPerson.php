<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageBookedPerson extends Model
{
    protected $table= 'package_booking_persons_details';
    use HasFactory;
    protected $fillable = [
        'name',
        'email',
        'phone',
        'booking_id'
    ];


}
