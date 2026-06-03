<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    protected $fillable = [
        'user_id',
        'provider_id',
        'emergency_request_id',
        'rating',
        'comment',
        'would_recommend',
        'service_quality',
        'professionalism',
        'value_for_money',
        'is_verified_purchase',
    ];

    protected $casts = [
        'rating' => 'integer',
        'would_recommend' => 'boolean',
        'service_quality' => 'array',
        'professionalism' => 'array',
        'value_for_money' => 'array',
        'is_verified_purchase' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function provider(): BelongsTo
    {
        return $this->belongsTo(Provider::class);
    }

    public function emergencyRequest(): BelongsTo
    {
        return $this->belongsTo(EmergencyRequest::class);
    }
}
