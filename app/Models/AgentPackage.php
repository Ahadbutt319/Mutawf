<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgentPackage extends Model
{
    use HasFactory;
    protected $fillable = [
        'Package_Name',
        'Duration',
        'Visa',
        'Details',
        'Additional_Notes',
        'Travel',
        'Managed_by',
        'Added_by'
    ];
}
