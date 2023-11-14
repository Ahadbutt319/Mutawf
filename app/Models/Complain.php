<?php

namespace App\Models;

use App\Models\ComplainType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Complain extends Model
{
    use HasFactory;

    const complaint_status_pending= 3;

    protected $fillable =  [
        'user_id',
        'compain_type_id',
        'name',
        'email',
        'description',
        'complaint_status_id'
    ];

    public function getdata()
    {
           $data['id'] = $this->id;
           $data['name'] = $this->name;
           $data['email'] = $this->email;
           $data['description'] = $this->description;
           $data['complaint_status_id'] = ComplaintStatus::where('id',$this->complaint_status_id )->value('name');
           $data['complain_type_id'] = ComplainType::where('id',$this->compain_type_id )->value('name'); 
           $data['user_id'] = User::where('id',$this->user_id )->value('name');
           $data['createdAt'] = date(config("app.date_format"), strtotime($this->created_at));
            return $data;
    }





    public function user() {
        return $this->belongsTo(User::class);
    }
    public function ComplainType() {
        return $this->belongsTo(ComplainType::class);
    }
    public function status() {
        return $this->belongsTo(ComplaintStatus::class);
    }
    public function admins() {
        return $this->belongsToMany(User::class, 'complains_users');
    }
    // Define the relationship with actions
    public function actions()
    {
        return $this->belongsToMany(Action::class, 'action_complain_user', 'complain_id', 'action_id');
    }

}
