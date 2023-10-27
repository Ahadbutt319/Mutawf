<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgentHotel extends Model
{
    use HasFactory;
   protected $fillable = [
    'hotel_name',
    'private_transport',
    'location',
    'details',
    'added_by',
   ];
   public function getImages(){
    return $this->hasMany(AgentImage::class,'type_id')->where('category_id',2);
}
}
