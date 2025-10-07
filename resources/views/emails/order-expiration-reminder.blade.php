<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation Reminder</title>
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
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
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
            border-left: 4px solid #f5576c;
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
            background: #f5576c;
            color: white;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin: 20px 0;
        }
        .urgent {
            background: #ff6b6b;
            color: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            margin: 20px 0;
            font-size: 18px;
            font-weight: bold;
        }
        .action-buttons {
            text-align: center;
            margin: 30px 0;
        }
        .btn {
            display: inline-block;
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 6px;
            margin: 10px 5px;
            font-weight: bold;
        }
        .btn-confirm {
            background: #28a745;
        }
        .btn-return {
            background: #ffc107;
            color: #333;
        }
        .btn-view {
            background: #6c757d;
        }
        .footer {
            text-align: center;
            color: #666;
            font-size: 14px;
            margin-top: 30px;
        }
        .countdown {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>⏰ Order Confirmation Reminder</h1>
        <p>Your order confirmation deadline is approaching</p>
    </div>

    <div class="content">
        <h2>Hello {{ $customer->name }}!</h2>
        
        <p>We're writing to remind you that your order <strong>#{{ $order->order_number }}</strong> was delivered and is waiting for your confirmation.</p>

        <div class="urgent">
            ⚠️ ACTION REQUIRED ⚠️<br>
            Please confirm receipt or request a return within the next 24 hours
        </div>

        <div class="countdown">
            <strong>⏰ Confirmation Deadline:</strong><br>
            {{ $deadline->format('F j, Y \a\t g:i A') }}<br>
            <small>({{ $deadline->diffForHumans() }})</small>
        </div>

        <div class="order-info">
            <h3>Order Details</h3>
            <p><strong>Order Number:</strong> #{{ $order->order_number }}</p>
            <p><strong>Order Date:</strong> {{ $order->created_at->format('F j, Y \a\t g:i A') }}</p>
            <p><strong>Delivery Date:</strong> {{ $order->delivered_at->format('F j, Y \a\t g:i A') }}</p>
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

        <div class="action-buttons">
            <h3>What would you like to do?</h3>
            <p>Please choose one of the following options:</p>
            
            <a href="{{ route('orders.confirm-received', $order) }}" class="btn btn-confirm">
                ✅ Confirm Item Received
            </a>
            
            <a href="{{ route('orders.request-return', $order) }}" class="btn btn-return">
                🔄 Request Return/Refund
            </a>
            
            <a href="{{ route('orders.show', $order) }}" class="btn btn-view">
                👁️ View Order Details
            </a>
        </div>

        <div class="order-info">
            <h3>What happens if I don't respond?</h3>
            <p>If you don't take any action within the deadline, your order will be automatically confirmed as received. This is our standard policy to ensure smooth order processing.</p>
        </div>

        <div class="order-info">
            <h3>Need Help?</h3>
            <p>If you have any questions or need assistance, please contact our customer support team. We're here to help!</p>
        </div>

        <div class="footer">
            <p>Thank you for choosing our store!</p>
            <p>This is an automated reminder. Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>
