<?php

namespace App\Http\Controllers\API;

use App\Models\Diagnosis;
use App\Services\AIService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DiagnosisController extends Controller
{
    private $aiService;

    public function __construct(AIService $aiService)
    {
        $this->aiService = $aiService;
    }

    /**
     * Get diagnosis history for user
     */
    public function index(Request $request)
    {
        $diagnoses = $request->user()
            ->diagnoses()
            ->with('vehicle')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return response()->json([
            'message' => 'Diagnoses retrieved successfully',
            'data' => $diagnoses,
        ]);
    }

    /**
     * Create new diagnosis
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vehicle_id' => 'required|exists:vehicles,id',
            'symptoms' => 'required|string|min:10',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $vehicle = $request->user()->vehicles()->findOrFail($request->vehicle_id);

        try {
            // Call AI Service to analyze symptoms
            $aiResponse = $this->aiService->analyzeSymptons(
                $vehicle,
                $request->symptoms
            );

            $diagnosis = Diagnosis::create([
                'user_id' => $request->user()->id,
                'vehicle_id' => $vehicle->id,
                'symptoms' => $request->symptoms,
                'ai_response' => $aiResponse['response'],
                'possible_causes' => $aiResponse['causes'],
                'suggestions' => $aiResponse['suggestions'],
                'severity_level' => $aiResponse['severity'],
                'requires_professional' => $aiResponse['professional_needed'],
                'ai_model' => $aiResponse['model'],
                'tokens_used' => $aiResponse['tokens_used'] ?? 0,
            ]);

            return response()->json([
                'message' => 'Diagnosis created successfully',
                'data' => $diagnosis,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error creating diagnosis',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get specific diagnosis
     */
    public function show(Diagnosis $diagnosis)
    {
        return response()->json([
            'message' => 'Diagnosis retrieved successfully',
            'data' => $diagnosis->load('vehicle', 'user'),
        ]);
    }
}
