<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Car;
use Illuminate\Http\Request;

class CarController extends Controller
{
    public function index(Request $request)
    {
        $query = Car::with(['category', 'location'])
            ->where('availability_status', 'available');

        if ($request->has('location_id')) {
            $query->where('location_id', $request->location_id);
        }

        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('seat_capacity_min')) {
            $query->where('seat_capacity', '>=', $request->seat_capacity_min);
        }

        if ($request->has('seat_capacity_max')) {
            $query->where('seat_capacity', '<=', $request->seat_capacity_max);
        }

        if ($request->has('price_min')) {
            $query->where('price_per_day', '>=', $request->price_min);
        }

        if ($request->has('price_max')) {
            $query->where('price_per_day', '<=', $request->price_max);
        }

        $cars = $query->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $cars
        ]);
    }

    public function show($id)
    {
        $car = Car::with(['category', 'location'])->find($id);

        if (!$car) {
            return response()->json([
                'success' => false,
                'message' => 'Car not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $car
        ]);
    }

    public function recommended(Request $request)
    {
        $user = $request->user();

        // Get user's rental history to recommend similar cars
        $recentRentals = $user->rentals()
            ->with('car.category')
            ->latest()
            ->limit(5)
            ->get();

        $categoryIds = $recentRentals->pluck('car.category_id')->unique();

        $query = Car::with(['category', 'location'])
            ->where('availability_status', 'available');

        if ($categoryIds->isNotEmpty()) {
            $query->whereIn('category_id', $categoryIds);
        }

        $cars = $query->inRandomOrder()->limit(10)->get();

        return response()->json([
            'success' => true,
            'data' => $cars
        ]);
    }
}
