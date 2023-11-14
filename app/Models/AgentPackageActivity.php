<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgentPackageActivity extends Model
{
    protected $table = 'agent_package_activities';
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'image',
        'user_id',
        'package_id',
    ];

   
}
