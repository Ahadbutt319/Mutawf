<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageBooking extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'package_id',
        'payment_status',
        'date',
        'payment_id',
        'to',
        'from',
        'quantity',
        'total_amount',
    ];
    public function packages()
    {
        return $this->belongsToMany(AgentPackage::class, 'bookpackages', 'package_id', 'booking_id');
    } 
    public function getdata()
    {
           $data['id'] = $this->id;
           $data['User'] = User::where('id',$this->user_id )->value('name');
           $data['package'] = UmrahPackage::where('id',$this->package_id)->get();
           $data['persons'] = $this->package_persons;
           $data['visas'] = $this->visas;
           $data['createdAt'] = date(config("app.date_format"), strtotime($this->created_at));
            return $data;
    }
    public function visas()
    {
        return $this->hasMany(Visa::class,'booking_id');
    }
    public function package_persons()
    {
        return $this->hasMany(PackageBookedPerson::class,'booking_id');
    }

}
