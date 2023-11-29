<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroundServiceActivity extends Model
{
    use HasFactory;
    protected $fillable = [
        'ground_Service_id',
        'visit_location',
        'description',
        'image',
    ];


}
