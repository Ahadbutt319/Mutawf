<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\AgentImages;


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

    public function getImages(){
        return $this->hasMany(AgentImages::class,'Package_id')->where('category_id',2);
    }
}
