<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\AgentImage;


class AgentPackage extends Model
{
    use HasFactory;
    protected $fillable = [
        'package_name',
        'duration',
        'visa',
        'details',
        'additional_notes',
        'travel',
        'managed_by',
        'hotel',
        'added_by'
    ];

    public function Keys(){
        return $this->hasOne(PackageKey::class,'package');
    }
    public function Images(){
        return $this->hasMany(AgentImage::class,'type_id')->where('category_id',1);
    }
}
