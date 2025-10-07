<?php

/**
 * Create Fresh Test Order for Full Refund Testing
 * 
 * This script creates a fresh delivered order that's still waiting for customer response
 */

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Tracking;
use Carbon\Carbon;

echo "🧪 Creating Fresh Test Order for Full Refund Testing\n";
echo "====================================================\n\n";

try {
    // Get or create a test user
    $user = User::first();
    if (!$user) {
        echo "❌ No users found. Please create a user first.\n";
        exit(1);
    }
    
    echo "✅ Using user: {$user->name} ({$user->email})\n";
    
    // Create a fresh test order that's delivered but still waiting for response
    $order = Order::create([
        'user_id' => $user->id,
        'order_number' => 'FRESH-TEST-' . strtoupper(uniqid()),
        'status' => 'delivered',
        'shipping_address' => '456 Fresh Test Avenue, Test City, Test State 54321',
        'contact_number' => '+1234567890',
        'email' => $user->email,
        'payment_method' => 'PayMaya',
        'payment_status' => 'paid',
        'subtotal' => 599.99,
        'tax' => 60.00,
        'shipping_cost' => 75.00,
        'total' => 734.99,
        'notes' => 'Fresh test order for full refund functionality testing',
        'paid_at' => now()->subDays(1),
        'delivered_at' => now()->subHours(2), // Delivered 2 hours ago
        'delivery_confirmation_deadline' => now()->addDays(7), // 7 days to respond
        'delivery_status' => 'delivered',
        'return_status' => 'none'
    ]);
    
    echo "✅ Created fresh test order: {$order->order_number}\n";
    echo "   Order ID: {$order->id}\n";
    echo "   Total: $" . number_format($order->total, 2) . "\n";
    echo "   Status: {$order->status}\n";
    echo "   Delivery Status: {$order->delivery_status}\n";
    echo "   Delivered At: {$order->delivered_at}\n";
    echo "   Response Deadline: {$order->delivery_confirmation_deadline}\n";
    echo "   Hours Left to Respond: " . round($order->delivery_confirmation_deadline->diffInHours(now())) . "\n\n";
    
    // Create test order items
    $testProducts = [
        [
            'name' => 'Premium Wireless Earbuds Pro',
            'price' => 299.99,
            'quantity' => 1
        ],
        [
            'name' => 'Smart Fitness Tracker',
            'price' => 200.00,
            'quantity' => 1
        ],
        [
            'name' => 'Portable Phone Charger',
            'price' => 100.00,
            'quantity' => 1
        ]
    ];
    
    foreach ($testProducts as $product) {
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => null, // No real product for test
            'product_name' => $product['name'],
            'quantity' => $product['quantity'],
            'price' => $product['price']
        ]);
        
        echo "✅ Added order item: {$product['name']} - $" . number_format($product['price'], 2) . "\n";
    }
    
    // Create tracking record
    $tracking = Tracking::create([
        'order_id' => $order->id,
        'tracking_number' => 'FRESH-TRK-' . strtoupper(uniqid()),
        'carrier' => 'Express Delivery',
        'status' => 'delivered',
        'estimated_delivery' => now()->subHours(2),
        'history' => [
            [
                'status' => 'Order Placed',
                'location' => 'Processing Center',
                'timestamp' => now()->subDays(1)->toISOString(),
                'description' => 'Your order has been received and is being processed.'
            ],
            [
                'status' => 'Shipped',
                'location' => 'Distribution Center',
                'timestamp' => now()->subHours(8)->toISOString(),
                'description' => 'Your order has been shipped and is on its way.'
            ],
            [
                'status' => 'Out for Delivery',
                'location' => 'Local Delivery Hub',
                'timestamp' => now()->subHours(3)->toISOString(),
                'description' => 'Your order is out for delivery.'
            ],
            [
                'status' => 'Delivered',
                'location' => 'Delivery Address',
                'timestamp' => now()->subHours(2)->toISOString(),
                'description' => 'Your order has been delivered successfully.'
            ]
        ]
    ]);
    
    echo "✅ Created tracking record: {$tracking->tracking_number}\n";
    echo "   Status: {$tracking->status}\n";
    echo "   Carrier: {$tracking->carrier}\n\n";
    
    // Display testing instructions
    echo "🎯 Full Testing Instructions:\n";
    echo "=============================\n";
    echo "1. Login as user: {$user->email}\n";
    echo "2. Go to: /orders-waiting-response\n";
    echo "3. You should see the fresh test order waiting for response\n";
    echo "4. Click on the order to see response options\n";
    echo "5. Test 'Confirm Received' option:\n";
    echo "   - Click 'Confirm Received'\n";
    echo "   - Check if order status changes to 'completed'\n";
    echo "   - Verify delivery status changes to 'confirmed_received'\n";
    echo "6. Test 'Request Return' option:\n";
    echo "   - Click 'Request Return'\n";
    echo "   - Fill out the return reason form\n";
    echo "   - Submit the return request\n";
    echo "   - Check if return status changes to 'requested'\n";
    echo "7. Test auto-confirmation (after 7 days):\n";
    echo "   - Run: php artisan orders:auto-complete\n";
    echo "   - Check if order auto-confirms after deadline\n\n";
    
    echo "🔗 Direct Test Links:\n";
    echo "====================\n";
    echo "Orders waiting for response: http://localhost/ecommerce-core1/orders-waiting-response\n";
    echo "Order response form: http://localhost/ecommerce-core1/orders/{$order->id}/response\n";
    echo "Order details: http://localhost/ecommerce-core1/orders/{$order->id}\n";
    echo "Return requests: http://localhost/ecommerce-core1/orders-return-requests\n\n";
    
    echo "📊 Fresh Order Summary:\n";
    echo "=======================\n";
    echo "Order Number: {$order->order_number}\n";
    echo "Customer: {$user->name} ({$user->email})\n";
    echo "Total: $" . number_format($order->total, 2) . "\n";
    echo "Status: {$order->status}\n";
    echo "Delivery Status: {$order->delivery_status}\n";
    echo "Response Deadline: {$order->delivery_confirmation_deadline->format('M j, Y g:i A')}\n";
    echo "Hours Left: " . round($order->delivery_confirmation_deadline->diffInHours(now())) . " hours\n";
    echo "Tracking Number: {$tracking->tracking_number}\n";
    echo "Items: " . count($testProducts) . " products\n\n";
    
    echo "✅ Fresh test order created successfully!\n";
    echo "You can now test the complete refund functionality flow.\n";
    
} catch (Exception $e) {
    echo "❌ Error creating fresh test order: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
    exit(1);
}
