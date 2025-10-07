<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Seller;
use App\Models\Order;
use App\Models\Earning;
use App\Models\CommissionRule;
use App\Models\PayoutRequest;
use App\Models\SubscriptionPlan;
use App\Models\PlatformSetting;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DataSyncService
{
    /**
     * Sync seller data between systems
     */
    public function syncSellerData($sellerId, $data = [])
    {
        try {
            $seller = Seller::findOrFail($sellerId);
            
            // Update seller data
            if (!empty($data)) {
                $seller->update($data);
            }
            
            // Get comprehensive seller data
            $sellerData = [
                'id' => $seller->id,
                'business_name' => $seller->business_name,
                'email' => $seller->user->email,
                'status' => $seller->status,
                'verification_status' => $seller->verification_status,
                'commission_rate' => $seller->commission_rate,
                'total_sales' => $seller->total_sales,
                'total_products' => $seller->products()->count(),
                'total_orders' => $seller->orders()->count(),
                'subscription_plan' => $seller->subscriptions()->active()->first()?->plan?->name,
                'shops' => $seller->shops->map(function($shop) {
                    return [
                        'id' => $shop->id,
                        'name' => $shop->name,
                        'status' => $shop->status
                    ];
                }),
                'created_at' => $seller->created_at,
                'updated_at' => $seller->updated_at
            ];
            
            // Cache the data for quick access
            Cache::put("seller_data_{$sellerId}", $sellerData, 3600);
            
            // Trigger webhook for real-time updates
            $this->triggerWebhook('seller.updated', $sellerData);
            
            return $sellerData;
            
        } catch (\Exception $e) {
            Log::error("Failed to sync seller data: " . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Sync product data between systems
     */
    public function syncProductData($productId, $data = [])
    {
        try {
            $product = Product::findOrFail($productId);
            
            // Update product data
            if (!empty($data)) {
                $product->update($data);
            }
            
            // Get comprehensive product data
            $productData = [
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                'price' => $product->price,
                'stock' => $product->stock,
                'category' => $product->category,
                'status' => $product->status,
                'seller_id' => $product->seller_id,
                'shop_id' => $product->shop_id,
                'sku' => $product->sku,
                'image' => $product->image,
                'rating' => $product->rating,
                'reviews_count' => $product->reviews_count,
                'seller' => [
                    'id' => $product->seller->id,
                    'business_name' => $product->seller->business_name,
                    'status' => $product->seller->status
                ],
                'shop' => [
                    'id' => $product->shop->id,
                    'name' => $product->shop->name,
                    'status' => $product->shop->status
                ],
                'created_at' => $product->created_at,
                'updated_at' => $product->updated_at
            ];
            
            // Cache the data
            Cache::put("product_data_{$productId}", $productData, 3600);
            
            // Trigger webhook
            $this->triggerWebhook('product.updated', $productData);
            
            return $productData;
            
        } catch (\Exception $e) {
            Log::error("Failed to sync product data: " . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Sync order data between systems
     */
    public function syncOrderData($orderId, $data = [])
    {
        try {
            $order = Order::findOrFail($orderId);
            
            // Update order data
            if (!empty($data)) {
                $order->update($data);
            }
            
            // Get comprehensive order data
            $orderData = [
                'id' => $order->id,
                'order_number' => $order->order_number,
                'status' => $order->status,
                'total_amount' => $order->total_amount,
                'payment_status' => $order->payment_status,
                'shipping_status' => $order->shipping_status,
                'customer_id' => $order->user_id,
                'customer' => [
                    'id' => $order->user->id,
                    'name' => $order->user->name,
                    'email' => $order->user->email
                ],
                'items' => $order->items->map(function($item) {
                    return [
                        'id' => $item->id,
                        'product_id' => $item->product_id,
                        'product_name' => $item->product->name,
                        'quantity' => $item->quantity,
                        'price' => $item->price,
                        'seller_id' => $item->product->seller_id,
                        'seller_name' => $item->product->seller->business_name
                    ];
                }),
                'shipping_address' => $order->shipping_address,
                'billing_address' => $order->billing_address,
                'created_at' => $order->created_at,
                'updated_at' => $order->updated_at
            ];
            
            // Cache the data
            Cache::put("order_data_{$orderId}", $orderData, 3600);
            
            // Trigger webhook
            $this->triggerWebhook('order.updated', $orderData);
            
            return $orderData;
            
        } catch (\Exception $e) {
            Log::error("Failed to sync order data: " . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Sync commission and earnings data
     */
    public function syncEarningsData($sellerId, $period = 'month')
    {
        try {
            $seller = Seller::findOrFail($sellerId);
            
            $startDate = now()->startOfMonth();
            $endDate = now()->endOfMonth();
            
            if ($period === 'week') {
                $startDate = now()->startOfWeek();
                $endDate = now()->endOfWeek();
            } elseif ($period === 'year') {
                $startDate = now()->startOfYear();
                $endDate = now()->endOfYear();
            }
            
            $earnings = Earning::where('seller_id', $sellerId)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->get();
            
            $totalEarnings = $earnings->sum('amount');
            $totalCommission = $earnings->sum('commission_amount');
            $netEarnings = $earnings->sum('net_amount');
            
            $earningsData = [
                'seller_id' => $sellerId,
                'period' => $period,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'total_earnings' => $totalEarnings,
                'total_commission' => $totalCommission,
                'net_earnings' => $netEarnings,
                'orders_count' => $earnings->count(),
                'breakdown' => $earnings->map(function($earning) {
                    return [
                        'id' => $earning->id,
                        'order_id' => $earning->order_id,
                        'amount' => $earning->amount,
                        'commission_amount' => $earning->commission_amount,
                        'net_amount' => $earning->net_amount,
                        'created_at' => $earning->created_at
                    ];
                })
            ];
            
            // Cache the data
            Cache::put("earnings_data_{$sellerId}_{$period}", $earningsData, 1800);
            
            // Trigger webhook
            $this->triggerWebhook('earnings.updated', $earningsData);
            
            return $earningsData;
            
        } catch (\Exception $e) {
            Log::error("Failed to sync earnings data: " . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Sync platform settings and configuration
     */
    public function syncPlatformSettings()
    {
        try {
            $settings = PlatformSetting::all()->mapWithKeys(function($setting) {
                return [$setting->key => $setting->value];
            });
            
            $platformData = [
                'settings' => $settings,
                'commission_rules' => CommissionRule::active()->get()->map(function($rule) {
                    return [
                        'id' => $rule->id,
                        'name' => $rule->name,
                        'category' => $rule->category,
                        'commission_rate' => $rule->commission_rate,
                        'priority' => $rule->priority,
                        'is_active' => $rule->is_active
                    ];
                }),
                'subscription_plans' => SubscriptionPlan::active()->get()->map(function($plan) {
                    return [
                        'id' => $plan->id,
                        'name' => $plan->name,
                        'type' => $plan->type,
                        'price' => $plan->price,
                        'commission_rate' => $plan->commission_rate,
                        'features' => $plan->features
                    ];
                }),
                'payout_settings' => [
                    'min_payout_amount' => $settings['min_payout_amount'] ?? 100,
                    'auto_approve_limit' => $settings['auto_approve_payout_limit'] ?? 500,
                    'supported_methods' => $settings['supported_payment_methods'] ?? ['bank_transfer']
                ]
            ];
            
            // Cache the data
            Cache::put('platform_settings', $platformData, 3600);
            
            // Trigger webhook
            $this->triggerWebhook('platform.settings.updated', $platformData);
            
            return $platformData;
            
        } catch (\Exception $e) {
            Log::error("Failed to sync platform settings: " . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Get shared data for both systems
     */
    public function getSharedData($type, $filters = [])
    {
        try {
            switch ($type) {
                case 'sellers':
                    return $this->getSellersData($filters);
                case 'products':
                    return $this->getProductsData($filters);
                case 'orders':
                    return $this->getOrdersData($filters);
                case 'earnings':
                    return $this->getEarningsData($filters);
                case 'platform_stats':
                    return $this->getPlatformStatsData($filters);
                default:
                    throw new \InvalidArgumentException("Unknown data type: {$type}");
            }
        } catch (\Exception $e) {
            Log::error("Failed to get shared data: " . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Get sellers data for both systems
     */
    private function getSellersData($filters = [])
    {
        $query = Seller::with(['user', 'shops', 'subscriptions.plan']);
        
        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        
        if (isset($filters['verification_status'])) {
            $query->where('verification_status', $filters['verification_status']);
        }
        
        if (isset($filters['search'])) {
            $query->where(function($q) use ($filters) {
                $q->where('business_name', 'like', '%' . $filters['search'] . '%')
                  ->orWhereHas('user', function($userQuery) use ($filters) {
                      $userQuery->where('email', 'like', '%' . $filters['search'] . '%');
                  });
            });
        }
        
        $sellers = $query->paginate($filters['per_page'] ?? 15);
        
        return [
            'sellers' => $sellers->items(),
            'pagination' => [
                'current_page' => $sellers->currentPage(),
                'last_page' => $sellers->lastPage(),
                'per_page' => $sellers->perPage(),
                'total' => $sellers->total()
            ]
        ];
    }
    
    /**
     * Get products data for both systems
     */
    private function getProductsData($filters = [])
    {
        $query = Product::with(['seller', 'shop', 'reviews']);
        
        if (isset($filters['seller_id'])) {
            $query->where('seller_id', $filters['seller_id']);
        }
        
        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        
        if (isset($filters['category'])) {
            $query->where('category', $filters['category']);
        }
        
        if (isset($filters['search'])) {
            $query->where(function($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('description', 'like', '%' . $filters['search'] . '%');
            });
        }
        
        $products = $query->paginate($filters['per_page'] ?? 15);
        
        return [
            'products' => $products->items(),
            'pagination' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total()
            ]
        ];
    }
    
    /**
     * Get orders data for both systems
     */
    private function getOrdersData($filters = [])
    {
        $query = Order::with(['user', 'items.product.seller']);
        
        if (isset($filters['seller_id'])) {
            $query->whereHas('items.product', function($q) use ($filters) {
                $q->where('seller_id', $filters['seller_id']);
            });
        }
        
        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        
        if (isset($filters['date_from'])) {
            $query->where('created_at', '>=', $filters['date_from']);
        }
        
        if (isset($filters['date_to'])) {
            $query->where('created_at', '<=', $filters['date_to']);
        }
        
        $orders = $query->paginate($filters['per_page'] ?? 15);
        
        return [
            'orders' => $orders->items(),
            'pagination' => [
                'current_page' => $orders->currentPage(),
                'last_page' => $orders->lastPage(),
                'per_page' => $orders->perPage(),
                'total' => $orders->total()
            ]
        ];
    }
    
    /**
     * Get earnings data for both systems
     */
    private function getEarningsData($filters = [])
    {
        $query = Earning::with(['seller', 'order']);
        
        if (isset($filters['seller_id'])) {
            $query->where('seller_id', $filters['seller_id']);
        }
        
        if (isset($filters['date_from'])) {
            $query->where('created_at', '>=', $filters['date_from']);
        }
        
        if (isset($filters['date_to'])) {
            $query->where('created_at', '<=', $filters['date_to']);
        }
        
        $earnings = $query->paginate($filters['per_page'] ?? 15);
        
        return [
            'earnings' => $earnings->items(),
            'pagination' => [
                'current_page' => $earnings->currentPage(),
                'last_page' => $earnings->lastPage(),
                'per_page' => $earnings->perPage(),
                'total' => $earnings->total()
            ]
        ];
    }
    
    /**
     * Get platform statistics data
     */
    private function getPlatformStatsData($filters = [])
    {
        $startDate = $filters['date_from'] ?? now()->startOfMonth();
        $endDate = $filters['date_to'] ?? now()->endOfMonth();
        
        $stats = [
            'total_users' => \App\Models\User::count(),
            'total_sellers' => Seller::count(),
            'active_sellers' => Seller::where('status', 'active')->count(),
            'total_products' => Product::count(),
            'active_products' => Product::where('status', 'active')->count(),
            'total_orders' => Order::count(),
            'orders_in_period' => Order::whereBetween('created_at', [$startDate, $endDate])->count(),
            'total_revenue' => Order::where('status', 'completed')->sum('total_amount'),
            'revenue_in_period' => Order::where('status', 'completed')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->sum('total_amount'),
            'total_commission' => Earning::sum('commission_amount'),
            'commission_in_period' => Earning::whereBetween('created_at', [$startDate, $endDate])
                ->sum('commission_amount'),
            'pending_payouts' => PayoutRequest::where('status', 'pending')->count(),
            'pending_payout_amount' => PayoutRequest::where('status', 'pending')->sum('amount')
        ];
        
        return $stats;
    }
    
    /**
     * Trigger webhook for real-time updates
     */
    private function triggerWebhook($event, $data)
    {
        try {
            // Get webhook endpoints for the event
            $endpoints = \App\Models\WebhookEndpoint::where('events', 'like', "%{$event}%")
                ->where('is_active', true)
                ->get();
            
            foreach ($endpoints as $endpoint) {
                $this->sendWebhook($endpoint, $event, $data);
            }
        } catch (\Exception $e) {
            Log::error("Failed to trigger webhook: " . $e->getMessage());
        }
    }
    
    /**
     * Send webhook to endpoint
     */
    private function sendWebhook($endpoint, $event, $data)
    {
        try {
            $payload = [
                'event' => $event,
                'timestamp' => now()->toISOString(),
                'data' => $data
            ];
            
            $client = new \GuzzleHttp\Client();
            $response = $client->post($endpoint->url, [
                'json' => $payload,
                'headers' => [
                    'X-Webhook-Signature' => $this->generateWebhookSignature($payload, $endpoint->secret),
                    'Content-Type' => 'application/json'
                ],
                'timeout' => 30
            ]);
            
            // Log webhook event
            \App\Models\WebhookEvent::create([
                'endpoint_id' => $endpoint->id,
                'event' => $event,
                'payload' => $payload,
                'response_status' => $response->getStatusCode(),
                'response_body' => $response->getBody()->getContents()
            ]);
            
        } catch (\Exception $e) {
            Log::error("Failed to send webhook to {$endpoint->url}: " . $e->getMessage());
        }
    }
    
    /**
     * Generate webhook signature
     */
    private function generateWebhookSignature($payload, $secret)
    {
        return hash_hmac('sha256', json_encode($payload), $secret);
    }
}
