<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PromotionController extends Controller
{
    public function active()
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

    public function validate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string',
            'rental_amount' => 'required|numeric|min:0',
            'rental_days' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $promo = Promotion::where('code', $request->code)
            ->where('is_active', true)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->first();

        if (!$promo) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired promotion code'
            ], 404);
        }

        if ($request->rental_amount < $promo->min_rental_amount) {
            return response()->json([
                'success' => false,
                'message' => "Minimum rental amount is Rp " . number_format($promo->min_rental_amount, 0, ',', '.')
            ], 400);
        }

        if ($promo->min_rental_days && $request->rental_days < $promo->min_rental_days) {
            return response()->json([
                'success' => false,
                'message' => "Minimum rental days is {$promo->min_rental_days} days"
            ], 400);
        }

        $discount = 0;
        if ($promo->discount_type === 'percentage') {
            $discount = ($request->rental_amount * $promo->discount_value) / 100;
            if ($promo->max_discount_amount) {
                $discount = min($discount, $promo->max_discount_amount);
            }
        } else {
            $discount = $promo->discount_value;
        }

        return response()->json([
            'success' => true,
            'message' => 'Promotion code is valid',
            'data' => [
                'promotion' => $promo,
                'discount_amount' => $discount,
                'final_amount' => $request->rental_amount - $discount
            ]
        ]);
    }
}
