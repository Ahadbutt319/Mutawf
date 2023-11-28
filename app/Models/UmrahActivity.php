<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UmrahActivity extends Model
{

    protected $fillable=['locations','title','vehicle','price'];
    protected $casts = [
        'locations' => 'json',
    ];
    use HasFactory;
}
