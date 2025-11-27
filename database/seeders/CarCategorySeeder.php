<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CarCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Economy',
                'description' => 'Mobil hemat untuk perjalanan efisien',
                'icon' => 'economy-icon.png',
                'daily_rate' => 200000.00,
                'weekly_rate' => 1200000.00,
                'monthly_rate' => 4500000.00,
            ],
            [
                'name' => 'SUV',
                'description' => 'Mobil besar dan nyaman untuk keluarga',
                'icon' => 'suv-icon.png',
                'daily_rate' => 500000.00,
                'weekly_rate' => 3000000.00,
                'monthly_rate' => 11000000.00,
            ],
            [
                'name' => 'MPV',
                'description' => 'Multi-purpose vehicle untuk grup besar',
                'icon' => 'mpv-icon.png',
                'daily_rate' => 400000.00,
                'weekly_rate' => 2400000.00,
                'monthly_rate' => 9000000.00,
            ],
            [
                'name' => 'Sedan',
                'description' => 'Mobil elegan untuk perjalanan bisnis',
                'icon' => 'sedan-icon.png',
                'daily_rate' => 350000.00,
                'weekly_rate' => 2100000.00,
                'monthly_rate' => 7500000.00,
            ],
            [
                'name' => 'Luxury',
                'description' => 'Mobil mewah untuk pengalaman premium',
                'icon' => 'luxury-icon.png',
                'daily_rate' => 1000000.00,
                'weekly_rate' => 6000000.00,
                'monthly_rate' => 22000000.00,
            ],
        ];

        foreach ($categories as $category) {
            \App\Models\CarCategory::create($category);
        }
    }
}
