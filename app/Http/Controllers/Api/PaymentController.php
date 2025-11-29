<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Rental;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'rental_id' => 'required|exists:rentals,id',
            'payment_method' => 'required|in:credit_card,debit_card,bank_transfer,ewallet,cash',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $rental = Rental::findOrFail($request->rental_id);

        if ($rental->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        if ($rental->rental_status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Rental is not in pending status'
            ], 400);
        }

        $payment = Payment::create([
            'rental_id' => $rental->id,
            'amount' => $rental->total_amount,
            'payment_method' => $request->payment_method,
            'payment_status' => 'pending',
            'transaction_id' => 'TRX-' . strtoupper(Str::random(10)),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Payment initiated successfully',
            'data' => $payment->load('rental')
        ], 201);
    }

    public function show(Request $request, $id)
    {
        $payment = Payment::with('rental')->find($id);

        if (!$payment) {
            return response()->json([
                'success' => false,
                'message' => 'Payment not found'
            ], 404);
        }

        if ($payment->rental->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => $payment
        ]);
    }
}
