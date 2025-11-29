<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\Rental;
use App\Models\Promotion;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Active rental
        $activeRental = $user->rentals()
            ->whereIn('rental_status', ['confirmed', 'active'])
            ->with(['car', 'pickupLocation', 'dropoffLocation'])
            ->first();

        // Upcoming rentals
        $upcomingRentals = $user->rentals()
            ->where('rental_status', 'confirmed')
            ->where('start_date', '>', now())
            ->with(['car', 'pickupLocation'])
            ->latest()
            ->limit(3)
            ->get();

        // Stats
        $stats = [
            'total_rentals' => $user->rentals()->count(),
            'active_rentals' => $user->rentals()->whereIn('rental_status', ['confirmed', 'active'])->count(),
            'completed_rentals' => $user->rentals()->where('rental_status', 'completed')->count(),
            'total_spent' => $user->rentals()->where('rental_status', 'completed')->sum('total_amount'),
        ];

        // Unread notifications
        $unreadNotifications = $user->notifications()->where('is_read', false)->count();

        // Recommended cars
        $recommendedCars = Car::where('availability_status', 'available')
            ->with(['category', 'location'])
            ->inRandomOrder()
            ->limit(5)
            ->get();

        // Active promotions
        $activePromotions = Promotion::where('is_active', true)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->limit(3)
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'user' => $user->load('customerProfile'),
                'active_rental' => $activeRental,
                'upcoming_rentals' => $upcomingRentals,
                'stats' => $stats,
                'unread_notifications' => $unreadNotifications,
                'recommended_cars' => $recommendedCars,
                'active_promotions' => $activePromotions,
            ]
        ]);
    }

    public function stats(Request $request)
    {
        $user = $request->user();

        $stats = [
            'total_rentals' => $user->rentals()->count(),
            'active_rentals' => $user->rentals()->whereIn('rental_status', ['confirmed', 'active'])->count(),
            'completed_rentals' => $user->rentals()->where('rental_status', 'completed')->count(),
            'cancelled_rentals' => $user->rentals()->where('rental_status', 'cancelled')->count(),
            'total_spent' => $user->rentals()->where('rental_status', 'completed')->sum('total_amount'),
            'total_days' => $user->rentals()->where('rental_status', 'completed')->sum('total_days'),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    public function activeRental(Request $request)
    {
        $activeRental = $request->user()->rentals()
            ->whereIn('rental_status', ['confirmed', 'active'])
            ->with(['car', 'pickupLocation', 'dropoffLocation', 'payment'])
            ->first();

        return response()->json([
            'success' => true,
            'data' => $activeRental
        ]);
    }

    public function upcomingRentals(Request $request)
    {
        $upcomingRentals = $request->user()->rentals()
            ->where('rental_status', 'confirmed')
            ->where('start_date', '>', now())
            ->with(['car', 'pickupLocation', 'dropoffLocation'])
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $upcomingRentals
        ]);
    }

    public function recommendedCars(Request $request)
    {
        $limit = $request->get('limit', 10);

        $user = $request->user();
        $recentRentals = $user->rentals()->with('car.category')->latest()->limit(5)->get();
        $categoryIds = $recentRentals->pluck('car.category_id')->unique();

        $query = Car::where('availability_status', 'available')
            ->with(['category', 'location']);

        if ($categoryIds->isNotEmpty()) {
            $query->whereIn('category_id', $categoryIds);
        }

        $cars = $query->inRandomOrder()->limit($limit)->get();

        return response()->json([
            'success' => true,
            'data' => $cars
        ]);
    }

    public function recentTransactions(Request $request)
    {
        $limit = $request->get('limit', 5);

        $rentals = $request->user()->rentals()
            ->with(['car', 'payment'])
            ->latest()
            ->limit($limit)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $rentals
        ]);
    }

    public function promos(Request $request)
    {
        $promotions = Promotion::where('is_active', true)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->get();

        return response()->json([
            'success' => true,
            'data' => $promotions
        ]);
    }

    public function membership(Request $request)
    {
        $user = $request->user();
        $completedRentals = $user->rentals()->where('rental_status', 'completed')->count();

        $membershipTiers = [
            ['name' => 'Bronze', 'min_rentals' => 0, 'benefits' => '5% discount'],
            ['name' => 'Silver', 'min_rentals' => 5, 'benefits' => '10% discount'],
            ['name' => 'Gold', 'min_rentals' => 10, 'benefits' => '15% discount'],
            ['name' => 'Platinum', 'min_rentals' => 20, 'benefits' => '20% discount'],
        ];

        $currentTier = 'Bronze';
        $nextTier = 'Silver';
        $rentalsToNextTier = 5;

        foreach ($membershipTiers as $index => $tier) {
            if ($completedRentals >= $tier['min_rentals']) {
                $currentTier = $tier['name'];
                if (isset($membershipTiers[$index + 1])) {
                    $nextTier = $membershipTiers[$index + 1]['name'];
                    $rentalsToNextTier = $membershipTiers[$index + 1]['min_rentals'] - $completedRentals;
                } else {
                    $nextTier = null;
                    $rentalsToNextTier = 0;
                }
            }
        }

        return response()->json([
            'success' => true,
            'data' => [
                'current_tier' => $currentTier,
                'next_tier' => $nextTier,
                'completed_rentals' => $completedRentals,
                'rentals_to_next_tier' => $rentalsToNextTier,
                'tiers' => $membershipTiers
            ]
        ]);
    }

    public function quickActions(Request $request)
    {
        $actions = [
            ['id' => 'book_car', 'label' => 'Book a Car', 'icon' => 'car', 'route' => '/cars'],
            ['id' => 'my_rentals', 'label' => 'My Rentals', 'icon' => 'list', 'route' => '/rentals'],
            ['id' => 'promotions', 'label' => 'Promotions', 'icon' => 'tag', 'route' => '/promotions'],
            ['id' => 'profile', 'label' => 'My Profile', 'icon' => 'user', 'route' => '/profile'],
        ];

        return response()->json([
            'success' => true,
            'data' => $actions
        ]);
    }

    public function rentalHistory(Request $request)
    {
        $period = $request->get('period', 30);
        $startDate = Carbon::now()->subDays($period);

        $rentals = $request->user()->rentals()
            ->where('created_at', '>=', $startDate)
            ->with(['car'])
            ->get();

        $summary = [
            'total_rentals' => $rentals->count(),
            'completed' => $rentals->where('rental_status', 'completed')->count(),
            'cancelled' => $rentals->where('rental_status', 'cancelled')->count(),
            'total_spent' => $rentals->where('rental_status', 'completed')->sum('total_amount'),
        ];

        return response()->json([
            'success' => true,
            'data' => [
                'summary' => $summary,
                'rentals' => $rentals
            ]
        ]);
    }

    public function spendingChart(Request $request)
    {
        $months = $request->get('months', 6);
        $data = [];

        for ($i = $months - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $spent = $request->user()->rentals()
                ->where('rental_status', 'completed')
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('total_amount');

            $data[] = [
                'month' => $date->format('M Y'),
                'amount' => $spent
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function popularCars(Request $request)
    {
        $locationId = $request->get('location_id');
        $limit = $request->get('limit', 5);

        $query = Car::where('availability_status', 'available')
            ->with(['category', 'location']);

        if ($locationId) {
            $query->where('location_id', $locationId);
        }

        $cars = $query->withCount('rentals')
            ->orderBy('rentals_count', 'desc')
            ->limit($limit)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $cars
        ]);
    }
}
