<?php

namespace App\Services;

use App\Models\Tracking;
use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ShippingService
{
    /**
     * Supported shipping providers
     */
    private $providers = [
        'jnt' => [
            'name' => 'J&T Express',
            'api_url' => 'https://api.jtexpress.com',
            'tracking_url' => 'https://www.jtexpress.com/tracking'
        ],
        'ninjavan' => [
            'name' => 'Ninja Van',
            'api_url' => 'https://api.ninjavan.co',
            'tracking_url' => 'https://www.ninjavan.co/tracking'
        ],
        'lbc' => [
            'name' => 'LBC Express',
            'api_url' => 'https://api.lbcexpress.com',
            'tracking_url' => 'https://www.lbcexpress.com/tracking'
        ],
        'flash' => [
            'name' => 'Flash Express',
            'api_url' => 'https://api.flashexpress.com',
            'tracking_url' => 'https://www.flashexpress.com/tracking'
        ]
    ];

    /**
     * Create shipment with shipping provider
     */
    public function createShipment(Order $order, array $shippingData): array
    {
        $carrier = $shippingData['carrier'] ?? 'jnt';
        
        if (!isset($this->providers[$carrier])) {
            throw new \Exception("Unsupported shipping provider: $carrier");
        }

        try {
            $provider = $this->providers[$carrier];
            
            // Prepare shipment data
            $shipmentData = $this->prepareShipmentData($order, $shippingData);
            
            // Send request to shipping provider
            $response = Http::timeout(30)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . config("shipping.{$carrier}.api_key")
                ])
                ->post($provider['api_url'] . '/shipments', $shipmentData);

            if ($response->successful()) {
                $responseData = $response->json();
                
                // Create tracking record
                $tracking = $this->createTrackingRecord($order, $responseData, $carrier);
                
                Log::info('Shipment created successfully', [
                    'order_id' => $order->id,
                    'carrier' => $carrier,
                    'tracking_number' => $tracking->tracking_number
                ]);

                return [
                    'success' => true,
                    'tracking' => $tracking,
                    'provider_response' => $responseData
                ];
            } else {
                throw new \Exception("Failed to create shipment: " . $response->body());
            }

        } catch (\Exception $e) {
            Log::error('Failed to create shipment', [
                'order_id' => $order->id,
                'carrier' => $carrier,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Get tracking information from shipping provider
     */
    public function getTrackingInfo(Tracking $tracking): array
    {
        $carrier = $tracking->carrier_code ?? $tracking->carrier;
        
        if (!isset($this->providers[$carrier])) {
            throw new \Exception("Unsupported shipping provider: $carrier");
        }

        try {
            $provider = $this->providers[$carrier];
            
            $response = Http::timeout(30)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . config("shipping.{$carrier}.api_key")
                ])
                ->get($provider['api_url'] . '/tracking/' . $tracking->tracking_number);

            if ($response->successful()) {
                $trackingData = $response->json();
                
                // Update tracking with latest information
                $this->updateTrackingFromProvider($tracking, $trackingData);
                
                return [
                    'success' => true,
                    'tracking' => $tracking->fresh(),
                    'provider_data' => $trackingData
                ];
            } else {
                throw new \Exception("Failed to get tracking info: " . $response->body());
            }

        } catch (\Exception $e) {
            Log::error('Failed to get tracking info', [
                'tracking_id' => $tracking->id,
                'carrier' => $carrier,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Update tracking status from provider webhook
     */
    public function handleProviderWebhook(string $carrier, array $webhookData): array
    {
        try {
            $trackingNumber = $webhookData['tracking_number'] ?? null;
            
            if (!$trackingNumber) {
                throw new \Exception('Tracking number not provided in webhook');
            }

            $tracking = Tracking::where('tracking_number', $trackingNumber)
                              ->where('carrier_code', $carrier)
                              ->first();

            if (!$tracking) {
                throw new \Exception("Tracking not found: $trackingNumber");
            }

            // Update tracking status based on webhook data
            $status = $this->mapProviderStatus($carrier, $webhookData['status']);
            $description = $webhookData['description'] ?? null;
            $location = $webhookData['location'] ?? null;

            $tracking->updateStatus($status, $description, $location);

            // Update additional fields if provided
            if (isset($webhookData['estimated_delivery'])) {
                $tracking->update(['estimated_delivery' => $webhookData['estimated_delivery']]);
            }

            if (isset($webhookData['actual_delivery'])) {
                $tracking->update(['actual_delivery' => $webhookData['actual_delivery']]);
            }

            Log::info('Tracking updated from provider webhook', [
                'tracking_id' => $tracking->id,
                'carrier' => $carrier,
                'status' => $status
            ]);

            return [
                'success' => true,
                'tracking' => $tracking->fresh()
            ];

        } catch (\Exception $e) {
            Log::error('Failed to handle provider webhook', [
                'carrier' => $carrier,
                'error' => $e->getMessage(),
                'webhook_data' => $webhookData
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Prepare shipment data for provider API
     */
    private function prepareShipmentData(Order $order, array $shippingData): array
    {
        return [
            'order_id' => $order->order_number,
            'sender' => [
                'name' => config('app.name'),
                'address' => config('shipping.sender_address'),
                'phone' => config('shipping.sender_phone'),
                'email' => config('shipping.sender_email')
            ],
            'recipient' => [
                'name' => $order->user->name,
                'address' => $order->shipping_address,
                'phone' => $order->contact_number,
                'email' => $order->email
            ],
            'package' => [
                'weight' => $shippingData['weight'] ?? 1.0,
                'dimensions' => $shippingData['dimensions'] ?? ['length' => 10, 'width' => 10, 'height' => 10],
                'description' => $shippingData['description'] ?? 'Package contents',
                'value' => $order->total,
                'insurance' => $shippingData['insurance'] ?? false
            ],
            'service_type' => $shippingData['service_type'] ?? 'standard',
            'special_instructions' => $shippingData['special_instructions'] ?? null
        ];
    }

    /**
     * Create tracking record from provider response
     */
    private function createTrackingRecord(Order $order, array $responseData, string $carrier): Tracking
    {
        return Tracking::create([
            'order_id' => $order->id,
            'tracking_number' => $responseData['tracking_number'],
            'carrier' => $this->providers[$carrier]['name'],
            'carrier_code' => $carrier,
            'status' => 'shipped',
            'status_description' => 'Package shipped',
            'shipped_at' => now(),
            'estimated_delivery' => $responseData['estimated_delivery'] ?? null,
            'shipping_cost' => $responseData['shipping_cost'] ?? null,
            'weight' => $responseData['weight'] ?? null,
            'dimensions' => $responseData['dimensions'] ?? null,
            'updated_by' => 'shipping_provider'
        ]);
    }

    /**
     * Update tracking from provider data
     */
    private function updateTrackingFromProvider(Tracking $tracking, array $providerData): void
    {
        $status = $this->mapProviderStatus($tracking->carrier_code, $providerData['status']);
        
        $tracking->updateStatus(
            $status,
            $providerData['description'] ?? null,
            $providerData['location'] ?? null
        );

        // Update additional fields
        $updateData = [];
        
        if (isset($providerData['estimated_delivery'])) {
            $updateData['estimated_delivery'] = $providerData['estimated_delivery'];
        }
        
        if (isset($providerData['actual_delivery'])) {
            $updateData['actual_delivery'] = $providerData['actual_delivery'];
        }

        if (!empty($updateData)) {
            $tracking->update($updateData);
        }
    }

    /**
     * Map provider status to internal status
     */
    private function mapProviderStatus(string $carrier, string $providerStatus): string
    {
        $statusMap = [
            'jnt' => [
                'picked_up' => 'shipped',
                'in_transit' => 'in_transit',
                'out_for_delivery' => 'out_for_delivery',
                'delivered' => 'delivered',
                'delivery_attempted' => 'delivery_attempted',
                'returned' => 'returned'
            ],
            'ninjavan' => [
                'picked_up' => 'shipped',
                'in_transit' => 'in_transit',
                'out_for_delivery' => 'out_for_delivery',
                'delivered' => 'delivered',
                'delivery_attempted' => 'delivery_attempted',
                'returned' => 'returned'
            ],
            'lbc' => [
                'picked_up' => 'shipped',
                'in_transit' => 'in_transit',
                'out_for_delivery' => 'out_for_delivery',
                'delivered' => 'delivered',
                'delivery_attempted' => 'delivery_attempted',
                'returned' => 'returned'
            ],
            'flash' => [
                'picked_up' => 'shipped',
                'in_transit' => 'in_transit',
                'out_for_delivery' => 'out_for_delivery',
                'delivered' => 'delivered',
                'delivery_attempted' => 'delivery_attempted',
                'returned' => 'returned'
            ]
        ];

        return $statusMap[$carrier][$providerStatus] ?? 'pending';
    }

    /**
     * Get available shipping providers
     */
    public function getAvailableProviders(): array
    {
        return $this->providers;
    }

    /**
     * Calculate shipping cost
     */
    public function calculateShippingCost(array $packageData, string $carrier = 'jnt'): array
    {
        try {
            $provider = $this->providers[$carrier];
            
            $response = Http::timeout(30)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . config("shipping.{$carrier}.api_key")
                ])
                ->post($provider['api_url'] . '/calculate', $packageData);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'cost' => $response->json()
                ];
            } else {
                throw new \Exception("Failed to calculate shipping cost: " . $response->body());
            }

        } catch (\Exception $e) {
            Log::error('Failed to calculate shipping cost', [
                'carrier' => $carrier,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
}