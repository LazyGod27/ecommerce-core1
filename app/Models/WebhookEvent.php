<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebhookEvent extends Model
{
    protected $fillable = [
        'endpoint_id',
        'event',
        'payload',
        'response_status',
        'response_body',
        'attempts',
        'last_attempt_at',
        'status'
    ];

    protected $casts = [
        'payload' => 'array',
        'response_body' => 'array',
        'attempts' => 'integer',
        'last_attempt_at' => 'datetime'
    ];

    /**
     * Get the webhook endpoint
     */
    public function endpoint()
    {
        return $this->belongsTo(WebhookEndpoint::class);
    }

    /**
     * Scope for pending events
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for failed events
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Scope for successful events
     */
    public function scopeSuccessful($query)
    {
        return $query->where('status', 'successful');
    }
}