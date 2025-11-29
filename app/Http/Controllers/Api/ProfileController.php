<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        $profile = $request->user()->customerProfile;

        return response()->json([
            'success' => true,
            'data' => $profile
        ]);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'full_name' => 'sometimes|string|max:255',
            'phone_number' => 'sometimes|string|max:20',
            'date_of_birth' => 'sometimes|date',
            'driver_license_number' => 'sometimes|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $profile = $request->user()->customerProfile;
        $profile->update($request->only([
            'full_name',
            'phone_number',
            'date_of_birth',
            'driver_license_number'
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'data' => $profile
        ]);
    }

    public function updateAddress(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'province' => 'required|string|max:100',
            'postal_code' => 'required|string|max:10',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $profile = $request->user()->customerProfile;
        $profile->update($request->only([
            'address',
            'city',
            'province',
            'postal_code'
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Address updated successfully',
            'data' => $profile
        ]);
    }

    public function updateEmergencyContact(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'emergency_contact_name' => 'required|string|max:255',
            'emergency_contact_phone' => 'required|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $profile = $request->user()->customerProfile;
        $profile->update($request->only([
            'emergency_contact_name',
            'emergency_contact_phone'
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Emergency contact updated successfully',
            'data' => $profile
        ]);
    }
}
