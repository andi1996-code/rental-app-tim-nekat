<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rental extends Model
{
    protected $fillable = [
        'uuid',
        'user_id',
        'customer_id',
        'car_id',
        'promotion_id',
        'start_date',
        'end_date',
        'total_days',
        'pickup_location_id',
        'dropoff_location_id',
        'rental_type',
        'rental_status',
        'base_price',
        'driver_fee',
        'insurance_fee',
        'discount_amount',
        'tax_amount',
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
            'base_price' => 'decimal:2',
            'driver_fee' => 'decimal:2',
            'insurance_fee' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'tax_amount' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'final_amount' => 'decimal:2',
        ];
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    public function promotion()
    {
        return $this->belongsTo(Promotion::class);
    }

    public function pickupLocation()
    {
        return $this->belongsTo(Location::class, 'pickup_location_id');
    }

    public function dropoffLocation()
    {
        return $this->belongsTo(Location::class, 'dropoff_location_id');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
