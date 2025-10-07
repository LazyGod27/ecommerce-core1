<?php

/**
 * Create Test Delivered Order for Refund Testing
 * 
 * This script creates a dummy delivered order for testing refund functionality
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

echo "🧪 Creating Test Delivered Order for Refund Testing\n";
echo "==================================================\n\n";

try {
    // Get or create a test user
    $user = User::first();
    if (!$user) {
        echo "❌ No users found. Please create a user first.\n";
        exit(1);
    }
    
    echo "✅ Using user: {$user->name} ({$user->email})\n";
    
    // Create a test order
    $order = Order::create([
        'user_id' => $user->id,
        'order_number' => 'TEST-ORD-' . strtoupper(uniqid()),
        'status' => 'delivered',
        'shipping_address' => '123 Test Street, Test City, Test State 12345',
        'contact_number' => '+1234567890',
        'email' => $user->email,
        'payment_method' => 'GCash',
        'payment_status' => 'paid',
        'subtotal' => 299.99,
        'tax' => 30.00,
        'shipping_cost' => 50.00,
        'total' => 379.99,
        'notes' => 'Test order for refund functionality testing',
        'paid_at' => now()->subDays(2),
        'delivered_at' => now()->subDays(1), // Delivered 1 day ago
        'delivery_confirmation_deadline' => now()->addDays(6), // 6 days left to respond
        'delivery_status' => 'delivered',
        'return_status' => 'none'
    ]);
    
    echo "✅ Created test order: {$order->order_number}\n";
    echo "   Order ID: {$order->id}\n";
    echo "   Total: $" . number_format($order->total, 2) . "\n";
    echo "   Status: {$order->status}\n";
    echo "   Delivery Status: {$order->delivery_status}\n";
    echo "   Delivered At: {$order->delivered_at}\n";
    echo "   Response Deadline: {$order->delivery_confirmation_deadline}\n";
    echo "   Days Left to Respond: " . $order->delivery_confirmation_deadline->diffInDays(now()) . "\n\n";
    
    // Create test order items
    $testProducts = [
        [
            'name' => 'Test Product 1 - Wireless Headphones',
            'price' => 199.99,
            'quantity' => 1
        ],
        [
            'name' => 'Test Product 2 - Smart Watch',
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
        'tracking_number' => 'TEST-TRK-' . strtoupper(uniqid()),
        'carrier' => 'Test Shipping',
        'status' => 'delivered',
        'estimated_delivery' => now()->subDays(1),
        'history' => [
            [
                'status' => 'Order Placed',
                'location' => 'Processing Center',
                'timestamp' => now()->subDays(3)->toISOString(),
                'description' => 'Your order has been received and is being processed.'
            ],
            [
                'status' => 'Shipped',
                'location' => 'Distribution Center',
                'timestamp' => now()->subDays(2)->toISOString(),
                'description' => 'Your order has been shipped and is on its way.'
            ],
            [
                'status' => 'Out for Delivery',
                'location' => 'Local Delivery Hub',
                'timestamp' => now()->subDays(1)->addHours(8)->toISOString(),
                'description' => 'Your order is out for delivery.'
            ],
            [
                'status' => 'Delivered',
                'location' => 'Delivery Address',
                'timestamp' => now()->subDays(1)->addHours(12)->toISOString(),
                'description' => 'Your order has been delivered successfully.'
            ]
        ]
    ]);
    
    echo "✅ Created tracking record: {$tracking->tracking_number}\n";
    echo "   Status: {$tracking->status}\n";
    echo "   Carrier: {$tracking->carrier}\n\n";
    
    // Display testing instructions
    echo "🎯 Testing Instructions:\n";
    echo "========================\n";
    echo "1. Login as user: {$user->email}\n";
    echo "2. Go to: /orders-waiting-response\n";
    echo "3. You should see the test order waiting for response\n";
    echo "4. Click on the order to see response options\n";
    echo "5. Test both 'Confirm Received' and 'Request Return' options\n\n";
    
    echo "🔗 Direct Links:\n";
    echo "===============\n";
    echo "Orders waiting for response: http://localhost/ecommerce-core1/orders-waiting-response\n";
    echo "Order response form: http://localhost/ecommerce-core1/orders/{$order->id}/response\n";
    echo "Order details: http://localhost/ecommerce-core1/orders/{$order->id}\n\n";
    
    echo "📊 Order Summary:\n";
    echo "=================\n";
    echo "Order Number: {$order->order_number}\n";
    echo "Customer: {$user->name} ({$user->email})\n";
    echo "Total: $" . number_format($order->total, 2) . "\n";
    echo "Status: {$order->status}\n";
    echo "Delivery Status: {$order->delivery_status}\n";
    echo "Response Deadline: {$order->delivery_confirmation_deadline->format('M j, Y g:i A')}\n";
    echo "Days Left: " . $order->delivery_confirmation_deadline->diffInDays(now()) . " days\n";
    echo "Tracking Number: {$tracking->tracking_number}\n\n";
    
    echo "✅ Test delivered order created successfully!\n";
    echo "You can now test the refund functionality.\n";
    
} catch (Exception $e) {
    echo "❌ Error creating test order: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
    exit(1);
}
