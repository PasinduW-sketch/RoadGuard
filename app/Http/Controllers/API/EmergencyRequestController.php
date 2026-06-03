<?php

namespace App\Http\Controllers\API;

use App\Models\EmergencyRequest;
use App\Models\Provider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class EmergencyRequestController extends Controller
{
    /**
     * Get all emergency requests for user
     */
    public function index(Request $request)
    {
        if ($request->user()->isProvider()) {
            $requests = Provider::where('user_id', $request->user()->id)
                ->first()
                ->emergencyRequests()
                ->with('user', 'vehicle')
                ->orderBy('created_at', 'desc')
                ->paginate(15);
        } else {
            $requests = $request->user()
                ->emergencyRequests()
                ->with('vehicle', 'provider')
                ->orderBy('created_at', 'desc')
                ->paginate(15);
        }

        return response()->json([
            'message' => 'Emergency requests retrieved successfully',
            'data' => $requests,
        ]);
    }

    /**
     * Create SOS emergency request
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vehicle_id' => 'required|exists:vehicles,id',
            'issue_title' => 'required|string|max:255',
            'issue_description' => 'required|string|min:10',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'location_address' => 'nullable|string',
            'photos' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $vehicle = $request->user()->vehicles()->findOrFail($request->vehicle_id);

        $emergencyRequest = EmergencyRequest::create([
            'user_id' => $request->user()->id,
            'vehicle_id' => $vehicle->id,
            'ticket_number' => 'RG' . date('YmdHis') . Str::random(4),
            'issue_title' => $request->issue_title,
            'issue_description' => $request->issue_description,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'location_address' => $request->location_address,
            'photos' => $request->photos ?? [],
            'status' => 'pending',
            'is_emergency' => true,
        ]);

        // TODO: Notify nearby providers

        return response()->json([
            'message' => 'Emergency request created successfully',
            'data' => $emergencyRequest,
        ], 201);
    }

    /**
     * Get specific emergency request
     */
    public function show(EmergencyRequest $emergencyRequest)
    {
        return response()->json([
            'message' => 'Emergency request retrieved successfully',
            'data' => $emergencyRequest->load('user', 'vehicle', 'provider'),
        ]);
    }

    /**
     * Update emergency request status
     */
    public function update(Request $request, EmergencyRequest $emergencyRequest)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,accepted,on_the_way,arrived,completed,cancelled',
            'completion_notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $oldStatus = $emergencyRequest->status;
        $newStatus = $request->status;

        if ($oldStatus === 'pending' && $newStatus === 'accepted') {
            $emergencyRequest->update([
                'provider_id' => $request->user()->provider->id,
                'status' => $newStatus,
                'accepted_at' => now(),
            ]);
        } elseif ($newStatus === 'on_the_way') {
            $emergencyRequest->update([
                'status' => $newStatus,
                'started_at' => now(),
            ]);
        } elseif ($newStatus === 'completed') {
            $emergencyRequest->update([
                'status' => $newStatus,
                'completed_at' => now(),
                'completion_notes' => $request->completion_notes,
            ]);
        } else {
            $emergencyRequest->update(['status' => $newStatus]);
        }

        // TODO: Send notifications

        return response()->json([
            'message' => 'Emergency request updated successfully',
            'data' => $emergencyRequest,
        ]);
    }

    /**
     * Get nearby providers
     */
    public function nearbyProviders(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'radius' => 'nullable|numeric|min:1|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $radius = $request->radius ?? 10;
        $latitude = $request->latitude;
        $longitude = $request->longitude;

        // TODO: Use Google Maps or Haversine formula to find nearby providers

        $providers = Provider::where('is_active', true)
            ->where('is_verified', true)
            ->get();

        return response()->json([
            'message' => 'Nearby providers retrieved successfully',
            'data' => $providers,
        ]);
    }
}
