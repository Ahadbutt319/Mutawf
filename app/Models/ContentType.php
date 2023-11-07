<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ContentType extends Model
{
    use HasFactory;
    protected $fillable = [
        'name'
    ];
    public function getdata()
    {
        $data['name'] = $this->name;
        $data['id'] = $this->id;
        return $data;
    }
    public function content(): HasMany
    {
        return $this->hasMany(Content::class,'content_id');
    }

}
