<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgentImages extends Model
{
    use HasFactory;
    protected $fillable = ['image','package_id'];
}
