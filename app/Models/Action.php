<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Action extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
    ];
    public function getdata()
    {
           $data['id'] = $this->id;
           $data['name'] = $this->name;
           $data['description'] = $this->description;
           $data['createdAt'] = date(config("app.date_format"), strtotime($this->created_at));
            return $data;
    }
    public function complains()
    {
        return $this->belongsToMany(Complain::class, 'action_complain_user', 'action_id', 'complain_id');
    }
}
