<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\AgentImage;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'added_by',
        'status',
        'price',
        'person'
    ];

    public function Keys(){
        return $this->hasOne(PackageKey::class,'package');
    }
    public function Images(){
        return $this->hasMany(AgentImage::class,'type_id')->where('category_id',1);
    }
    
    public function package_activities() :HasMany
    {
        return $this->hasMany(AgentPackageActivity::class,'package_id');
    } 
    
}
