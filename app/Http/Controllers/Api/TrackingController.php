<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller as BaseController;
use App\Models\Order;
use App\Http\Resources\TrackingResource; // Ensure this class exists in the specified namespace


class TrackingController extends BaseController
{
    public function getTracking($orderId)
    {
        $userId = auth()->check() ? auth()->user()->id : null;

        if (!$userId) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $order = Order::where('user_id', $userId)->findOrFail($orderId);
        
        $tracking = $order->tracking;
        
        if (!$tracking) {
            return response()->json(['message' => 'Tracking not available yet'], 404);
        }
        
        return new TrackingResource($tracking);
    }
}