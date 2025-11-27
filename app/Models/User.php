<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'uuid',
        'email',
        'password_hash',
        'user_type',
        'full_name',
        'phone_number',
        'date_of_birth',
        'driver_license_number',
        'driver_license_image',
        'profile_image',
        'is_verified',
        'is_active',
        'managed_location_id',
        'last_login',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password_hash',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'is_verified' => 'boolean',
            'is_active' => 'boolean',
            'last_login' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    // Relationships
    public function customerProfile()
    {
        return $this->hasOne(CustomerProfile::class);
    }

    public function rentals()
    {
        return $this->hasMany(Rental::class, 'customer_id');
    }

    public function maintenances()
    {
        return $this->hasMany(Maintenance::class, 'admin_id');
    }

    public function promotions()
    {
        return $this->hasMany(Promotion::class, 'created_by');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function managedLocation()
    {
        return $this->belongsTo(Location::class, 'managed_location_id');
    }
}
