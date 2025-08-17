<?php

namespace App\Http\Controllers;

use App\Events\OrderCreated;
use App\Exceptions\PaymentFailedException;
use App\Http\Requests\OrderRequest;
use App\Http\Resources\OrderResource;
use App\Http\Resources\PaymentResource;
use App\Http\Resources\TrackingResource;
use App\Models\Order;
use App\Services\PaymentService;
use App\Services\ShippingService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    protected $paymentService;
    protected $shippingService;

    public function __construct(PaymentService $paymentService, ShippingService $shippingService)
    {
        $this->paymentService = $paymentService;
        $this->shippingService = $shippingService;
    }

    public function store(OrderRequest $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                // 1. Create order
                $order = $this->createOrder($request);
                
                // 2. Process payment
                $payment = $this->processPayment($order, $request);
                
                // 3. Create shipment
                $tracking = $this->createShipment($order);
                
                // 4. Finalize order
                $this->finalizeOrder($order);
                
                return response()->json([
                    'order' => new OrderResource($order),
                    'tracking' => new TrackingResource($tracking),
                    'payment' => new PaymentResource($payment),
                ], 201);
            });
        } catch (PaymentFailedException $e) {
            return $this->handlePaymentFailure($e, $order ?? null);
        } catch (\Exception $e) {
            return $this->handleOrderFailure($e);
        }
    }

    protected function createOrder($request): Order
    {
        $order = Order::create([
            'user_id' => auth()->id(),
            'total_amount' => $request->total_amount,
            'status' => 'pending',
            'shipping_address' => $request->shipping_address,
            'billing_address' => $request->billing_address,
        ]);

        foreach ($request->items as $item) {
            $order->items()->create([
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'options' => $item['options'] ?? null,
            ]);
        }

        return $order;
    }

    protected function processPayment(Order $order, $request)
    {
        $payment = $this->paymentService->process(
            $order->id,
            $request->payment_method,
            $order->total_amount
        );

        if (!$payment->success) {
            throw new PaymentFailedException('Payment processing failed');
        }

        return $payment;
    }

    protected function createShipment(Order $order)
    {
        return $this->shippingService->createShipment($order);
    }

    protected function finalizeOrder(Order $order)
    {
        $order->update(['status' => 'processing']);
        event(new OrderCreated($order));
    }

    protected function handlePaymentFailure(\Exception $e, ?Order $order)
    {
        if ($order) {
            $order->update(['status' => 'payment_failed']);
        }

        return response()->json([
            'message' => $e->getMessage(),
            'order_id' => $order->id ?? null,
        ], 422);
    }

    protected function handleOrderFailure(\Exception $e)
    {
        Log::error('Order processing failed: ' . $e->getMessage(), [
            'exception' => $e,
            'trace' => $e->getTraceAsString()
        ]);

        return response()->json([
            'message' => 'Order processing failed. Please try again.',
        ], 500);
    }
}