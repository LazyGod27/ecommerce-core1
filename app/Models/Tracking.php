<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tracking extends Model
{
    protected $fillable = [
        'order_id',
        'tracking_number',
        'carrier',
        'carrier_code',
        'status',
        'status_description',
        'estimated_delivery',
        'actual_delivery',
        'shipped_at',
        'history',
        'shipping_address',
        'delivery_attempts',
        'delivery_notes',
        'signature_required',
        'signature_received',
        'delivery_photo',
        'weight',
        'dimensions',
        'shipping_cost',
        'insurance_amount',
        'special_instructions',
        'return_tracking_number',
        'is_returned',
        'return_reason',
        'updated_by',
        'last_updated_at'
    ];

    protected $casts = [
        'estimated_delivery' => 'datetime',
        'actual_delivery' => 'datetime',
        'shipped_at' => 'datetime',
        'last_updated_at' => 'datetime',
        'history' => 'array',
        'shipping_address' => 'array',
        'dimensions' => 'array',
        'signature_required' => 'boolean',
        'signature_received' => 'boolean',
        'is_returned' => 'boolean',
        'shipping_cost' => 'decimal:2',
        'insurance_amount' => 'decimal:2',
        'weight' => 'decimal:2',
        'delivery_attempts' => 'integer'
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function scopeActive($query)
    {
        return $query->whereNotIn('status', ['delivered', 'cancelled']);
    }

    public function scopeDelivered($query)
    {
        return $query->where('status', 'delivered');
    }

    public function scopeByCarrier($query, $carrier)
    {
        return $query->where('carrier', $carrier);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeInTransit($query)
    {
        return $query->whereIn('status', ['shipped', 'in_transit', 'out_for_delivery']);
    }

    public function scopePendingDelivery($query)
    {
        return $query->whereIn('status', ['shipped', 'in_transit', 'out_for_delivery', 'delivery_attempted']);
    }

    public function addHistoryEntry($status, $description, $location = null, $timestamp = null)
    {
        $history = $this->history ?? [];
        $history[] = [
            'status' => $status,
            'description' => $description,
            'location' => $location,
            'timestamp' => $timestamp ?? now()->toISOString()
        ];

        $this->update([
            'history' => $history,
            'last_updated_at' => now()
        ]);
    }

    public function updateStatus($status, $description = null, $location = null)
    {
        $this->update([
            'status' => $status,
            'status_description' => $description,
            'last_updated_at' => now()
        ]);

        $this->addHistoryEntry($status, $description, $location);

        // Update order status based on tracking status
        $this->updateOrderStatus($status);
    }

    private function updateOrderStatus($trackingStatus)
    {
        $orderStatusMap = [
            'shipped' => 'shipped',
            'in_transit' => 'shipped',
            'out_for_delivery' => 'shipped',
            'delivered' => 'completed',
            'delivery_attempted' => 'shipped',
            'returned' => 'returned',
            'cancelled' => 'cancelled'
        ];

        if (isset($orderStatusMap[$trackingStatus])) {
            $this->order->update(['status' => $orderStatusMap[$trackingStatus]]);
        }
    }
}
