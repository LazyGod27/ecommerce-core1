<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebhookEndpoint extends Model
{
    protected $fillable = [
        'name',
        'url',
        'events',
        'secret',
        'is_active',
        'description',
        'retry_count',
        'timeout',
        'headers'
    ];

    protected $casts = [
        'events' => 'array',
        'is_active' => 'boolean',
        'headers' => 'array',
        'retry_count' => 'integer',
        'timeout' => 'integer'
    ];

    protected $hidden = [
        'secret'
    ];

    /**
     * Get webhook events for this endpoint
     */
    public function webhookEvents()
    {
        return $this->hasMany(WebhookEvent::class);
    }

    /**
     * Check if endpoint supports event
     */
    public function supportsEvent($event)
    {
        return in_array($event, $this->events) || in_array('*', $this->events);
    }

    /**
     * Scope for active endpoints
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}