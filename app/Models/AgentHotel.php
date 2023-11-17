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
    'luxuries',
        'added_by',
   ];
   public function getImages(){
    $catId=ImageCategory::where('image_type','Hotel')->first();
    return $this->hasMany(AgentImage::class,'type_id')->where('category_id', $catId->id);
}
public function getRooms(){
    return $this->hasMany(RoomBooking::class,'room_hotel_id');




}
public function getRoomImages(){
    $catId=ImageCategory::where('image_type','Room')->first();
    return $this->hasMany(AgentImage::class,'type_id')->where('category_id', $catId->id);
}
public function getdata(){

$data['hotel_name']=$this->hotel_name;
$data['private_transport']=$this->private_transport;
$data['location']=$this->location;
$data['details']=$this->details;
$data['luxuries']=$this->luxuries;
$data['id']= $this->id;
$data['images'] =  $this->getImages;
$data['room_images'] =  $this->getRoomImages;
$array = [];
foreach($this->getRooms as $da)
{
    $array[] = $da->room_category_id;
}
$array_1 = [];
for($i= 0; $i < count($array); $i++)
{
    $array_1[] = RoomCategory::where('id',$array[$i] )->first();
}
$data['roomdata'] =$array_1;
return $data;
}
public function umrahPackages()
{
    return $this->belongsToMany(UmrahPackage::class, 'hotel_package', 'package_id', 'hotel_id');
} 
}
