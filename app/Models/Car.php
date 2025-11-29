<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    protected $fillable = [
        'uuid',
        'category_id',
        'location_id',
        'brand',
        'model',
        'year',
        'license_plate',
        'color',
        'transmission',
        'fuel_type',
        'seat_capacity',
        'mileage',
        'price_per_day',
        'daily_rate',
        'weekly_discount',
        'monthly_discount',
        'availability_status',
        'features',
        'images',
        'status',
        'latitude',
        'longitude',
    ];

    protected function casts(): array
    {
        return [
            'year' => 'integer',
            'seat_capacity' => 'integer',
            'mileage' => 'decimal:2',
            'price_per_day' => 'decimal:2',
            'daily_rate' => 'decimal:2',
            'weekly_discount' => 'decimal:2',
            'monthly_discount' => 'decimal:2',
            'features' => 'array',
            'images' => 'array',
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
        ];
    }

    // Relationships
    public function category()
    {
        return $this->belongsTo(CarCategory::class, 'category_id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function rentals()
    {
        return $this->hasMany(Rental::class);
    }

    public function maintenances()
    {
        return $this->hasMany(Maintenance::class);
    }
}
