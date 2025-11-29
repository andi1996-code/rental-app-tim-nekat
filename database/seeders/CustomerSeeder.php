<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\CustomerProfile;
use Illuminate\Support\Facades\Hash;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        // Create test customer
        $user = User::create([
            'email' => 'customer1@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'customer',
            'is_active' => true,
            'is_verified' => true,
        ]);

        CustomerProfile::create([
            'user_id' => $user->id,
            'full_name' => 'John Doe',
            'phone_number' => '081234567890',
            'date_of_birth' => '1990-01-15',
            'driver_license_number' => 'SIM-1234567890',
            'address' => 'Jl. Sudirman No. 123',
            'city' => 'Jakarta',
            'province' => 'DKI Jakarta',
            'postal_code' => '12345',
        ]);

        // Create another test customer
        $user2 = User::create([
            'email' => 'customer2@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'customer',
            'is_active' => true,
            'is_verified' => true,
        ]);

        CustomerProfile::create([
            'user_id' => $user2->id,
            'full_name' => 'Jane Smith',
            'phone_number' => '089234567890',
            'date_of_birth' => '1992-05-20',
            'driver_license_number' => 'SIM-0987654321',
            'address' => 'Jl. Thamrin No. 456',
            'city' => 'Bandung',
            'province' => 'Jawa Barat',
            'postal_code' => '54321',
        ]);
    }
}
