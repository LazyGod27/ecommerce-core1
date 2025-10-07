<?php

namespace App\Services;

use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use App\Models\Review;
use App\Models\Address;
use App\Models\Cart;
use App\Models\Tracking;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class CoreTransaction1DataExporter
{
    /**
     * Export customer data for Core Transaction 2 & 3
     */
    public function exportCustomerData($customerId = null, $filters = [])
    {
        try {
            $query = User::with(['orders', 'addresses', 'reviews', 'cart']);

            if ($customerId) {
                $query->where('id', $customerId);
            }

            // Apply filters
            if (isset($filters['date_from'])) {
                $query->where('created_at', '>=', $filters['date_from']);
            }

            if (isset($filters['date_to'])) {
                $query->where('created_at', '<=', $filters['date_to']);
            }

            if (isset($filters['has_orders'])) {
                if ($filters['has_orders']) {
                    $query->has('orders');
                } else {
                    $query->doesntHave('orders');
                }
            }

            $customers = $query->paginate($filters['per_page'] ?? 15);

            $customerData = $customers->map(function($customer) {
                return [
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
                        'country' => $customer->country,
                        'full_address' => $customer->full_address
                    ],
                    'profile' => [
                        'birth_date' => $customer->birth_date?->format('Y-m-d'),
                        'gender' => $customer->gender,
                        'bio' => $customer->bio,
                        'avatar' => $customer->avatar,
                        'timezone' => $customer->timezone,
                        'preferred_language' => $customer->preferred_language
                    ],
                    'preferences' => [
                        'email_notifications' => $customer->email_notifications,
                        'sms_notifications' => $customer->sms_notifications
                    ],
                    'statistics' => [
                        'total_orders' => $customer->orders->count(),
                        'total_spent' => $customer->orders->sum('total'),
                        'average_order_value' => $customer->orders->avg('total') ?? 0,
                        'last_order_date' => $customer->orders->max('created_at')?->format('Y-m-d H:i:s'),
                        'total_reviews' => $customer->reviews->count(),
                        'addresses_count' => $customer->addresses->count()
                    ],
                    'created_at' => $customer->created_at->toISOString(),
                    'updated_at' => $customer->updated_at->toISOString()
                ];
            });

            return [
                'customers' => $customerData,
                'pagination' => [
                    'current_page' => $customers->currentPage(),
                    'last_page' => $customers->lastPage(),
                    'per_page' => $customers->perPage(),
                    'total' => $customers->total()
                ]
            ];

        } catch (\Exception $e) {
            Log::error("Failed to export customer data: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Export order data for Core Transaction 2 & 3
     */
    public function exportOrderData($orderId = null, $filters = [])
    {
        try {
            $query = Order::with(['user', 'items.product.seller', 'tracking', 'payments']);

            if ($orderId) {
                $query->where('id', $orderId);
            }

            // Apply filters
            if (isset($filters['customer_id'])) {
                $query->where('user_id', $filters['customer_id']);
            }

            if (isset($filters['status'])) {
                $query->where('status', $filters['status']);
            }

            if (isset($filters['payment_status'])) {
                $query->where('payment_status', $filters['payment_status']);
            }

            if (isset($filters['date_from'])) {
                $query->where('created_at', '>=', $filters['date_from']);
            }

            if (isset($filters['date_to'])) {
                $query->where('created_at', '<=', $filters['date_to']);
            }

            if (isset($filters['seller_id'])) {
                $query->whereHas('items.product', function($q) use ($filters) {
                    $q->where('seller_id', $filters['seller_id']);
                });
            }

            $orders = $query->paginate($filters['per_page'] ?? 15);

            $orderData = $orders->map(function($order) {
                return [
                    'id' => $order->id,
                    'order_number' => $order->order_number,
                    'status' => $order->status,
                    'payment_status' => $order->payment_status,
                    'payment_method' => $order->payment_method,
                    'customer' => [
                        'id' => $order->user->id,
                        'name' => $order->user->name,
                        'email' => $order->user->email,
                        'phone' => $order->user->phone
                    ],
                    'shipping' => [
                        'address' => $order->shipping_address,
                        'contact_number' => $order->contact_number,
                        'tracking' => $order->tracking ? [
                            'tracking_number' => $order->tracking->tracking_number,
                            'carrier' => $order->tracking->carrier,
                            'status' => $order->tracking->status,
                            'estimated_delivery' => $order->tracking->estimated_delivery?->format('Y-m-d H:i:s'),
                            'delivered_at' => $order->tracking->delivered_at?->format('Y-m-d H:i:s')
                        ] : null
                    ],
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
                                'business_name' => $item->product->seller->business_name,
                                'email' => $item->product->seller->user->email
                            ]
                        ];
                    }),
                    'payments' => $order->payments->map(function($payment) {
                        return [
                            'id' => $payment->id,
                            'amount' => $payment->amount,
                            'method' => $payment->method,
                            'status' => $payment->status,
                            'transaction_id' => $payment->transaction_id,
                            'created_at' => $payment->created_at->toISOString()
                        ];
                    }),
                    'timestamps' => [
                        'created_at' => $order->created_at->toISOString(),
                        'paid_at' => $order->paid_at?->toISOString(),
                        'delivered_at' => $order->delivered_at?->toISOString(),
                        'refunded_at' => $order->refunded_at?->toISOString()
                    ],
                    'notes' => $order->notes,
                    'refund_reason' => $order->refund_reason
                ];
            });

            return [
                'orders' => $orderData,
                'pagination' => [
                    'current_page' => $orders->currentPage(),
                    'last_page' => $orders->lastPage(),
                    'per_page' => $orders->perPage(),
                    'total' => $orders->total()
                ]
            ];

        } catch (\Exception $e) {
            Log::error("Failed to export order data: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Export product data for Core Transaction 2 & 3
     */
    public function exportProductData($productId = null, $filters = [])
    {
        try {
            $query = Product::with(['seller', 'shop', 'reviews']);

            if ($productId) {
                $query->where('id', $productId);
            }

            // Apply filters
            if (isset($filters['seller_id'])) {
                $query->where('seller_id', $filters['seller_id']);
            }

            if (isset($filters['category'])) {
                $query->where('category', $filters['category']);
            }

            if (isset($filters['status'])) {
                $query->where('status', $filters['status']);
            }

            if (isset($filters['in_stock'])) {
                if ($filters['in_stock']) {
                    $query->where('stock', '>', 0);
                } else {
                    $query->where('stock', '<=', 0);
                }
            }

            if (isset($filters['search'])) {
                $query->where(function($q) use ($filters) {
                    $q->where('name', 'like', '%' . $filters['search'] . '%')
                      ->orWhere('description', 'like', '%' . $filters['search'] . '%');
                });
            }

            $products = $query->paginate($filters['per_page'] ?? 15);

            $productData = $products->map(function($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'description' => $product->description,
                    'price' => $product->price,
                    'stock' => $product->stock,
                    'category' => $product->category,
                    'image' => $product->image,
                    'status' => $product->status,
                    'seller' => [
                        'id' => $product->seller->id,
                        'business_name' => $product->seller->business_name,
                        'email' => $product->seller->user->email
                    ],
                    'shop' => [
                        'id' => $product->shop->id,
                        'name' => $product->shop->name
                    ],
                    'inventory' => [
                        'sku' => $product->sku,
                        'barcode' => $product->barcode,
                        'weight' => $product->weight,
                        'dimensions' => $product->dimensions,
                        'is_digital' => $product->is_digital,
                        'download_link' => $product->download_link
                    ],
                    'details' => [
                        'brand' => $product->brand,
                        'model' => $product->model,
                        'condition' => $product->condition,
                        'tags' => $product->tags,
                        'is_featured' => $product->is_featured
                    ],
                    'seo' => [
                        'meta_title' => $product->meta_title,
                        'meta_description' => $product->meta_description,
                        'seo_keywords' => $product->seo_keywords
                    ],
                    'reviews' => [
                        'average_rating' => $product->average_rating,
                        'review_count' => $product->review_count,
                        'positive_review_count' => $product->positive_review_count,
                        'negative_review_count' => $product->negative_review_count,
                        'review_summary' => $product->review_summary,
                        'rating_distribution' => $product->rating_distribution
                    ],
                    'created_at' => $product->created_at->toISOString(),
                    'updated_at' => $product->updated_at->toISOString()
                ];
            });

            return [
                'products' => $productData,
                'pagination' => [
                    'current_page' => $products->currentPage(),
                    'last_page' => $products->lastPage(),
                    'per_page' => $products->perPage(),
                    'total' => $products->total()
                ]
            ];

        } catch (\Exception $e) {
            Log::error("Failed to export product data: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Export review data for Core Transaction 2 & 3
     */
    public function exportReviewData($reviewId = null, $filters = [])
    {
        try {
            $query = Review::with(['user', 'product.seller']);

            if ($reviewId) {
                $query->where('id', $reviewId);
            }

            // Apply filters
            if (isset($filters['product_id'])) {
                $query->where('product_id', $filters['product_id']);
            }

            if (isset($filters['user_id'])) {
                $query->where('user_id', $filters['user_id']);
            }

            if (isset($filters['rating'])) {
                $query->where('rating', $filters['rating']);
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

            $reviews = $query->paginate($filters['per_page'] ?? 15);

            $reviewData = $reviews->map(function($review) {
                return [
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
                    'created_at' => $review->created_at->toISOString(),
                    'updated_at' => $review->updated_at->toISOString()
                ];
            });

            return [
                'reviews' => $reviewData,
                'pagination' => [
                    'current_page' => $reviews->currentPage(),
                    'last_page' => $reviews->lastPage(),
                    'per_page' => $reviews->perPage(),
                    'total' => $reviews->total()
                ]
            ];

        } catch (\Exception $e) {
            Log::error("Failed to export review data: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Export marketplace analytics for Core Transaction 2 & 3
     */
    public function exportMarketplaceAnalytics($filters = [])
    {
        try {
            $startDate = $filters['date_from'] ?? now()->startOfMonth();
            $endDate = $filters['date_to'] ?? now()->endOfMonth();

            $analytics = [
                'period' => [
                    'start_date' => $startDate,
                    'end_date' => $endDate
                ],
                'customers' => [
                    'total_customers' => User::count(),
                    'new_customers' => User::whereBetween('created_at', [$startDate, $endDate])->count(),
                    'active_customers' => User::whereHas('orders', function($q) use ($startDate, $endDate) {
                        $q->whereBetween('created_at', [$startDate, $endDate]);
                    })->count(),
                    'customer_growth_rate' => $this->calculateGrowthRate(
                        User::where('created_at', '<', $startDate)->count(),
                        User::whereBetween('created_at', [$startDate, $endDate])->count()
                    )
                ],
                'orders' => [
                    'total_orders' => Order::count(),
                    'orders_in_period' => Order::whereBetween('created_at', [$startDate, $endDate])->count(),
                    'completed_orders' => Order::where('status', 'completed')->whereBetween('created_at', [$startDate, $endDate])->count(),
                    'cancelled_orders' => Order::where('status', 'cancelled')->whereBetween('created_at', [$startDate, $endDate])->count(),
                    'average_order_value' => Order::whereBetween('created_at', [$startDate, $endDate])->avg('total') ?? 0,
                    'total_revenue' => Order::where('status', 'completed')->whereBetween('created_at', [$startDate, $endDate])->sum('total')
                ],
                'products' => [
                    'total_products' => Product::count(),
                    'active_products' => Product::where('status', 'active')->count(),
                    'out_of_stock' => Product::where('stock', '<=', 0)->count(),
                    'top_categories' => Product::select('category', DB::raw('count(*) as count'))
                        ->whereBetween('created_at', [$startDate, $endDate])
                        ->groupBy('category')
                        ->orderBy('count', 'desc')
                        ->limit(10)
                        ->get()
                        ->pluck('count', 'category')
                        ->toArray()
                ],
                'reviews' => [
                    'total_reviews' => Review::count(),
                    'reviews_in_period' => Review::whereBetween('created_at', [$startDate, $endDate])->count(),
                    'average_rating' => Review::whereBetween('created_at', [$startDate, $endDate])->avg('rating') ?? 0,
                    'rating_distribution' => Review::whereBetween('created_at', [$startDate, $endDate])
                        ->selectRaw('rating, count(*) as count')
                        ->groupBy('rating')
                        ->orderBy('rating', 'desc')
                        ->pluck('count', 'rating')
                        ->toArray()
                ],
                'conversion' => [
                    'cart_to_order_rate' => $this->calculateConversionRate('cart_to_order', $startDate, $endDate),
                    'visitor_to_customer_rate' => $this->calculateConversionRate('visitor_to_customer', $startDate, $endDate),
                    'repeat_customer_rate' => $this->calculateRepeatCustomerRate($startDate, $endDate)
                ],
                'geographic' => [
                    'top_cities' => User::select('city', DB::raw('count(*) as count'))
                        ->whereNotNull('city')
                        ->whereBetween('created_at', [$startDate, $endDate])
                        ->groupBy('city')
                        ->orderBy('count', 'desc')
                        ->limit(10)
                        ->get()
                        ->pluck('count', 'city')
                        ->toArray(),
                    'top_states' => User::select('state', DB::raw('count(*) as count'))
                        ->whereNotNull('state')
                        ->whereBetween('created_at', [$startDate, $endDate])
                        ->groupBy('state')
                        ->orderBy('count', 'desc')
                        ->limit(10)
                        ->get()
                        ->pluck('count', 'state')
                        ->toArray()
                ]
            ];

            return $analytics;

        } catch (\Exception $e) {
            Log::error("Failed to export marketplace analytics: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Export real-time events for Core Transaction 2 & 3
     */
    public function exportRealTimeEvents($lastEventId = null, $eventTypes = [])
    {
        try {
            $query = DB::table('core_transaction_1_events')
                ->where('id', '>', $lastEventId ?? 0);

            if (!empty($eventTypes)) {
                $query->whereIn('event_type', $eventTypes);
            }

            $events = $query->orderBy('id', 'asc')
                ->limit(100)
                ->get();

            return $events->map(function($event) {
                return [
                    'id' => $event->id,
                    'event_type' => $event->event_type,
                    'entity_type' => $event->entity_type,
                    'entity_id' => $event->entity_id,
                    'data' => json_decode($event->data, true),
                    'created_at' => $event->created_at
                ];
            });

        } catch (\Exception $e) {
            Log::error("Failed to export real-time events: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Calculate growth rate
     */
    private function calculateGrowthRate($previous, $current)
    {
        if ($previous == 0) {
            return $current > 0 ? 100 : 0;
        }

        return round((($current - $previous) / $previous) * 100, 2);
    }

    /**
     * Calculate conversion rate
     */
    private function calculateConversionRate($type, $startDate, $endDate)
    {
        switch ($type) {
            case 'cart_to_order':
                $carts = Cart::whereBetween('created_at', [$startDate, $endDate])->count();
                $orders = Order::whereBetween('created_at', [$startDate, $endDate])->count();
                return $carts > 0 ? round(($orders / $carts) * 100, 2) : 0;

            case 'visitor_to_customer':
                // This would require tracking visitors, which might not be available
                $customers = User::whereBetween('created_at', [$startDate, $endDate])->count();
                // Assuming 10:1 visitor to customer ratio for demo
                $visitors = $customers * 10;
                return $visitors > 0 ? round(($customers / $visitors) * 100, 2) : 0;

            default:
                return 0;
        }
    }

    /**
     * Calculate repeat customer rate
     */
    private function calculateRepeatCustomerRate($startDate, $endDate)
    {
        $totalCustomers = User::whereHas('orders', function($q) use ($startDate, $endDate) {
            $q->whereBetween('created_at', [$startDate, $endDate]);
        })->count();

        $repeatCustomers = User::whereHas('orders', function($q) use ($startDate, $endDate) {
            $q->whereBetween('created_at', [$startDate, $endDate]);
        })->havingRaw('COUNT(orders.id) > 1')->count();

        return $totalCustomers > 0 ? round(($repeatCustomers / $totalCustomers) * 100, 2) : 0;
    }

    /**
     * Log event for real-time tracking
     */
    public function logEvent($eventType, $entityType, $entityId, $data)
    {
        try {
            DB::table('core_transaction_1_events')->insert([
                'event_type' => $eventType,
                'entity_type' => $entityType,
                'entity_id' => $entityId,
                'data' => json_encode($data),
                'created_at' => now(),
                'updated_at' => now()
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to log event: " . $e->getMessage());
        }
    }
}
