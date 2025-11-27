<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'rental_id',
        'payment_method',
        'payment_gateway',
        'gateway_transaction_id',
        'amount',
        'fee_amount',
        'status',
        'payment_date',
        'expiration_date',
        'receipt_image',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'fee_amount' => 'decimal:2',
            'payment_date' => 'datetime',
            'expiration_date' => 'datetime',
        ];
    }

    // Relationships
    public function rental()
    {
        return $this->belongsTo(Rental::class);
    }
}
