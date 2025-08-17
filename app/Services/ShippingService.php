<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ShippingService
{
    protected string $apiKey;
    protected string $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.shipping.api_key');
        $this->baseUrl = config('services.shipping.base_url');
    }

    /**
     * Create shipment for an order
     *
     * @param Order $order
     * @return string Tracking number
     * @throws \Exception
     */
    public function createShipment(Order $order): string
    {
        try {
            // Validate order
            if (!$order->payment_status === 'paid') {
                throw new \Exception("Order must be paid before shipping");
            }

            // Prepare shipment data
            $shipmentData = [
                'order_id' => $order->id,
                'customer' => [
                    'name' => $order->customer->name,
                    'address' => $order->shipping_address,
                    'email' => $order->customer->email,
                    'phone' => $order->customer->phone,
                ],
                'items' => $order->items->map(function ($item) {
                    return [
                        'product_id' => $item->product_id,
                        'quantity' => $item->quantity,
                        'weight' => $item->product->weight,
                        'dimensions' => $item->product->dimensions,
                    ];
                })->toArray(),
                'carrier' => $order->shipping_method,
            ];

            // Create shipment via API
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post("{$this->baseUrl}/shipments", $shipmentData);

            if ($response->failed()) {
                throw new \Exception("Shipping API error: " . $response->body());
            }

            $trackingNumber = $response->json()['tracking_number'];

            // Update order with tracking info
            $order->update([
                'shipping_status' => 'processing',
                'tracking_number' => $trackingNumber
            ]);

            return $trackingNumber;

        } catch (\Exception $e) {
            Log::error('Shipping failed for order ' . $order->id, [
                'error' => $e->getMessage(),
                'order' => $order->toArray()
            ]);
            throw $e;
        }
    }
}