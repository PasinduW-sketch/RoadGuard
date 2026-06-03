<?php

return [
    'gemini' => [
        'key' => env('GEMINI_API_KEY'),
        'model' => 'gemini-pro',
    ],

    'google_maps' => [
        'key' => env('GOOGLE_MAPS_API_KEY'),
    ],

    'stripe' => [
        'public_key' => env('STRIPE_PUBLIC_KEY'),
        'secret_key' => env('STRIPE_SECRET_KEY'),
        'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
    ],

    'subscription_plans' => [
        'free' => [
            'max_requests' => 2,
            'priority_dispatch' => false,
            'advanced_diagnostics' => false,
            'maintenance_reminders' => false,
            'fleet_management' => false,
            'max_vehicles' => 1,
            'price' => 0,
        ],
        'premium' => [
            'max_requests' => null,
            'priority_dispatch' => true,
            'advanced_diagnostics' => true,
            'maintenance_reminders' => true,
            'fleet_management' => false,
            'max_vehicles' => 3,
            'price' => 9.99,
        ],
        'enterprise' => [
            'max_requests' => null,
            'priority_dispatch' => true,
            'advanced_diagnostics' => true,
            'maintenance_reminders' => true,
            'fleet_management' => true,
            'max_vehicles' => null,
            'price' => 29.99,
        ],
    ],

    'emergency_request_radius' => 10, // km
];
