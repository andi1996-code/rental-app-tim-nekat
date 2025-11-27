<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Maintenance extends Model
{
    protected $table = 'maintenance';

    protected $fillable = [
        'car_id',
        'admin_id',
        'maintenance_type',
        'description',
        'start_date',
        'expected_end_date',
        'actual_end_date',
        'cost',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'expected_end_date' => 'date',
            'actual_end_date' => 'date',
            'cost' => 'decimal:2',
        ];
    }

    // Relationships
    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
