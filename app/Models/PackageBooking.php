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
    ];
    public function packages()
    {
        return $this->belongsToMany(AgentPackage::class, 'bookpackages', 'package_id', 'booking_id');
    } 
    public function getdata()
    {
           $data['id'] = $this->id;
           $data['User'] = User::where('id',$this->user_id )->value('name');
           $data['package'] = AgentPackage::where('id',$this->package_id)->get();
           $data['createdAt'] = date(config("app.date_format"), strtotime($this->created_at));
            return $data;
    }

}
