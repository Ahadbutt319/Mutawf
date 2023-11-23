<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class TransportBooking extends Model
{


    protected $table = 'transportation_bookings';
    use HasFactory;
    // translatable fields
    public $translatable = ['description'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'passengers',
        'luggages',
        'details',
        'type',
        'pickup',
        'drop_off',
        'date',
        'time',
        'return_date',
        'return_time',
        'duration',
        'transport_id',
        'user_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function seat(): BelongsTo
    {
        return $this->belongsTo(Seat::class);
    }

    public function transportation(): BelongsTo
    {
        return $this->belongsTo(Transportation::class);
    }

    public function payments(): MorphMany
    {
        return $this->morphMany(Payment::class, 'paymentable');
    }
}
