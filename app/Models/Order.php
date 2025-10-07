<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'order_number',
        'status',
        'shipping_address',
        'contact_number',
        'email',
        'payment_method',
        'payment_status',
        'payment_id',
        'subtotal',
        'tax',
        'shipping_cost',
        'total',
        'notes',
        'paid_at',
        'delivered_at',
        'delivery_confirmation_deadline',
        'delivery_status',
        'customer_response_at',
        'return_reason',
        'return_status',
        'return_requested_at',
        'return_processed_at',
        'refunded_at',
        'refund_reason'
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'total' => 'decimal:2',
        'paid_at' => 'datetime',
        'delivered_at' => 'datetime',
        'delivery_confirmation_deadline' => 'datetime',
        'customer_response_at' => 'datetime',
        'return_requested_at' => 'datetime',
        'return_processed_at' => 'datetime',
        'refunded_at' => 'datetime'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function tracking()
    {
        // Each order has a single tracking record
        return $this->hasOne(Tracking::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeProcessing($query)
    {
        return $query->where('status', 'processing');
    }

    public function scopeShipped($query)
    {
        return $query->where('status', 'shipped');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    public function scopeRefunded($query)
    {
        return $query->where('status', 'refunded');
    }

    public function scopePaid($query)
    {
        return $query->where('payment_status', 'paid');
    }

    public function scopeUnpaid($query)
    {
        return $query->where('payment_status', 'pending');
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isProcessing(): bool
    {
        return $this->status === 'processing';
    }

    public function isShipped(): bool
    {
        return $this->status === 'shipped';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    public function isRefunded(): bool
    {
        return $this->status === 'refunded';
    }

    public function isPaid(): bool
    {
        return $this->payment_status === 'paid';
    }

    public function isUnpaid(): bool
    {
        return $this->payment_status === 'pending';
    }

    public function canBeCancelled(): bool
    {
        return in_array($this->status, ['pending', 'processing']) && !$this->isPaid();
    }

    public function canBeEdited(): bool
    {
        // Can only edit if order is pending (not yet processed)
        return $this->status === 'pending';
    }

    public function canBeRefunded(): bool
    {
        return $this->isPaid() && !in_array($this->status, ['cancelled', 'refunded']);
    }

    public function getStatusBadgeClass(): string
    {
        return match($this->status) {
            'pending' => 'bg-yellow-100 text-yellow-800',
            'processing' => 'bg-blue-100 text-blue-800',
            'shipped' => 'bg-purple-100 text-purple-800',
            'completed' => 'bg-green-100 text-green-800',
            'cancelled' => 'bg-red-100 text-red-800',
            'refunded' => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    public function getPaymentStatusBadgeClass(): string
    {
        return match($this->payment_status) {
            'pending' => 'bg-yellow-100 text-yellow-800',
            'paid' => 'bg-green-100 text-green-800',
            'failed' => 'bg-red-100 text-red-800',
            'refunded' => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    /**
     * Mark order as delivered and set confirmation deadline
     */
    public function markAsDelivered(): void
    {
        $this->update([
            'status' => 'delivered',
            'delivered_at' => now(),
            'delivery_status' => 'delivered',
            'delivery_confirmation_deadline' => now()->addDays(7)
        ]);
    }

    /**
     * Customer confirms item received
     */
    public function confirmReceived(): void
    {
        $this->update([
            'delivery_status' => 'confirmed_received',
            'customer_response_at' => now(),
            'status' => 'completed'
        ]);
    }

    /**
     * Customer requests return/refund
     */
    public function requestReturn(string $reason): void
    {
        $this->update([
            'delivery_status' => 'return_requested',
            'return_status' => 'requested',
            'return_reason' => $reason,
            'return_requested_at' => now(),
            'customer_response_at' => now()
        ]);
    }

    /**
     * Auto-confirm order after deadline
     */
    public function autoConfirm(): void
    {
        $this->update([
            'delivery_status' => 'auto_confirmed',
            'status' => 'completed',
            'customer_response_at' => now()
        ]);
    }

    /**
     * Check if order is waiting for customer response
     */
    public function isWaitingForCustomerResponse(): bool
    {
        return $this->delivery_status === 'delivered' && 
               $this->delivery_confirmation_deadline && 
               $this->delivery_confirmation_deadline->isFuture();
    }

    /**
     * Check if order deadline has passed
     */
    public function hasDeadlinePassed(): bool
    {
        return $this->delivery_confirmation_deadline && 
               $this->delivery_confirmation_deadline->isPast() &&
               $this->delivery_status === 'delivered';
    }

    /**
     * Get delivery status badge class
     */
    public function getDeliveryStatusBadgeClass(): string
    {
        return match($this->delivery_status) {
            'pending' => 'bg-gray-100 text-gray-800',
            'delivered' => 'bg-blue-100 text-blue-800',
            'confirmed_received' => 'bg-green-100 text-green-800',
            'return_requested' => 'bg-yellow-100 text-yellow-800',
            'auto_confirmed' => 'bg-purple-100 text-purple-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    /**
     * Get return status badge class
     */
    public function getReturnStatusBadgeClass(): string
    {
        return match($this->return_status) {
            'none' => 'bg-gray-100 text-gray-800',
            'requested' => 'bg-yellow-100 text-yellow-800',
            'approved' => 'bg-blue-100 text-blue-800',
            'rejected' => 'bg-red-100 text-red-800',
            'completed' => 'bg-green-100 text-green-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    /**
     * Scope for orders waiting for customer response
     */
    public function scopeWaitingForResponse($query)
    {
        return $query->where('delivery_status', 'delivered')
                    ->where('delivery_confirmation_deadline', '>', now());
    }

    /**
     * Scope for orders with passed deadline
     */
    public function scopeDeadlinePassed($query)
    {
        return $query->where('delivery_status', 'delivered')
                    ->where('delivery_confirmation_deadline', '<', now());
    }

    /**
     * Scope for orders with return requests
     */
    public function scopeWithReturnRequests($query)
    {
        return $query->where('return_status', '!=', 'none');
    }
}
