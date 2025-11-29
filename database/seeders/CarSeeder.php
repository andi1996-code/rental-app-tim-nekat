<?php

namespace Database\Seeders;

use App\Models\Car;
use App\Models\CarCategory;
use App\Models\Location;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🚗 Seeding cars...');

        // Ambil data yang dibutuhkan
        $categories = CarCategory::all();
        $locations = Location::all();

        if ($categories->isEmpty()) {
            $this->command->error('❌ No car categories found. Please run CarCategorySeeder first.');
            return;
        }

        if ($locations->isEmpty()) {
            $this->command->error('❌ No locations found. Please run LocationSeeder first.');
            return;
        }

        // Data mobil berdasarkan kategori
        $carsByCategory = [
            'Sedan' => [
                ['brand' => 'Toyota', 'model' => 'Camry', 'year' => 2023, 'color' => 'Silver', 'seats' => 5, 'rate' => 500000],
                ['brand' => 'Honda', 'model' => 'Accord', 'year' => 2022, 'color' => 'Black', 'seats' => 5, 'rate' => 480000],
                ['brand' => 'Mazda', 'model' => 'Mazda6', 'year' => 2023, 'color' => 'Red', 'seats' => 5, 'rate' => 520000],
                ['brand' => 'Toyota', 'model' => 'Corolla Altis', 'year' => 2022, 'color' => 'White', 'seats' => 5, 'rate' => 450000],
                ['brand' => 'Honda', 'model' => 'Civic', 'year' => 2023, 'color' => 'Blue', 'seats' => 5, 'rate' => 470000],
            ],
            'SUV' => [
                ['brand' => 'Toyota', 'model' => 'Fortuner', 'year' => 2023, 'color' => 'Black', 'seats' => 7, 'rate' => 750000],
                ['brand' => 'Mitsubishi', 'model' => 'Pajero Sport', 'year' => 2022, 'color' => 'White', 'seats' => 7, 'rate' => 700000],
                ['brand' => 'Honda', 'model' => 'CR-V', 'year' => 2023, 'color' => 'Silver', 'seats' => 7, 'rate' => 650000],
                ['brand' => 'Mazda', 'model' => 'CX-5', 'year' => 2022, 'color' => 'Red', 'seats' => 5, 'rate' => 600000],
                ['brand' => 'Toyota', 'model' => 'Rush', 'year' => 2023, 'color' => 'Grey', 'seats' => 7, 'rate' => 550000],
            ],
            'MPV' => [
                ['brand' => 'Toyota', 'model' => 'Avanza', 'year' => 2023, 'color' => 'White', 'seats' => 7, 'rate' => 400000],
                ['brand' => 'Toyota', 'model' => 'Innova Reborn', 'year' => 2022, 'color' => 'Silver', 'seats' => 7, 'rate' => 550000],
                ['brand' => 'Honda', 'model' => 'Mobilio', 'year' => 2023, 'color' => 'Black', 'seats' => 7, 'rate' => 380000],
                ['brand' => 'Mitsubishi', 'model' => 'Xpander', 'year' => 2023, 'color' => 'Red', 'seats' => 7, 'rate' => 420000],
                ['brand' => 'Suzuki', 'model' => 'Ertiga', 'year' => 2022, 'color' => 'Blue', 'seats' => 7, 'rate' => 350000],
            ],
            'Hatchback' => [
                ['brand' => 'Honda', 'model' => 'Jazz', 'year' => 2023, 'color' => 'Yellow', 'seats' => 5, 'rate' => 380000],
                ['brand' => 'Toyota', 'model' => 'Yaris', 'year' => 2022, 'color' => 'White', 'seats' => 5, 'rate' => 370000],
                ['brand' => 'Mazda', 'model' => 'Mazda2', 'year' => 2023, 'color' => 'Red', 'seats' => 5, 'rate' => 390000],
                ['brand' => 'Suzuki', 'model' => 'Swift', 'year' => 2022, 'color' => 'Blue', 'seats' => 5, 'rate' => 340000],
            ],
            'Luxury' => [
                ['brand' => 'Mercedes-Benz', 'model' => 'C-Class', 'year' => 2023, 'color' => 'Black', 'seats' => 5, 'rate' => 1500000],
                ['brand' => 'BMW', 'model' => '3 Series', 'year' => 2023, 'color' => 'White', 'seats' => 5, 'rate' => 1450000],
                ['brand' => 'Audi', 'model' => 'A4', 'year' => 2022, 'color' => 'Silver', 'seats' => 5, 'rate' => 1400000],
                ['brand' => 'Mercedes-Benz', 'model' => 'E-Class', 'year' => 2023, 'color' => 'Grey', 'seats' => 5, 'rate' => 1800000],
            ],
            'City Car' => [
                ['brand' => 'Toyota', 'model' => 'Agya', 'year' => 2023, 'color' => 'White', 'seats' => 5, 'rate' => 280000],
                ['brand' => 'Daihatsu', 'model' => 'Ayla', 'year' => 2022, 'color' => 'Red', 'seats' => 5, 'rate' => 270000],
                ['brand' => 'Honda', 'model' => 'Brio', 'year' => 2023, 'color' => 'Green', 'seats' => 5, 'rate' => 300000],
                ['brand' => 'Suzuki', 'model' => 'Karimun Wagon R', 'year' => 2022, 'color' => 'Silver', 'seats' => 5, 'rate' => 260000],
            ],
        ];

        $transmissions = ['manual', 'automatic'];
        $fuelTypes = ['bensin', 'diesel', 'electric', 'hybrid'];
        $statuses = ['available', 'rented', 'maintenance'];

        $carCount = 0;

        foreach ($categories as $category) {
            // Cari data mobil untuk kategori ini
            $carsData = $carsByCategory[$category->name] ?? [];

            if (empty($carsData)) {
                continue;
            }

            foreach ($carsData as $carData) {
                // Distribusi ke berbagai lokasi
                $locationIndex = $carCount % $locations->count();
                $location = $locations[$locationIndex];

                // Status: 70% available, 20% rented, 10% maintenance
                $rand = rand(1, 100);
                if ($rand <= 70) {
                    $status = 'available';
                } elseif ($rand <= 90) {
                    $status = 'rented';
                } else {
                    $status = 'maintenance';
                }

                // Generate random features
                $features = [
                    'AC',
                    'Power Steering',
                    'Power Window',
                    'Central Lock',
                ];

                // Add random premium features
                $premiumFeatures = ['Leather Seats', 'Sunroof', 'GPS Navigation', 'Parking Sensor', 'Camera', 'Bluetooth', 'USB Port', 'ABS', 'Airbags'];
                $numPremiumFeatures = rand(2, 6);
                $selectedFeatures = array_merge($features, array_rand(array_flip($premiumFeatures), $numPremiumFeatures));

                Car::create([
                    'uuid' => Str::uuid()->toString(),
                    'category_id' => $category->id,
                    'location_id' => $location->id,
                    'brand' => $carData['brand'],
                    'model' => $carData['model'],
                    'year' => $carData['year'],
                    'license_plate' => $this->generateLicensePlate(),
                    'color' => $carData['color'],
                    'transmission' => $transmissions[array_rand($transmissions)],
                    'fuel_type' => $fuelTypes[array_rand($fuelTypes)],
                    'seat_capacity' => $carData['seats'],
                    'mileage' => rand(5000, 80000),
                    'daily_rate' => $carData['rate'],
                    'weekly_discount' => 10.00, // 10% discount
                    'monthly_discount' => 20.00, // 20% discount
                    'features' => $selectedFeatures,
                    'images' => [
                        'https://via.placeholder.com/800x600/007bff/ffffff?text=' . urlencode($carData['brand'] . ' ' . $carData['model']),
                        'https://via.placeholder.com/800x600/28a745/ffffff?text=' . urlencode($carData['brand'] . ' ' . $carData['model']),
                        'https://via.placeholder.com/800x600/dc3545/ffffff?text=' . urlencode($carData['brand'] . ' ' . $carData['model']),
                    ],
                    'status' => $status,
                    'latitude' => $location->latitude + (rand(-1000, 1000) / 10000), // Small offset dari location
                    'longitude' => $location->longitude + (rand(-1000, 1000) / 10000),
                ]);

                $carCount++;
            }
        }

        $this->command->info("✅ Created {$carCount} cars");
    }

    /**
     * Generate random Indonesian license plate
     */
    private function generateLicensePlate(): string
    {
        $areas = ['B', 'D', 'F', 'L', 'N', 'T', 'K', 'S', 'H', 'AA', 'AB'];
        $area = $areas[array_rand($areas)];
        $numbers = rand(1000, 9999);
        $letters = chr(rand(65, 90)) . chr(rand(65, 90)) . chr(rand(65, 90));

        return "{$area} {$numbers} {$letters}";
    }
}
