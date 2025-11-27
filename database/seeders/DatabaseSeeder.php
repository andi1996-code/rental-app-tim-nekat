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
            LocationSeeder::class,
            CarCategorySeeder::class,
            UserSeeder::class,
            // CarSeeder::class, // Uncomment jika sudah ada isinya
        ]);

        $this->command->info('✅ Database seeding completed!');
    }
}
