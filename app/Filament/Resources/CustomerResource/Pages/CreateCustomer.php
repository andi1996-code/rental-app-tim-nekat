<?php

namespace App\Filament\Resources\CustomerResource\Pages;

use App\Filament\Resources\CustomerResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCustomer extends CreateRecord
{
    protected static string $resource = CustomerResource::class;

    protected function afterCreate(): void
    {
        // Pastikan customer profile dibuat saat customer dibuat
        if (!$this->record->customerProfile) {
            $this->record->customerProfile()->create([
                'membership_level' => 'regular',
                'total_rentals' => 0,
                'total_spent' => 0,
                'rating' => 5.0,
            ]);
        }
    }
}
