<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HasFactory;

    // translatable fields
    public $translatable = ['question', 'answer'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'created_by',
        'question',
        'answer',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'question' => 'json',
        'answer' => 'json'
    ];
    public function getdata()
    {
        $data['id'] = $this->id;
        $data['question'] = $this->question;
        $data['answer'] = $this->answer;
        $data['is_active'] = $this->is_active;
        $data['created_by'] = $this->created_by;
        $data["createdAt"] = date(config("app.date_format"), strtotime($this->created_at));
        return $data;
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
