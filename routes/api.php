<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\LocationController;
use App\Http\Controllers\Api\CarCategoryController;
use App\Http\Controllers\Api\CarController;
use App\Http\Controllers\Api\RentalController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\PromotionController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\DashboardController;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Public - Locations & Categories
Route::get('/locations', [LocationController::class, 'index']);
Route::get('/locations/{id}', [LocationController::class, 'show']);
Route::get('/car-categories', [CarCategoryController::class, 'index']);
Route::get('/car-categories/{id}', [CarCategoryController::class, 'show']);

// Public - Cars
Route::get('/cars', [CarController::class, 'index']);
Route::get('/cars/{id}', [CarController::class, 'show']);

// Public - Promotions
Route::get('/promotions/active', [PromotionController::class, 'active']);
Route::post('/promotions/validate', [PromotionController::class, 'validate']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Profile
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::put('/profile', [ProfileController::class, 'update']);
    Route::put('/profile/address', [ProfileController::class, 'updateAddress']);
    Route::put('/profile/emergency-contact', [ProfileController::class, 'updateEmergencyContact']);

    // Cars - Authenticated (must be before rentals to avoid route conflict)
    Route::get('/cars/recommended', [CarController::class, 'recommended']);

    // Rentals
    Route::post('/rentals/preview', [RentalController::class, 'preview']);
    Route::post('/rentals', [RentalController::class, 'store']);
    Route::get('/rentals', [RentalController::class, 'index']);
    Route::get('/rentals/{uuid}', [RentalController::class, 'show']);
    Route::post('/rentals/{uuid}/cancel', [RentalController::class, 'cancel']);

    // Payments
    Route::post('/payments', [PaymentController::class, 'store']);
    Route::get('/payments/{id}', [PaymentController::class, 'show']);

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount']);
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead']);

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/dashboard/stats', [DashboardController::class, 'stats']);
    Route::get('/dashboard/active-rental', [DashboardController::class, 'activeRental']);
    Route::get('/dashboard/upcoming-rentals', [DashboardController::class, 'upcomingRentals']);
    Route::get('/dashboard/recommended-cars', [DashboardController::class, 'recommendedCars']);
    Route::get('/dashboard/recent-transactions', [DashboardController::class, 'recentTransactions']);
    Route::get('/dashboard/promos', [DashboardController::class, 'promos']);
    Route::get('/dashboard/membership', [DashboardController::class, 'membership']);
    Route::get('/dashboard/quick-actions', [DashboardController::class, 'quickActions']);
    Route::get('/dashboard/rental-history', [DashboardController::class, 'rentalHistory']);
    Route::get('/dashboard/spending-chart', [DashboardController::class, 'spendingChart']);
    Route::get('/dashboard/popular-cars', [DashboardController::class, 'popularCars']);
});
