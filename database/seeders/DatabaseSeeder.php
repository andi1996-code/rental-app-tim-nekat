<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('🌱 Starting database seeding...');

        // Seed in order (karena ada foreign key dependencies)
        $this->call([
            // 1. Master data dulu
            LocationSeeder::class,
            CarCategorySeeder::class,

            // 2. Users (termasuk customers dan admins)
            UserSeeder::class,

            // 3. Cars (butuh location dan category)
            CarSeeder::class,

            // 4. Maintenance (butuh cars dan admins)
            MaintenanceSeeder::class,

            // 5. Rentals (butuh customers, cars, dan locations)
            RentalSeeder::class,
        ]);

        $this->command->info('');
        $this->command->info('✅ Database seeding completed successfully!');
        $this->command->info('');
        $this->command->info('📊 Summary:');
        $this->command->info('   - Locations seeded');
        $this->command->info('   - Car categories seeded');
        $this->command->info('   - Users (customers & admins) seeded');
        $this->command->info('   - Cars seeded');
        $this->command->info('   - Maintenance records seeded');
        $this->command->info('   - Rental records seeded');
        $this->command->info('');
    }
}
