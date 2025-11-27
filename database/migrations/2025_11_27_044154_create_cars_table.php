<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('category_id')->constrained('car_categories')->onDelete('cascade');
            $table->foreignId('location_id')->constrained('locations')->onDelete('cascade');
            $table->string('brand', 100);
            $table->string('model', 100);
            $table->integer('year');
            $table->string('license_plate', 20)->unique();
            $table->string('color', 50)->nullable();
            $table->enum('transmission', ['manual', 'automatic']);
            $table->enum('fuel_type', ['bensin', 'diesel', 'electric', 'hybrid']);
            $table->integer('seat_capacity');
            $table->decimal('mileage', 10, 2)->default(0.00);
            $table->decimal('daily_rate', 10, 2);
            $table->decimal('weekly_discount', 5, 2)->default(0.00);
            $table->decimal('monthly_discount', 5, 2)->default(0.00);
            $table->json('features')->nullable();
            $table->json('images')->nullable();
            $table->enum('status', ['available', 'rented', 'maintenance', 'unavailable'])->default('available');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
