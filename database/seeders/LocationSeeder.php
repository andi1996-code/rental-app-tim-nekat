<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $locations = [
            [
                'name' => 'Bandung Central Office',
                'address' => 'Jl. Asia Afrika No. 8',
                'city' => 'Bandung',
                'province' => 'Jawa Barat',
                'phone_number' => '022-4201234',
                'email' => 'bandung@rentalcar.com',
                'latitude' => -6.921381,
                'longitude' => 107.607204,
                'is_active' => true,
                'opening_hours' => [
                    'monday' => '08:00-17:00',
                    'tuesday' => '08:00-17:00',
                    'wednesday' => '08:00-17:00',
                    'thursday' => '08:00-17:00',
                    'friday' => '08:00-17:00',
                    'saturday' => '09:00-15:00',
                    'sunday' => 'Closed',
                ],
            ],
            [
                'name' => 'Jakarta Airport Branch',
                'address' => 'Soekarno-Hatta International Airport',
                'city' => 'Tangerang',
                'province' => 'Banten',
                'phone_number' => '021-5501234',
                'email' => 'jakarta@rentalcar.com',
                'latitude' => -6.125567,
                'longitude' => 106.655897,
                'is_active' => true,
                'opening_hours' => [
                    'monday' => '24 hours',
                    'tuesday' => '24 hours',
                    'wednesday' => '24 hours',
                    'thursday' => '24 hours',
                    'friday' => '24 hours',
                    'saturday' => '24 hours',
                    'sunday' => '24 hours',
                ],
            ],
            [
                'name' => 'Surabaya Office',
                'address' => 'Jl. Raya Darmo No. 100',
                'city' => 'Surabaya',
                'province' => 'Jawa Timur',
                'phone_number' => '031-7771234',
                'email' => 'surabaya@rentalcar.com',
                'latitude' => -7.265757,
                'longitude' => 112.734146,
                'is_active' => true,
                'opening_hours' => [
                    'monday' => '08:00-17:00',
                    'tuesday' => '08:00-17:00',
                    'wednesday' => '08:00-17:00',
                    'thursday' => '08:00-17:00',
                    'friday' => '08:00-17:00',
                    'saturday' => '09:00-15:00',
                    'sunday' => 'Closed',
                ],
            ],
        ];

        foreach ($locations as $location) {
            \App\Models\Location::create($location);
        }
    }
}
