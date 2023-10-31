<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgentTransportation extends Model
{
    use HasFactory;

    protected $fillable=['added_by','type','availability','location','pickup','no_of_persons','manage_by','tags'];
}
