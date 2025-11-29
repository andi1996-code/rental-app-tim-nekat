<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Rental;
use App\Models\Car;
use App\Models\Promotion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RentalController extends Controller
{
    public function preview(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'car_id' => 'required|exists:cars,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'pickup_location_id' => 'required|exists:locations,id',
            'dropoff_location_id' => 'required|exists:locations,id',
            'driver_option' => 'required|in:self_drive,with_driver',
            'promo_code' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $car = Car::findOrFail($request->car_id);

        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        $days = $startDate->diffInDays($endDate);

        $basePrice = $car->price_per_day * $days;
        $driverFee = $request->driver_option === 'with_driver' ? 150000 * $days : 0;

        $discount = 0;
        $promoDetails = null;

        if ($request->promo_code) {
            $promo = Promotion::where('code', $request->promo_code)
                ->where('is_active', true)
                ->where('start_date', '<=', now())
                ->where('end_date', '>=', now())
                ->first();

            if ($promo && $basePrice >= $promo->min_rental_amount) {
                if ($promo->discount_type === 'percentage') {
                    $discount = ($basePrice * $promo->discount_value) / 100;
                    if ($promo->max_discount_amount) {
                        $discount = min($discount, $promo->max_discount_amount);
                    }
                } else {
                    $discount = $promo->discount_value;
                }

                $promoDetails = [
                    'code' => $promo->code,
                    'name' => $promo->name,
                    'discount_type' => $promo->discount_type,
                    'discount_value' => $promo->discount_value,
                    'discount_amount' => $discount
                ];
            }
        }

        $insurance = $basePrice * 0.05; // 5% insurance
        $tax = ($basePrice + $driverFee - $discount) * 0.11; // 11% tax

        $totalAmount = $basePrice + $driverFee + $insurance + $tax - $discount;

        return response()->json([
            'success' => true,
            'data' => [
                'car' => $car,
                'rental_days' => $days,
                'breakdown' => [
                    'base_price' => $basePrice,
                    'driver_fee' => $driverFee,
                    'insurance' => $insurance,
                    'discount' => $discount,
                    'tax' => $tax,
                    'total' => $totalAmount
                ],
                'promotion' => $promoDetails
            ]
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'car_id' => 'required|exists:cars,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'pickup_location_id' => 'required|exists:locations,id',
            'dropoff_location_id' => 'required|exists:locations,id',
            'driver_option' => 'required|in:self_drive,with_driver',
            'special_requests' => 'nullable|string',
            'promo_code' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $car = Car::findOrFail($request->car_id);

        if ($car->availability_status !== 'available') {
            return response()->json([
                'success' => false,
                'message' => 'Car is not available'
            ], 400);
        }

        try {
            DB::beginTransaction();

            $startDate = Carbon::parse($request->start_date);
            $endDate = Carbon::parse($request->end_date);
            $days = $startDate->diffInDays($endDate);

            $basePrice = $car->price_per_day * $days;
            $driverFee = $request->driver_option === 'with_driver' ? 150000 * $days : 0;

            $discount = 0;
            $promotionId = null;

            if ($request->promo_code) {
                $promo = Promotion::where('code', $request->promo_code)
                    ->where('is_active', true)
                    ->where('start_date', '<=', now())
                    ->where('end_date', '>=', now())
                    ->first();

                if ($promo && $basePrice >= $promo->min_rental_amount) {
                    if ($promo->discount_type === 'percentage') {
                        $discount = ($basePrice * $promo->discount_value) / 100;
                        if ($promo->max_discount_amount) {
                            $discount = min($discount, $promo->max_discount_amount);
                        }
                    } else {
                        $discount = $promo->discount_value;
                    }
                    $promotionId = $promo->id;
                }
            }

            $insurance = $basePrice * 0.05;
            $tax = ($basePrice + $driverFee - $discount) * 0.11;
            $totalAmount = $basePrice + $driverFee + $insurance + $tax - $discount;

            $rental = Rental::create([
                'user_id' => $request->user()->id,
                'car_id' => $car->id,
                'promotion_id' => $promotionId,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'pickup_location_id' => $request->pickup_location_id,
                'dropoff_location_id' => $request->dropoff_location_id,
                'driver_option' => $request->driver_option,
                'special_requests' => $request->special_requests,
                'rental_status' => 'pending',
                'total_days' => $days,
                'base_price' => $basePrice,
                'driver_fee' => $driverFee,
                'insurance_fee' => $insurance,
                'discount_amount' => $discount,
                'tax_amount' => $tax,
                'total_amount' => $totalAmount,
            ]);

            $car->update(['availability_status' => 'rented']);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Rental created successfully',
                'data' => $rental->load(['car', 'pickupLocation', 'dropoffLocation'])
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create rental',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function index(Request $request)
    {
        $query = $request->user()->rentals()->with(['car', 'pickupLocation', 'dropoffLocation']);

        if ($request->has('status')) {
            $query->where('rental_status', $request->status);
        }

        $rentals = $query->latest()->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $rentals
        ]);
    }

    public function show(Request $request, $uuid)
    {
        $rental = $request->user()->rentals()
            ->where('uuid', $uuid)
            ->with(['car', 'pickupLocation', 'dropoffLocation', 'payment'])
            ->first();

        if (!$rental) {
            return response()->json([
                'success' => false,
                'message' => 'Rental not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $rental
        ]);
    }

    public function cancel(Request $request, $uuid)
    {
        $rental = $request->user()->rentals()
            ->where('uuid', $uuid)
            ->first();

        if (!$rental) {
            return response()->json([
                'success' => false,
                'message' => 'Rental not found'
            ], 404);
        }

        if (!in_array($rental->rental_status, ['pending', 'confirmed'])) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot cancel this rental'
            ], 400);
        }

        $rental->update(['rental_status' => 'cancelled']);
        $rental->car->update(['availability_status' => 'available']);

        return response()->json([
            'success' => true,
            'message' => 'Rental cancelled successfully',
            'data' => $rental
        ]);
    }
}
