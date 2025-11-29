<?php

namespace Database\Seeders;

use App\Models\Car;
use App\Models\Location;
use App\Models\Rental;
use App\Models\User;
use App\Models\CustomerProfile;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RentalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🚘 Seeding rentals...');

        // Ambil data yang dibutuhkan
        $customers = User::where('user_type', 'customer')->get();
        $cars = Car::all();
        $locations = Location::all();

        if ($customers->isEmpty()) {
            $this->command->error('❌ No customers found. Please run UserSeeder first and ensure customers exist.');
            return;
        }

        if ($cars->isEmpty()) {
            $this->command->error('❌ No cars found. Please run CarSeeder first.');
            return;
        }

        if ($locations->isEmpty()) {
            $this->command->error('❌ No locations found. Please run LocationSeeder first.');
            return;
        }

        $rentalTypes = ['daily', 'weekly', 'monthly'];
        $statuses = ['pending', 'confirmed', 'active', 'completed', 'cancelled'];
        $paymentStatuses = ['pending', 'paid', 'failed', 'refunded'];
        $driverOptions = ['self_drive', 'with_driver'];

        $rentalCount = 0;

        // Buat rental untuk setiap customer (1-5 rentals per customer)
        foreach ($customers as $customer) {
            $numRentals = rand(1, 5);

            for ($i = 0; $i < $numRentals; $i++) {
                // Pilih random car
                $car = $cars->random();

                // Random rental type
                $rentalType = $rentalTypes[array_rand($rentalTypes)];

                // Tentukan total days berdasarkan rental type
                if ($rentalType === 'daily') {
                    $totalDays = rand(1, 6);
                } elseif ($rentalType === 'weekly') {
                    $totalDays = rand(7, 13);
                } else { // monthly
                    $totalDays = rand(30, 60);
                }

                // Random status dengan distribusi realistis
                $statusRand = rand(1, 100);
                if ($statusRand <= 10) {
                    $status = 'pending';
                } elseif ($statusRand <= 25) {
                    $status = 'confirmed';
                } elseif ($statusRand <= 35) {
                    $status = 'active';
                } elseif ($statusRand <= 85) {
                    $status = 'completed';
                } else {
                    $status = 'cancelled';
                }

                // Tanggal berdasarkan status
                if ($status === 'pending' || $status === 'confirmed') {
                    // Future rental
                    $startDate = now()->addDays(rand(1, 30));
                } elseif ($status === 'active') {
                    // Ongoing rental
                    $startDate = now()->subDays(rand(1, $totalDays - 1));
                } else {
                    // Past rental (completed or cancelled)
                    $startDate = now()->subDays(rand($totalDays, 180));
                }

                $endDate = $startDate->copy()->addDays($totalDays);

                // Payment status based on rental status
                if ($status === 'pending') {
                    $paymentStatus = rand(0, 1) ? 'pending' : 'failed';
                } elseif ($status === 'confirmed') {
                    $paymentStatus = 'paid';
                } elseif ($status === 'active') {
                    $paymentStatus = 'paid';
                } elseif ($status === 'completed') {
                    $paymentStatus = 'paid';
                } else { // cancelled
                    $paymentStatus = rand(0, 1) ? 'refunded' : 'pending';
                }

                // Random locations
                $pickupLocation = $locations->random();
                $dropoffLocation = rand(0, 1) ? $pickupLocation : $locations->random();

                // Calculate amounts
                $dailyRate = $car->daily_rate;
                $totalAmount = $dailyRate * $totalDays;

                // Apply discount based on rental type
                $discountAmount = 0;
                if ($rentalType === 'weekly' && $totalDays >= 7) {
                    $discountAmount = $totalAmount * ($car->weekly_discount / 100);
                } elseif ($rentalType === 'monthly' && $totalDays >= 30) {
                    $discountAmount = $totalAmount * ($car->monthly_discount / 100);
                }

                // Random additional discount (promo) 0-15%
                if (rand(1, 100) <= 20) { // 20% chance of promo
                    $promoDiscount = $totalAmount * (rand(5, 15) / 100);
                    $discountAmount += $promoDiscount;
                }

                $finalAmount = $totalAmount - $discountAmount;

                // Driver option
                $driverOption = $driverOptions[array_rand($driverOptions)];
                if ($driverOption === 'with_driver') {
                    // Add driver fee (50k per day)
                    $driverFee = 50000 * $totalDays;
                    $finalAmount += $driverFee;
                }

                // Special requests (20% chance)
                $specialRequests = null;
                if (rand(1, 100) <= 20) {
                    $requests = [
                        'Need baby seat',
                        'Pick up at airport',
                        'Extra insurance',
                        'GPS navigation required',
                        'Child safety seat needed',
                        'Early pickup requested',
                        'Late return requested',
                    ];
                    $specialRequests = $requests[array_rand($requests)];
                }

                // Admin notes (30% chance)
                $adminNotes = null;
                if (rand(1, 100) <= 30) {
                    $notes = [
                        'Customer has good history',
                        'VIP customer',
                        'First time renter',
                        'Requested specific car color',
                        'Corporate booking',
                        'Regular customer',
                        'Late pickup noted',
                    ];
                    $adminNotes = $notes[array_rand($notes)];
                }

                $rental = Rental::create([
                    'uuid' => Str::uuid()->toString(),
                    'customer_id' => $customer->id,
                    'car_id' => $car->id,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'total_days' => $totalDays,
                    'pickup_location_id' => $pickupLocation->id,
                    'dropoff_location_id' => $dropoffLocation->id,
                    'rental_type' => $rentalType,
                    'total_amount' => $totalAmount,
                    'discount_amount' => $discountAmount,
                    'final_amount' => $finalAmount,
                    'status' => $status,
                    'payment_status' => $paymentStatus,
                    'driver_option' => $driverOption,
                    'special_requests' => $specialRequests,
                    'admin_notes' => $adminNotes,
                ]);

                $rentalCount++;

                // Update customer profile
                $customerProfile = $customer->customerProfile;
                if ($customerProfile) {
                    if ($status === 'completed') {
                        // Update total rentals and total spent
                        $customerProfile->total_rentals += 1;
                        $customerProfile->total_spent += $finalAmount;

                        // Update membership level based on total spent
                        $totalSpent = $customerProfile->total_spent;
                        if ($totalSpent >= 25000000) {
                            $customerProfile->membership_level = 'gold';
                        } elseif ($totalSpent >= 10000000) {
                            $customerProfile->membership_level = 'silver';
                        } else {
                            $customerProfile->membership_level = 'regular';
                        }

                        // Random rating adjustment (slight variation)
                        $currentRating = $customerProfile->rating ?? 5.0;
                        $ratingChange = (rand(-10, 5) / 10); // -1.0 to +0.5
                        $newRating = max(1.0, min(5.0, $currentRating + $ratingChange));
                        $customerProfile->rating = $newRating;

                        $customerProfile->save();
                    }
                }

                // Update car status if active rental
                if ($status === 'active') {
                    $car->update(['status' => 'rented']);
                }
            }
        }

        $this->command->info("✅ Created {$rentalCount} rental records");
        $this->command->info("📊 Customer profiles updated with rental statistics");
    }
}
