<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Super Admin
        $superAdmin = \App\Models\User::create([
            'uuid' => \Illuminate\Support\Str::uuid(),
            'email' => 'superadmin@rentalapp.com',
            'password_hash' => bcrypt('password123'),
            'user_type' => 'super_admin',
            'full_name' => 'Super Admin',
            'phone_number' => '081234567890',
            'is_verified' => true,
            'is_active' => true,
        ]);

        // Admin Bandung
        $adminBandung = \App\Models\User::create([
            'uuid' => \Illuminate\Support\Str::uuid(),
            'email' => 'admin.bandung@rentalapp.com',
            'password_hash' => bcrypt('password123'),
            'user_type' => 'admin',
            'full_name' => 'Admin Bandung',
            'phone_number' => '082234567890',
            'is_verified' => true,
            'is_active' => true,
            'managed_location_id' => 1, // Bandung location
        ]);

        // Admin Jakarta
        $adminJakarta = \App\Models\User::create([
            'uuid' => \Illuminate\Support\Str::uuid(),
            'email' => 'admin.jakarta@rentalapp.com',
            'password_hash' => bcrypt('password123'),
            'user_type' => 'admin',
            'full_name' => 'Admin Jakarta',
            'phone_number' => '083234567890',
            'is_verified' => true,
            'is_active' => true,
            'managed_location_id' => 2, // Jakarta location
        ]);

        // Admin Surabaya
        $adminSurabaya = \App\Models\User::create([
            'uuid' => \Illuminate\Support\Str::uuid(),
            'email' => 'admin.surabaya@rentalapp.com',
            'password_hash' => bcrypt('password123'),
            'user_type' => 'admin',
            'full_name' => 'Admin Surabaya',
            'phone_number' => '084234567890',
            'is_verified' => true,
            'is_active' => true,
            'managed_location_id' => 3, // Surabaya location
        ]);

        // Customer 1 - Gold Member
        $customer1 = \App\Models\User::create([
            'uuid' => \Illuminate\Support\Str::uuid(),
            'email' => 'customer1@gmail.com',
            'password_hash' => bcrypt('password123'),
            'user_type' => 'customer',
            'full_name' => 'Budi Santoso',
            'phone_number' => '085234567890',
            'date_of_birth' => '1990-05-15',
            'driver_license_number' => 'SIM-1234567890',
            'is_verified' => true,
            'is_active' => true,
        ]);

        \App\Models\CustomerProfile::create([
            'user_id' => $customer1->id,
            'address' => 'Jl. Sudirman No. 123',
            'city' => 'Jakarta',
            'province' => 'DKI Jakarta',
            'postal_code' => '12345',
            'emergency_contact_name' => 'Ani Santoso',
            'emergency_contact_phone' => '089234567890',
            'membership_level' => 'gold',
            'total_rentals' => 15,
            'total_spent' => 25000000.00,
            'rating' => 4.8,
        ]);

        // Customer 2 - Silver Member
        $customer2 = \App\Models\User::create([
            'uuid' => \Illuminate\Support\Str::uuid(),
            'email' => 'customer2@gmail.com',
            'password_hash' => bcrypt('password123'),
            'user_type' => 'customer',
            'full_name' => 'Siti Nurhaliza',
            'phone_number' => '086234567890',
            'date_of_birth' => '1995-08-20',
            'driver_license_number' => 'SIM-9876543210',
            'is_verified' => true,
            'is_active' => true,
        ]);

        \App\Models\CustomerProfile::create([
            'user_id' => $customer2->id,
            'address' => 'Jl. Gatot Subroto No. 45',
            'city' => 'Bandung',
            'province' => 'Jawa Barat',
            'postal_code' => '40123',
            'emergency_contact_name' => 'Ahmad Nurhaliza',
            'emergency_contact_phone' => '087234567890',
            'membership_level' => 'silver',
            'total_rentals' => 8,
            'total_spent' => 12000000.00,
            'rating' => 4.5,
        ]);

        // Customer 3 - Regular Member
        $customer3 = \App\Models\User::create([
            'uuid' => \Illuminate\Support\Str::uuid(),
            'email' => 'customer3@gmail.com',
            'password_hash' => bcrypt('password123'),
            'user_type' => 'customer',
            'full_name' => 'Andi Wijaya',
            'phone_number' => '088234567890',
            'date_of_birth' => '1998-12-10',
            'driver_license_number' => 'SIM-5555555555',
            'is_verified' => true,
            'is_active' => true,
        ]);

        \App\Models\CustomerProfile::create([
            'user_id' => $customer3->id,
            'address' => 'Jl. Ahmad Yani No. 78',
            'city' => 'Surabaya',
            'province' => 'Jawa Timur',
            'postal_code' => '60123',
            'emergency_contact_name' => 'Rini Wijaya',
            'emergency_contact_phone' => '089987654321',
            'membership_level' => 'regular',
            'total_rentals' => 2,
            'total_spent' => 3000000.00,
            'rating' => 5.0,
        ]);

        // Customer 4 - New Customer (belum verified)
        $customer4 = \App\Models\User::create([
            'uuid' => \Illuminate\Support\Str::uuid(),
            'email' => 'newcustomer@gmail.com',
            'password_hash' => bcrypt('password123'),
            'user_type' => 'customer',
            'full_name' => 'John Doe',
            'phone_number' => '081111111111',
            'is_verified' => false,
            'is_active' => true,
        ]);

        \App\Models\CustomerProfile::create([
            'user_id' => $customer4->id,
            'membership_level' => 'regular',
            'total_rentals' => 0,
            'total_spent' => 0.00,
            'rating' => 5.0,
        ]);

        $this->command->info('✅ Users seeded successfully!');
        $this->command->info('📧 Super Admin: superadmin@rentalapp.com');
        $this->command->info('📧 Admin Bandung: admin.bandung@rentalapp.com');
        $this->command->info('📧 Admin Jakarta: admin.jakarta@rentalapp.com');
        $this->command->info('📧 Admin Surabaya: admin.surabaya@rentalapp.com');
        $this->command->info('📧 Customer 1 (Gold): customer1@gmail.com');
        $this->command->info('📧 Customer 2 (Silver): customer2@gmail.com');
        $this->command->info('📧 Customer 3 (Regular): customer3@gmail.com');
        $this->command->info('📧 Customer 4 (New): newcustomer@gmail.com');
        $this->command->info('🔑 Password for all: password123');
    }
}
