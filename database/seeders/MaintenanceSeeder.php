<?php

namespace Database\Seeders;

use App\Models\Car;
use App\Models\Maintenance;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MaintenanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🔧 Seeding maintenance records...');

        // Ambil data yang dibutuhkan
        $cars = Car::all();
        $admins = User::whereIn('user_type', ['admin', 'super_admin'])->get();

        if ($cars->isEmpty()) {
            $this->command->error('❌ No cars found. Please run CarSeeder first.');
            return;
        }

        if ($admins->isEmpty()) {
            $this->command->error('❌ No admin users found. Please run UserSeeder first.');
            return;
        }

        // Tipe maintenance yang umum (sesuai enum: routine, repair, accident)
        $maintenanceTypes = [
            'routine' => [
                'descriptions' => [
                    'Regular oil change and filter replacement',
                    'Tire pressure check and adjustment',
                    'Brake fluid replacement',
                    'Air filter replacement',
                    'Scheduled service checkup',
                ],
                'cost_range' => [300000, 800000],
                'duration_days' => 1,
            ],
            'repair' => [
                'descriptions' => [
                    'Engine diagnostics and repair',
                    'Transmission system repair',
                    'Brake pad and disc replacement',
                    'AC system repair and freon refill',
                    'Battery replacement',
                    'Electrical wiring repair',
                    'Suspension and shock absorber repair',
                    'Tire replacement (worn out)',
                ],
                'cost_range' => [500000, 5000000],
                'duration_days' => [1, 5],
            ],
            'accident' => [
                'descriptions' => [
                    'Front bumper damage repair',
                    'Side panel dent repair and paint',
                    'Rear light replacement',
                    'Windshield replacement',
                    'Door alignment and repair',
                    'Mirror replacement',
                    'Major body damage repair',
                ],
                'cost_range' => [1000000, 10000000],
                'duration_days' => [3, 10],
            ],
        ];

        $statuses = ['scheduled', 'in_progress', 'completed'];

        $maintenanceCount = 0;

        // Buat maintenance untuk sebagian mobil (sekitar 40%)
        $carsNeedingMaintenance = $cars->random(min((int)($cars->count() * 0.4), $cars->count()));

        foreach ($carsNeedingMaintenance as $car) {
            // Setiap mobil bisa punya 1-3 maintenance records
            $numRecords = rand(1, 3);

            for ($i = 0; $i < $numRecords; $i++) {
                // Pilih random maintenance type
                $maintenanceType = array_rand($maintenanceTypes);
                $maintenanceData = $maintenanceTypes[$maintenanceType];

                // Random description dari list
                $description = $maintenanceData['descriptions'][array_rand($maintenanceData['descriptions'])];

                // Random admin
                $admin = $admins->random();

                // Random status
                $status = $statuses[array_rand($statuses)];

                // Tanggal
                $startDate = now()->subDays(rand(1, 180));

                // Duration days
                if (is_array($maintenanceData['duration_days'])) {
                    $durationDays = rand($maintenanceData['duration_days'][0], $maintenanceData['duration_days'][1]);
                } else {
                    $durationDays = $maintenanceData['duration_days'];
                }

                $expectedEndDate = $startDate->copy()->addDays($durationDays);

                // Jika completed, set actual end date
                $actualEndDate = null;
                if ($status === 'completed') {
                    // Bisa selesai lebih cepat atau lebih lama dari expected
                    $actualEndDate = $startDate->copy()->addDays($durationDays + rand(-1, 3));
                }

                // Jika in_progress, start date lebih recent
                if ($status === 'in_progress') {
                    $startDate = now()->subDays(rand(1, $durationDays - 1));
                    $expectedEndDate = $startDate->copy()->addDays($durationDays);
                }

                // Jika scheduled, start date di masa depan
                if ($status === 'scheduled') {
                    $startDate = now()->addDays(rand(1, 30));
                    $expectedEndDate = $startDate->copy()->addDays($durationDays);
                }

                // Random cost dalam range
                $cost = rand($maintenanceData['cost_range'][0], $maintenanceData['cost_range'][1]);

                Maintenance::create([
                    'car_id' => $car->id,
                    'admin_id' => $admin->id,
                    'maintenance_type' => $maintenanceType,
                    'description' => $description,
                    'start_date' => $startDate,
                    'expected_end_date' => $expectedEndDate,
                    'actual_end_date' => $actualEndDate,
                    'cost' => $cost,
                    'status' => $status,
                ]);

                $maintenanceCount++;

                // Update car status ke maintenance jika status maintenance adalah in_progress
                if ($status === 'in_progress' && $car->status !== 'rented') {
                    $car->update(['status' => 'maintenance']);
                }
            }
        }

        $this->command->info("✅ Created {$maintenanceCount} maintenance records");
    }
}
