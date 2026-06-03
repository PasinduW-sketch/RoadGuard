<?php

namespace App\Http\Controllers\API;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    /**
     * Get reviews for a provider
     */
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'provider_id' => 'required|exists:providers,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $reviews = Review::where('provider_id', $request->provider_id)
            ->with('user', 'provider', 'emergencyRequest')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return response()->json([
            'message' => 'Reviews retrieved successfully',
            'data' => $reviews,
        ]);
    }

    /**
     * Create a new review
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'provider_id' => 'required|exists:providers,id',
            'emergency_request_id' => 'required|exists:emergency_requests,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'would_recommend' => 'boolean',
            'service_quality' => 'nullable|array',
            'professionalism' => 'nullable|array',
            'value_for_money' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Check if user already reviewed this service
        $existingReview = Review::where('user_id', $request->user()->id)
            ->where('emergency_request_id', $request->emergency_request_id)
            ->first();

        if ($existingReview) {
            return response()->json([
                'message' => 'You have already reviewed this service',
            ], 409);
        }

        $review = Review::create([
            'user_id' => $request->user()->id,
            'provider_id' => $request->provider_id,
            'emergency_request_id' => $request->emergency_request_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'would_recommend' => $request->boolean('would_recommend', true),
            'service_quality' => $request->service_quality ?? [],
            'professionalism' => $request->professionalism ?? [],
            'value_for_money' => $request->value_for_money ?? [],
            'is_verified_purchase' => true,
        ]);

        // Update provider rating
        $this->updateProviderRating($request->provider_id);

        return response()->json([
            'message' => 'Review created successfully',
            'data' => $review,
        ], 201);
    }

    /**
     * Update provider average rating
     */
    private function updateProviderRating($providerId)
    {
        $averageRating = Review::where('provider_id', $providerId)
            ->average('rating');

        $provider = \App\Models\Provider::find($providerId);
        $provider->update(['rating' => round($averageRating, 2)]);
    }

    /**
     * Get user's review for a service
     */
    public function userReview(Request $request, $emergencyRequestId)
    {
        $review = Review::where('user_id', $request->user()->id)
            ->where('emergency_request_id', $emergencyRequestId)
            ->first();

        if (!$review) {
            return response()->json([
                'message' => 'Review not found',
            ], 404);
        }

        return response()->json([
            'message' => 'Review retrieved successfully',
            'data' => $review,
        ]);
    }
}
