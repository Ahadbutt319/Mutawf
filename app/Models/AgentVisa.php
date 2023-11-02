<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgentVisa extends Model
{
    use HasFactory;

    protected $fillable=
    [
        'added_by',
        'visa',
        'duration',
        'visa_to',
        'immigration' ,
        'manage_by',
        'validity'
   ];
}
