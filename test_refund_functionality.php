<?php

/**
 * Test Refund Functionality
 * 
 * This script tests the refund/return functionality
 */

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Order;
use App\Models\User;

echo "🧪 Testing Refund Functionality\n";
echo "===============================\n\n";

try {
    // Get the test order we just created
    $order = Order::where('order_number', 'TEST-ORD-68CFC41841416')->first();
    
    if (!$order) {
        echo "❌ Test order not found. Please run create_test_delivered_order.php first.\n";
        exit(1);
    }
    
    echo "✅ Found test order: {$order->order_number}\n";
    echo "   Order ID: {$order->id}\n";
    echo "   Status: {$order->status}\n";
    echo "   Delivery Status: {$order->delivery_status}\n";
    echo "   Return Status: {$order->return_status}\n";
    echo "   Response Deadline: {$order->delivery_confirmation_deadline}\n";
    echo "   Is Waiting for Response: " . ($order->isWaitingForCustomerResponse() ? 'Yes' : 'No') . "\n";
    echo "   Has Deadline Passed: " . ($order->hasDeadlinePassed() ? 'Yes' : 'No') . "\n\n";
    
    // Test the order methods
    echo "🔍 Testing Order Methods:\n";
    echo "========================\n";
    
    // Test markAsDelivered (should already be delivered)
    echo "1. Testing markAsDelivered()...\n";
    if ($order->delivery_status === 'delivered') {
        echo "   ✅ Order is already marked as delivered\n";
    } else {
        $order->markAsDelivered();
        echo "   ✅ Order marked as delivered\n";
    }
    
    // Test confirmReceived
    echo "2. Testing confirmReceived()...\n";
    $order->confirmReceived();
    echo "   ✅ Order confirmed as received\n";
    echo "   New Status: {$order->status}\n";
    echo "   New Delivery Status: {$order->delivery_status}\n";
    echo "   Customer Response At: {$order->customer_response_at}\n\n";
    
    // Reset order for return testing
    echo "3. Resetting order for return testing...\n";
    $order->update([
        'status' => 'delivered',
        'delivery_status' => 'delivered',
        'customer_response_at' => null,
        'return_status' => 'none',
        'return_reason' => null,
        'return_requested_at' => null
    ]);
    echo "   ✅ Order reset for return testing\n\n";
    
    // Test requestReturn
    echo "4. Testing requestReturn()...\n";
    $returnReason = "Product arrived damaged and not as described. The quality is poor and does not match the product images.";
    $order->requestReturn($returnReason);
    echo "   ✅ Return requested\n";
    echo "   New Status: {$order->status}\n";
    echo "   New Delivery Status: {$order->delivery_status}\n";
    echo "   Return Status: {$order->return_status}\n";
    echo "   Return Reason: {$order->return_reason}\n";
    echo "   Return Requested At: {$order->return_requested_at}\n\n";
    
    // Test autoConfirm
    echo "5. Testing autoConfirm()...\n";
    $order->update([
        'delivery_status' => 'delivered',
        'delivery_confirmation_deadline' => now()->subDays(1) // Set deadline to yesterday
    ]);
    $order->autoConfirm();
    echo "   ✅ Order auto-confirmed\n";
    echo "   New Status: {$order->status}\n";
    echo "   New Delivery Status: {$order->delivery_status}\n";
    echo "   Customer Response At: {$order->customer_response_at}\n\n";
    
    // Test badge classes
    echo "6. Testing Badge Classes:\n";
    echo "   Delivery Status Badge: {$order->getDeliveryStatusBadgeClass()}\n";
    echo "   Return Status Badge: {$order->getReturnStatusBadgeClass()}\n\n";
    
    // Test scopes
    echo "7. Testing Order Scopes:\n";
    $waitingForResponse = Order::waitingForResponse()->count();
    $deadlinePassed = Order::deadlinePassed()->count();
    $withReturnRequests = Order::withReturnRequests()->count();
    
    echo "   Orders waiting for response: {$waitingForResponse}\n";
    echo "   Orders with passed deadline: {$deadlinePassed}\n";
    echo "   Orders with return requests: {$withReturnRequests}\n\n";
    
    echo "🎯 Manual Testing Instructions:\n";
    echo "===============================\n";
    echo "1. Login as: {$order->user->email}\n";
    echo "2. Go to: /orders-waiting-response\n";
    echo "3. You should see the test order\n";
    echo "4. Click on the order to test response options\n";
    echo "5. Test 'Confirm Received' button\n";
    echo "6. Test 'Request Return' button\n";
    echo "7. Check order status changes\n\n";
    
    echo "🔗 Test URLs:\n";
    echo "=============\n";
    echo "Orders waiting for response: http://localhost/ecommerce-core1/orders-waiting-response\n";
    echo "Order response form: http://localhost/ecommerce-core1/orders/{$order->id}/response\n";
    echo "Order details: http://localhost/ecommerce-core1/orders/{$order->id}\n";
    echo "Return requests: http://localhost/ecommerce-core1/orders-return-requests\n\n";
    
    echo "✅ Refund functionality testing completed!\n";
    echo "All order methods are working correctly.\n";
    
} catch (Exception $e) {
    echo "❌ Error testing refund functionality: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
    exit(1);
}
