<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    const CUSTOMER_ROLE_ID = 1;
    const AGENT_ROLE_ID = 2;
    const ADMIN_ROLE_ID = 3;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'role_id',
        'email',
        'phone',
        'lat',
        'lng',
        'country_id',
        'nationality_country_id',
        'email_verified_at',
        'phone_verified_at',
        'email_verification_code',
        'email_verification_code_expires',
        'phone_verification_code',
        'phone_verification_code_expires',
        'password',
        'last_seen_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_verified_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Find a user by their email address.
     *
     * @param  string  $email
     * @return \App\Models\User|null
     */
    public static function findByEmail($email)
    {
        return static::where('email', $email)->first();
    }

    /**
     * Find a user by their phone number.
     *
     * @param  string  $email
     * @return \App\Models\User|null
     */
    public static function findByPhone($phone)
    {
        return static::where('phone', $phone)->first();
    }

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function nationality(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'nationality_country_id');
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function image(): MorphOne
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'group_member', 'member_id', 'group_id');
    }

    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class);
    }

    public function accounts(): HasMany
    {
        return $this->hasMany(UserAccount::class);
    }

    public function registeredCompanies(): HasMany
    {
        return $this->hasMany(Company::class,  'owner_id');
    }

    public function companies(): BelongsToMany
    {
        return $this->belongsToMany(Company::class, 'company_user', 'user_id', 'company_id');
    }

    public function hotelBookings(): HasMany
    {
        return $this->hasMany(HotelBooking::class);
    }

    public function hotelsServiceProvider(): HasMany
    {
        return $this->hasMany(HotelServiceProvider::class, 'service_provider_id');
    }

    public function transportBookings(): HasMany
    {
        return $this->hasMany(TransportBooking::class);
    }

    public function blogs(): HasMany
    {
        return $this->hasMany(Blog::class, 'author_id');
    }

    public function umrahs(): HasMany
    {
        return $this->hasMany(Umrah::class);
    }

    public function umrahsServiceProvider(): HasMany
    {
        return $this->hasMany(Umrah::class, 'service_provider_id');
    }

    public static function generateRandomCode(): int
    {
        return random_int(100000, 999999); // generate random code of six digits
    }
}
