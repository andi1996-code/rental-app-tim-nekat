<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarCategory extends Model
{
    protected $fillable = [
        'name',
        'description',
        'icon',
        'daily_rate',
        'weekly_rate',
        'monthly_rate',
    ];

    protected function casts(): array
    {
        return [
            'daily_rate' => 'decimal:2',
            'weekly_rate' => 'decimal:2',
            'monthly_rate' => 'decimal:2',
        ];
    }

    // Relationships
    public function cars()
    {
        return $this->hasMany(Car::class, 'category_id');
    }
}
