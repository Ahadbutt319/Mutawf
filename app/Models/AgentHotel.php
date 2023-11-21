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
        'checkin_time',
        'checkout_time',
        'is_active',
        'details',
        'luxuries',
        'added_by',
    ];
    public function getdata()
    {

        $data['hotel_name'] = $this->hotel_name;
        $data['private_transport'] = $this->private_transport;
        $data['location'] = $this->location;
        $data['details'] = $this->details;
        $data['luxuries'] = $this->luxuries;
        $data['id'] = $this->id;
        $data['checkin_time'] =  $this->checkin_time;
        $data['checkout_time'] =  $this->checkout_time;
        $data['hotel_images'] =  $this->hotel_images;
        // $data['rooms'] =  $this->rooms;
        return $data;
    }
    public function umrahPackages()
    {
        return $this->belongsToMany(UmrahPackage::class, 'hotel_package', 'package_id', 'hotel_id');
    }
    public function rooms()
    {
        return $this->hasMany(RoomBooking::class, 'room_hotel_id');
    }
    public function hotel_images()
    {
        return $this->hasMany(HotelImage::class, 'hotel_id');
    }
    public function roomImages()
    {
        return $this->hasMany(RoomImage::class, 'room_id');
    }
    public function bookings()
    {
        return $this->morphMany(Booking::class, 'bookable');
    }
}
