<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerProfile extends Model
{
    protected $fillable = [
        'user_id',
        'address',
        'city',
        'province',
        'postal_code',
        'emergency_contact_name',
        'emergency_contact_phone',
        'membership_level',
        'total_rentals',
        'total_spent',
        'rating',
    ];

    protected function casts(): array
    {
        return [
            'total_rentals' => 'integer',
            'total_spent' => 'decimal:2',
            'rating' => 'decimal:2',
        ];
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
