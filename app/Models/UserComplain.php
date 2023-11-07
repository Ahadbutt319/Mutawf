<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserComplain extends Model
{
    protected $table = 'complains_users';
    use HasFactory;

    protected $fillable = [

        'user_id',
        'complain_id'

    ];



}
