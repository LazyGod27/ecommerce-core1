<?php

namespace App\Services;

use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use App\Models\Review;
use App\Models\Cart;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class CoreTransaction1WebhookSender
{
    protected $coreTransaction2WebhookUrl;
    protected $coreTransaction3WebhookUrl;
    protected $coreTransaction2Secret;
    protected $coreTransaction3Secret;

    public function __construct()
    {
        $this->coreTransaction2WebhookUrl = config('webhooks.core_transaction_2_url');
        $this->coreTransaction3WebhookUrl = config('webhooks.core_transaction_3_url');
        $this->coreTransaction2Secret = config('webhooks.core_transaction_2_secret');
        $this->coreTransaction3Secret = config('webhooks.core_transaction_3_secret');
    }

    /**
     * Send customer created event
     */
    public function sendCustomerCreated(User $customer)
    {
        $data = [
            'id' => $customer->id,
            'name' => $customer->name,
            'email' => $customer->email,
            'phone' => $customer->phone,
            'address' => [
                'line1' => $customer->address_line1,
                'line2' => $customer->address_line2,
                'city' => $customer->city,
                'state' => $customer->state,
                'postal_code' => $customer->postal_code,
                'country' => $customer->country
            ],
            'created_at' => $customer->created_at->toISOString()
        ];

        $this->sendWebhook('customer.created', $data, ['core_transaction_2', 'core_transaction_3']);
    }

    /**
     * Send customer updated event
     */
    public function sendCustomerUpdated(User $customer)
    {
        $data = [
            'id' => $customer->id,
            'name' => $customer->name,
            'email' => $customer->email,
            'phone' => $customer->phone,
            'address' => [
                'line1' => $customer->address_line1,
                'line2' => $customer->address_line2,
                'city' => $customer->city,
                'state' => $customer->state,
                'postal_code' => $customer->postal_code,
                'country' => $customer->country
            ],
            'updated_at' => $customer->updated_at->toISOString()
        ];

        $this->sendWebhook('customer.updated', $data, ['core_transaction_2', 'core_transaction_3']);
    }

    /**
     * Send order created event
     */
    public function sendOrderCreated(Order $order)
    {
        $data = [
            'id' => $order->id,
            'order_number' => $order->order_number,
            'status' => $order->status,
            'payment_status' => $order->payment_status,
            'customer' => [
                'id' => $order->user->id,
                'name' => $order->user->name,
                'email' => $order->user->email,
                'phone' => $order->user->phone
            ],
            'shipping_address' => $order->shipping_address,
            'contact_number' => $order->contact_number,
            'financial' => [
                'subtotal' => $order->subtotal,
                'tax' => $order->tax,
                'shipping_cost' => $order->shipping_cost,
                'total' => $order->total
            ],
            'items' => $order->items->map(function($item) {
                return [
                    'id' => $item->id,
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'subtotal' => $item->quantity * $item->price,
                    'seller' => [
                        'id' => $item->product->seller->id,
                        'business_name' => $item->product->seller->business_name
                    ]
                ];
            }),
            'created_at' => $order->created_at->toISOString()
        ];

        $this->sendWebhook('order.created', $data, ['core_transaction_2', 'core_transaction_3']);
    }

    /**
     * Send order updated event
     */
    public function sendOrderUpdated(Order $order)
    {
        $data = [
            'id' => $order->id,
            'order_number' => $order->order_number,
            'status' => $order->status,
            'payment_status' => $order->payment_status,
            'customer' => [
                'id' => $order->user->id,
                'name' => $order->user->name,
                'email' => $order->user->email
            ],
            'shipping' => [
                'address' => $order->shipping_address,
                'contact_number' => $order->contact_number,
                'tracking' => $order->tracking ? [
                    'tracking_number' => $order->tracking->tracking_number,
                    'carrier' => $order->tracking->carrier,
                    'status' => $order->tracking->status
                ] : null
            ],
            'financial' => [
                'subtotal' => $order->subtotal,
                'tax' => $order->tax,
                'shipping_cost' => $order->shipping_cost,
                'total' => $order->total
            ],
            'updated_at' => $order->updated_at->toISOString()
        ];

        $this->sendWebhook('order.updated', $data, ['core_transaction_2', 'core_transaction_3']);
    }

    /**
     * Send order status changed event
     */
    public function sendOrderStatusChanged(Order $order, $oldStatus, $newStatus)
    {
        $data = [
            'id' => $order->id,
            'order_number' => $order->order_number,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'customer' => [
                'id' => $order->user->id,
                'name' => $order->user->name,
                'email' => $order->user->email
            ],
            'items' => $order->items->map(function($item) {
                return [
                    'product_id' => $item->product_id,
                    'seller' => [
                        'id' => $item->product->seller->id,
                        'business_name' => $item->product->seller->business_name
                    ]
                ];
            }),
            'changed_at' => now()->toISOString()
        ];

        $this->sendWebhook('order.status_changed', $data, ['core_transaction_2', 'core_transaction_3']);
    }

    /**
     * Send payment status changed event
     */
    public function sendPaymentStatusChanged(Order $order, $oldStatus, $newStatus)
    {
        $data = [
            'id' => $order->id,
            'order_number' => $order->order_number,
            'old_payment_status' => $oldStatus,
            'new_payment_status' => $newStatus,
            'payment_method' => $order->payment_method,
            'total' => $order->total,
            'customer' => [
                'id' => $order->user->id,
                'name' => $order->user->name,
                'email' => $order->user->email
            ],
            'items' => $order->items->map(function($item) {
                return [
                    'product_id' => $item->product_id,
                    'seller' => [
                        'id' => $item->product->seller->id,
                        'business_name' => $item->product->seller->business_name
                    ]
                ];
            }),
            'changed_at' => now()->toISOString()
        ];

        $this->sendWebhook('payment.status_changed', $data, ['core_transaction_2', 'core_transaction_3']);
    }

    /**
     * Send product viewed event
     */
    public function sendProductViewed(Product $product, User $customer = null)
    {
        $data = [
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'category' => $product->category,
                'seller' => [
                    'id' => $product->seller->id,
                    'business_name' => $product->seller->business_name
                ]
            ],
            'customer' => $customer ? [
                'id' => $customer->id,
                'name' => $customer->name,
                'email' => $customer->email
            ] : null,
            'viewed_at' => now()->toISOString()
        ];

        $this->sendWebhook('product.viewed', $data, ['core_transaction_2', 'core_transaction_3']);
    }

    /**
     * Send product added to cart event
     */
    public function sendProductAddedToCart(Product $product, User $customer, $quantity)
    {
        $data = [
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'category' => $product->category,
                'seller' => [
                    'id' => $product->seller->id,
                    'business_name' => $product->seller->business_name
                ]
            ],
            'customer' => [
                'id' => $customer->id,
                'name' => $customer->name,
                'email' => $customer->email
            ],
            'quantity' => $quantity,
            'added_at' => now()->toISOString()
        ];

        $this->sendWebhook('product.added_to_cart', $data, ['core_transaction_2', 'core_transaction_3']);
    }

    /**
     * Send review created event
     */
    public function sendReviewCreated(Review $review)
    {
        $data = [
            'id' => $review->id,
            'rating' => $review->rating,
            'comment' => $review->comment,
            'status' => $review->status,
            'customer' => [
                'id' => $review->user->id,
                'name' => $review->user->name,
                'email' => $review->user->email
            ],
            'product' => [
                'id' => $review->product->id,
                'name' => $review->product->name,
                'seller' => [
                    'id' => $review->product->seller->id,
                    'business_name' => $review->product->seller->business_name
                ]
            ],
            'created_at' => $review->created_at->toISOString()
        ];

        $this->sendWebhook('review.created', $data, ['core_transaction_2', 'core_transaction_3']);
    }

    /**
     * Send review updated event
     */
    public function sendReviewUpdated(Review $review)
    {
        $data = [
            'id' => $review->id,
            'rating' => $review->rating,
            'comment' => $review->comment,
            'status' => $review->status,
            'customer' => [
                'id' => $review->user->id,
                'name' => $review->user->name,
                'email' => $review->user->email
            ],
            'product' => [
                'id' => $review->product->id,
                'name' => $review->product->name,
                'seller' => [
                    'id' => $review->product->seller->id,
                    'business_name' => $review->product->seller->business_name
                ]
            ],
            'updated_at' => $review->updated_at->toISOString()
        ];

        $this->sendWebhook('review.updated', $data, ['core_transaction_2', 'core_transaction_3']);
    }

    /**
     * Send cart abandoned event
     */
    public function sendCartAbandoned(Cart $cart)
    {
        $data = [
            'id' => $cart->id,
            'customer' => [
                'id' => $cart->user->id,
                'name' => $cart->user->name,
                'email' => $cart->user->email
            ],
            'items' => $cart->items->map(function($item) {
                return [
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'seller' => [
                        'id' => $item->product->seller->id,
                        'business_name' => $item->product->seller->business_name
                    ]
                ];
            }),
            'total_value' => $cart->items->sum(function($item) {
                return $item->quantity * $item->price;
            }),
            'abandoned_at' => now()->toISOString()
        ];

        $this->sendWebhook('cart.abandoned', $data, ['core_transaction_2', 'core_transaction_3']);
    }

    /**
     * Send marketplace analytics event
     */
    public function sendMarketplaceAnalytics($analytics)
    {
        $data = [
            'period' => $analytics['period'],
            'customers' => $analytics['customers'],
            'orders' => $analytics['orders'],
            'products' => $analytics['products'],
            'reviews' => $analytics['reviews'],
            'conversion' => $analytics['conversion'],
            'generated_at' => now()->toISOString()
        ];

        $this->sendWebhook('marketplace.analytics', $data, ['core_transaction_3']);
    }

    /**
     * Send webhook to specified systems
     */
    private function sendWebhook($event, $data, $systems = ['core_transaction_2', 'core_transaction_3'])
    {
        $payload = [
            'event' => $event,
            'timestamp' => now()->toISOString(),
            'source' => 'core_transaction_1',
            'data' => $data
        ];

        foreach ($systems as $system) {
            $this->sendToSystem($system, $payload);
        }
    }

    /**
     * Send webhook to specific system
     */
    private function sendToSystem($system, $payload)
    {
        try {
            $webhookUrl = $system === 'core_transaction_2' ? $this->coreTransaction2WebhookUrl : $this->coreTransaction3WebhookUrl;
            $secret = $system === 'core_transaction_2' ? $this->coreTransaction2Secret : $this->coreTransaction3Secret;

            if (!$webhookUrl) {
                Log::warning("Webhook URL not configured for {$system}");
                return;
            }

            $signature = hash_hmac('sha256', json_encode($payload), $secret);

            $response = Http::timeout(30)
                ->withHeaders([
                    'X-Webhook-Signature' => $signature,
                    'Content-Type' => 'application/json',
                    'User-Agent' => 'Core-Transaction-1/1.0'
                ])
                ->post($webhookUrl, $payload);

            if ($response->successful()) {
                Log::info("Webhook sent successfully to {$system}", [
                    'event' => $payload['event'],
                    'status' => $response->status()
                ]);
            } else {
                Log::error("Webhook failed to {$system}", [
                    'event' => $payload['event'],
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
            }

        } catch (\Exception $e) {
            Log::error("Failed to send webhook to {$system}: " . $e->getMessage(), [
                'event' => $payload['event'],
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Test webhook connectivity
     */
    public function testWebhookConnectivity($system)
    {
        $testPayload = [
            'event' => 'test',
            'timestamp' => now()->toISOString(),
            'source' => 'core_transaction_1',
            'data' => [
                'message' => 'This is a test webhook from Core Transaction 1',
                'test_id' => uniqid()
            ]
        ];

        return $this->sendToSystem($system, $testPayload);
    }

    /**
     * Get webhook status for both systems
     */
    public function getWebhookStatus()
    {
        return [
            'core_transaction_2' => [
                'url' => $this->coreTransaction2WebhookUrl,
                'configured' => !empty($this->coreTransaction2WebhookUrl),
                'last_test' => Cache::get('webhook_test_core_transaction_2')
            ],
            'core_transaction_3' => [
                'url' => $this->coreTransaction3WebhookUrl,
                'configured' => !empty($this->coreTransaction3WebhookUrl),
                'last_test' => Cache::get('webhook_test_core_transaction_3')
            ]
        ];
    }
}
