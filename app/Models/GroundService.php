<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroundService extends Model
{
    use HasFactory;

    protected $fillable=[
        'added_by',
        'hotels',
        'guider_name',
        'tour_location',
        'services'
];

}
