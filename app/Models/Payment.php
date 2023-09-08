<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Payment extends Model
{
    use HasFactory;

        /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'method',
        'amount',
        'transaction_fee',
        'status',
        'gateway',
        'description',
        'reference',
        'type',
        'paymentable_id',
        'paymentable_type'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'paymentable_id',
        'paymentable_type',
    ];

    public function paymentable(): MorphTo
    {
        return $this->morphTo();
    }
}
