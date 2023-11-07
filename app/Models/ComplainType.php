<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ComplainType extends Model
{
    use HasFactory;
     protected $fillable =  [
        'name',
        'user_id'
     ];
     public function getdata()
     {
            $data['id'] = $this->id;
            $data['name'] = $this->name;
            $data['created_by'] = User::where('id',$this->user_id )->value('name');
            $data['createdAt'] = date(config("app.date_format"), strtotime($this->created_at));
         return $data;
     }
    public function translations():BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}
