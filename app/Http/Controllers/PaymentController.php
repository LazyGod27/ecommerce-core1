<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Services\GcashService;

class PaymentController extends Controller
{
    public function initiateGcash(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id'
        ]);

        $order = Order::findOrFail($request->order_id);
        
        try {
            $gcashService = app(GcashService::class);
            $redirectUrl = $gcashService->createPayment(
                $order, 
                $request->callback_url ?? route('order.complete', $order->id)
            );
            
            return redirect($redirectUrl);
            
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to initiate GCash payment: ' . $e->getMessage());
        }
    }

    public function gcashWebhook(Request $request)
    {
        // Handle GCash webhook for payment confirmation
        $payload = $request->all();
        
        // Verify webhook signature and process payment
        // This would integrate with your GCash service
        
        return response()->json(['status' => 'success']);
    }
}
