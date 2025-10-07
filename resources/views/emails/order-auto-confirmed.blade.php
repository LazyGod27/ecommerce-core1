<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Auto-Confirmed</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .content {
            background: #f9f9f9;
            padding: 30px;
            border-radius: 0 0 10px 10px;
        }
        .order-info {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #667eea;
        }
        .item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #eee;
        }
        .item:last-child {
            border-bottom: none;
        }
        .item-name {
            font-weight: bold;
            color: #333;
        }
        .item-details {
            color: #666;
            font-size: 14px;
        }
        .total {
            background: #667eea;
            color: white;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin: 20px 0;
        }
        .notice {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            color: #666;
            font-size: 14px;
            margin-top: 30px;
        }
        .btn {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 6px;
            margin: 10px 5px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📦 Order Auto-Confirmed</h1>
        <p>Your order has been automatically confirmed as received</p>
    </div>

    <div class="content">
        <h2>Hello {{ $customer->name }}!</h2>
        
        <p>We're writing to inform you that your order <strong>#{{ $order->order_number }}</strong> has been automatically confirmed as received.</p>

        <div class="notice">
            <strong>⏰ Important Notice:</strong><br>
            Since you didn't respond within 7 days of delivery, we have automatically marked your order as completed. This is our standard policy to ensure smooth order processing.
        </div>

        <div class="order-info">
            <h3>Order Details</h3>
            <p><strong>Order Number:</strong> #{{ $order->order_number }}</p>
            <p><strong>Order Date:</strong> {{ $order->created_at->format('F j, Y \a\t g:i A') }}</p>
            <p><strong>Delivery Date:</strong> {{ $order->delivered_at->format('F j, Y \a\t g:i A') }}</p>
            <p><strong>Auto-Confirmed:</strong> {{ $order->customer_response_at->format('F j, Y \a\t g:i A') }}</p>
        </div>

        <div class="order-info">
            <h3>Order Items</h3>
            @foreach($items as $item)
                <div class="item">
                    <div>
                        <div class="item-name">{{ $item->product->name }}</div>
                        <div class="item-details">Quantity: {{ $item->quantity }} × ${{ number_format($item->price, 2) }}</div>
                    </div>
                    <div style="font-weight: bold;">${{ number_format($item->quantity * $item->price, 2) }}</div>
                </div>
            @endforeach
            
            <div class="total">
                Total: ${{ number_format($order->total, 2) }}
            </div>
        </div>

        <div class="order-info">
            <h3>Shipping Address</h3>
            <p>{{ $order->shipping_address }}</p>
            @if($order->contact_number)
                <p><strong>Contact:</strong> {{ $order->contact_number }}</p>
            @endif
        </div>

        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ route('orders.show', $order) }}" class="btn">View Order Details</a>
            <a href="{{ route('orders.index') }}" class="btn">View All Orders</a>
        </div>

        <div class="notice">
            <strong>💡 Need Help?</strong><br>
            If you have any questions about this order or need assistance, please don't hesitate to contact our customer support team. We're here to help!
        </div>

        <div class="footer">
            <p>Thank you for choosing our store!</p>
            <p>This is an automated message. Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>
