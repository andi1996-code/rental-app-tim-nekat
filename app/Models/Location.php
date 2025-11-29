<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $fillable = [
        'name',
        'address',
        'city',
        'province',
        'phone_number',
        'operating_hours',
        'email',
        'latitude',
        'longitude',
        'is_active',
        'opening_hours',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
            'is_active' => 'boolean',
            'opening_hours' => 'array',
        ];
    }

    // Relationships
    public function cars()
    {
        return $this->hasMany(Car::class, 'location_id');
    }

    public function pickupRentals()
    {
        return $this->hasMany(Rental::class, 'pickup_location_id');
    }

    public function dropoffRentals()
    {
        return $this->hasMany(Rental::class, 'dropoff_location_id');
    }

    public function managedByAdmins()
    {
        return $this->hasMany(User::class, 'managed_location_id');
    }
}
