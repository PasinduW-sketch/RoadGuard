<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\VehicleController;
use App\Http\Controllers\API\DiagnosisController;
use App\Http\Controllers\API\EmergencyRequestController;
use App\Http\Controllers\API\SubscriptionController;
use App\Http\Controllers\API\ReviewController;

Route::middleware('api')->prefix('api')->group(function () {
    // Public routes
    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/login', [AuthController::class, 'login']);
    Route::post('/auth/forgot-password', [AuthController::class, 'forgotPassword']);

    // Subscription plans (public)
    Route::get('/subscriptions/plans', [SubscriptionController::class, 'plans']);

    // Protected routes
    Route::middleware('auth:sanctum')->group(function () {
        // Auth
        Route::post('/auth/logout', [AuthController::class, 'logout']);
        Route::get('/auth/me', [AuthController::class, 'me']);

        // Vehicles
        Route::apiResource('vehicles', VehicleController::class);

        // Diagnoses
        Route::apiResource('diagnoses', DiagnosisController::class, ['only' => ['index', 'store', 'show']]);

        // Emergency Requests
        Route::apiResource('emergency-requests', EmergencyRequestController::class);
        Route::get('/emergency-requests/nearby/providers', [EmergencyRequestController::class, 'nearbyProviders']);

        // Subscriptions
        Route::get('/subscriptions/current', [SubscriptionController::class, 'current']);
        Route::post('/subscriptions/purchase', [SubscriptionController::class, 'purchase']);
        Route::post('/subscriptions/cancel', [SubscriptionController::class, 'cancel']);
        Route::get('/subscriptions/usage', [SubscriptionController::class, 'usage']);

        // Reviews
        Route::apiResource('reviews', ReviewController::class, ['only' => ['index', 'store']]);
        Route::get('/reviews/my-review/{emergencyRequestId}', [ReviewController::class, 'userReview']);
    });
});
