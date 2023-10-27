<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageKey extends Model
{
    use HasFactory;
    protected $fillable=['visa','travel','hotel','package'];
}
