<?php

namespace App\Http\Controllers\API;

use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubscriptionController extends Controller
{
    /**
     * Get all subscription plans
     */
    public function plans()
    {
        $plans = [
            [
                'id' => 'free',
                'name' => 'Free Plan',
                'price' => 0,
                'billing_period' => 'monthly',
                'features' => [
                    'max_requests' => 2,
                    'priority_dispatch' => false,
                    'advanced_diagnostics' => false,
                    'maintenance_reminders' => false,
                    'fleet_management' => false,
                    'max_vehicles' => 1,
                ],
            ],
            [
                'id' => 'premium',
                'name' => 'Premium Plan',
                'price' => 9.99,
                'billing_period' => 'monthly',
                'features' => [
                    'max_requests' => null, // unlimited
                    'priority_dispatch' => true,
                    'advanced_diagnostics' => true,
                    'maintenance_reminders' => true,
                    'fleet_management' => false,
                    'max_vehicles' => 3,
                ],
            ],
            [
                'id' => 'enterprise',
                'name' => 'Enterprise Plan',
                'price' => 29.99,
                'billing_period' => 'monthly',
                'features' => [
                    'max_requests' => null, // unlimited
                    'priority_dispatch' => true,
                    'advanced_diagnostics' => true,
                    'maintenance_reminders' => true,
                    'fleet_management' => true,
                    'max_vehicles' => null, // unlimited
                ],
            ],
        ];

        return response()->json([
            'message' => 'Subscription plans retrieved successfully',
            'data' => $plans,
        ]);
    }

    /**
     * Get current user subscription
     */
    public function current(Request $request)
    {
        $subscription = $request->user()->subscriptions()->latest()->first();

        if (!$subscription) {
            // Create default free subscription
            $subscription = Subscription::create([
                'user_id' => $request->user()->id,
                'plan_name' => 'free',
                'price' => 0,
                'billing_period' => 'monthly',
                'max_requests' => 2,
                'current_requests' => 0,
                'max_vehicles' => 1,
                'is_active' => true,
            ]);
        }

        return response()->json([
            'message' => 'Current subscription retrieved successfully',
            'data' => $subscription,
        ]);
    }

    /**
     * Purchase a subscription plan
     */
    public function purchase(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'plan_name' => 'required|in:free,premium,enterprise',
            'billing_period' => 'required|in:monthly,yearly',
            'payment_method' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // TODO: Process Stripe payment
        // TODO: Create subscription record

        return response()->json([
            'message' => 'Subscription purchased successfully',
            'data' => [],
        ], 201);
    }

    /**
     * Cancel subscription
     */
    public function cancel(Request $request)
    {
        $subscription = $request->user()->subscriptions()->latest()->first();

        if (!$subscription) {
            return response()->json([
                'message' => 'No active subscription found',
            ], 404);
        }

        $subscription->update(['is_active' => false]);

        return response()->json([
            'message' => 'Subscription cancelled successfully',
        ]);
    }

    /**
     * Get subscription usage
     */
    public function usage(Request $request)
    {
        $subscription = $request->user()->subscriptions()->latest()->first();

        if (!$subscription) {
            return response()->json([
                'message' => 'No active subscription found',
            ], 404);
        }

        return response()->json([
            'message' => 'Subscription usage retrieved successfully',
            'data' => [
                'current_requests' => $subscription->current_requests,
                'max_requests' => $subscription->max_requests,
                'requests_remaining' => $subscription->max_requests ? ($subscription->max_requests - $subscription->current_requests) : null,
                'vehicles_used' => $request->user()->vehicles()->count(),
                'max_vehicles' => $subscription->max_vehicles,
                'expires_at' => $subscription->expires_at,
            ],
        ]);
    }
}
