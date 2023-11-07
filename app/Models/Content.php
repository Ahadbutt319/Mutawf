<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Content extends Model
{
    use HasFactory;
    protected $fillable = [
      'content',
      'user_id',
      'content_id',  
    ];
    public function getdata()
    {
        $data['content'] = $this->content;
        $data['content_id'] = $this->content_id;
        $data['content_type_name'] = ContentType::where('id',$this->content_id )->value('name');
        $data['createdAt'] = date(config("app.date_format"), strtotime($this->created_at));
        return $data;
    }
   
}
