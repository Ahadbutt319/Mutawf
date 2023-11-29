<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transport extends Model
{
    use HasFactory;
    protected $fillable = [
        'company_id','type','capacity' ,'price','user_id','is_active','lat','lng','details'
    ];
    public function cars()
    {
        return $this->hasMany(TransportCar::class,'transport_id');
    }

    public function getdata()
    {
        $data['id'] = $this->id;
        $data['company_name'] = Company::where('id',$this->company_id)->first('name');
        $data['type'] = $this->type;
        $data['lat'] = $this->lat;
        $data['lng'] = $this->lng;
        $data['price'] = $this->price;
        $data['details'] = $this->details;
        $data['is_active'] = $this->is_active;
        $data['capacity'] = $this->capacity;
        $data['cars'] =  $this->cars;
        return $data;
    }
}
