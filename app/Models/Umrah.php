<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Umrah extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'service_provider_id',
        'company_id',
        'user_id',
        'group_id',
        'hotel_booking_id',
        'transport_booking_id',
        'type',
        'status',
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

    public function serviceProvider(): BelongsTo
    {
        return $this->belongsTo(User::class, 'service_provider_id');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function hotelBooking(): BelongsTo
    {
        return $this->belongsTo(HotelBooking::class);
    }

    public function tranBooking(): BelongsTo
    {
        return $this->belongsTo(TransportBooking::class);
    }
}
