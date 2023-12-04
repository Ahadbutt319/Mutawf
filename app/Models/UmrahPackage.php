<?php

namespace App\Models;

use App\Models\PackageBooking;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class UmrahPackage extends Model
{
    use HasFactory;
    protected $fillable = [ 'sku', 'name', 'details', 'managed_by', 'duration', 'person', 'type', 'first_start', 'first_end', 'second_start', 'second_end', 'tags', 'transport',  'user_id', 'price','package_status'];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'tags' => 'json',
    ];
    public function package_activities() :HasMany
    {
        return $this->hasMany(AgentPackageActivity::class,'package_id');
    } 
    public function packagebookings()
    {
        return $this->belongsToMany(PackageBooking::class, 'bookpackages', 'package_id', 'booking_id');
    } 
    public function hotels(): BelongsToMany
    {
        return $this->belongsToMany(AgentHotel::class, 'hotel_package', 'package_id', 'hotel_id');
    }
    public function getdata()
    {
           $data['id'] = $this->id;
           $data['sku'] = $this->sku;
           $data['name'] = $this->name;
           $data['details'] = $this->details;
           $data['package_type'] = $this->type;
           $data['managed_by'] = $this->managed_by;
           $data['duration'] = $this->duration;
           $data['person'] = $this->person;
           $data['first_trip_start'] = $this->first_start;
           $data['first_trip_end'] = $this->first_end;
           $data['second_trip_start'] = $this->second_start;
           $data['second_trip_end'] = $this->second_end;
           $data['tags'] = $this->tags;
           $data['price'] = $this->price;
           $data['createdAt'] = date(config("app.date_format"), strtotime($this->created_at));
           return $data;
    }
    public function getDetailRecord()
    {
           $data['id'] = $this->id;
           $data['sku'] = $this->sku;
           $data['name'] = $this->name;
           $data['details'] = $this->details;
           $data['package_type'] = $this->type;
           $data['managed_by'] = $this->managed_by;
           $data['duration'] = $this->duration;
           $data['person'] = $this->person;
           $data['first_trip_start'] = $this->first_start;
           $data['first_trip_end'] = $this->first_end;
           $data['second_trip_start'] = $this->second_start;
           $data['second_trip_end'] = $this->second_end;
           $data['tags'] = $this->tags;
           $data['transport'] = $this->transport;
           $data['price'] = $this->price;
           $data['package_status'] = $this->package_status;
           $array_madina = [];
           foreach ($this->hotels as $hotel) {
            if ($hotel->location == 'Madina') {
                $array_madina = $hotel;
            }
           $data['madina_hotel'] =   $array_madina;
           $array_makkah = [];
           foreach ($this->hotels as $hotel) {
            if ($hotel->location == 'Makkah') {
                $array_makkah = $hotel;
            }
            $data['makkah_hotel'] =   $array_makkah;
           }

           $data['createdAt'] = date(config("app.date_format"), strtotime($this->created_at));
           return $data;
    }
   
}
}