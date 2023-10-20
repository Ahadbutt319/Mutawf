<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgentHotel extends Model
{
    use HasFactory;
   protected $fillable = [
    'hotel_name',
    'private_transport',
    'Location',
    'Details',
    'added_by',
   ];
}
