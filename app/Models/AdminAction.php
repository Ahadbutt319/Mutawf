<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminAction extends Model
{
    protected $table  = 'action_complain_user';
    use HasFactory;
    protected $fillable = [
        'user_id',
        'complain_id',
        'action_id',
    ];
   

}
