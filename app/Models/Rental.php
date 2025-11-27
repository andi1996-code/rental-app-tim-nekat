<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rental extends Model
{
    protected $fillable = [
        'uuid',
        'customer_id',
        'car_id',
        'start_date',
        'end_date',
        'total_days',
        'pickup_location_id',
        'dropoff_location_id',
        'rental_type',
        'total_amount',
        'discount_amount',
        'final_amount',
        'status',
        'payment_status',
        'driver_option',
        'special_requests',
        'admin_notes',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'total_days' => 'integer',
            'total_amount' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'final_amount' => 'decimal:2',
        ];
    }

    // Relationships
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    public function pickupLocation()
    {
        return $this->belongsTo(Location::class, 'pickup_location_id');
    }

    public function dropoffLocation()
    {
        return $this->belongsTo(Location::class, 'dropoff_location_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
