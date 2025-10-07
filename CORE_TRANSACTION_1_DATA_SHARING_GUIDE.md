# 🔗 Core Transaction 1 Data Sharing Guide

## 📋 Overview

This guide explains how **Core Transaction 1** (Marketplace & Customer Experience) can share data with **Core Transaction 2** (Seller Operations & Fulfillment) and **Core Transaction 3** (Platform Control & Revenue Management) systems.

## 🏗️ Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                Core Transaction 1                           │
│            (Marketplace & Customer Experience)              │
│                                                             │
│  ┌─────────────────┐  ┌─────────────────┐  ┌─────────────┐ │
│  │   Customers     │  │     Orders      │  │  Products   │ │
│  │   Reviews       │  │     Cart        │  │  Analytics  │ │
│  └─────────────────┘  └─────────────────┘  └─────────────┘ │
│           │                     │                     │     │
│           └─────────────────────┼─────────────────────┘     │
│                                 │                         │
│  ┌─────────────────────────────▼─────────────────────────┐ │
│  │           Data Export & Webhook System                │ │
│  │  • CoreTransaction1DataExporter                       │ │
│  │  • CoreTransaction1WebhookSender                      │ │
│  │  • Real-time Events                                   │ │
│  └─────────────────────────────┬─────────────────────────┘ │
└─────────────────────────────────┼───────────────────────────┘
                                  │
                    ┌─────────────┼─────────────┐
                    │             │             │
        ┌───────────▼──┐  ┌───────▼───────┐  ┌──▼──────────┐
        │ Core Trans 2 │  │ Core Trans 3  │  │   Real-time │
        │ (Seller Ops) │  │ (Platform)    │  │   Events    │
        └──────────────┘  └───────────────┘  └─────────────┘
```

## 🚀 Quick Setup

### 1. Generate API Keys

```bash
# Generate API key for Core Transaction 1 data export
php artisan api:generate --name="Core Transaction 1 Export" --type=external --permissions=core_transaction_1_export
```

### 2. Configure Webhook URLs

Add to your `.env` file:

```env
# Core Transaction 2 Webhook Configuration
CORE_TRANSACTION_2_WEBHOOK_URL=https://core-transaction-2.example.com/webhooks/ecommerce-updates
CORE_TRANSACTION_2_WEBHOOK_SECRET=your_secret_key_here

# Core Transaction 3 Webhook Configuration
CORE_TRANSACTION_3_WEBHOOK_URL=https://core-transaction-3.example.com/webhooks/ecommerce-updates
CORE_TRANSACTION_3_WEBHOOK_SECRET=your_secret_key_here

# Webhook Settings
WEBHOOK_TIMEOUT=30
WEBHOOK_RETRY_ATTEMPTS=3
WEBHOOK_RETRY_DELAY=5
```

### 3. Run Migrations

```bash
php artisan migrate
```

## 📊 Data Export Methods

### 1. Customer Data Export

**Endpoint**: `GET /api/core-transaction-1/customers`

**Query Parameters**:
- `date_from` - Filter customers created from date
- `date_to` - Filter customers created to date
- `has_orders` - Filter customers with/without orders
- `per_page` - Number of customers per page

**Example Request**:
```bash
curl -X GET "https://your-domain.com/api/core-transaction-1/customers?has_orders=true&per_page=20" \
  -H "X-API-Key: your_api_key" \
  -H "X-API-Secret: your_api_secret"
```

**Response**:
```json
{
    "success": true,
    "data": {
        "customers": [
            {
                "id": 1,
                "name": "John Doe",
                "email": "john@example.com",
                "phone": "+1234567890",
                "address": {
                    "line1": "123 Main St",
                    "city": "New York",
                    "state": "NY",
                    "postal_code": "10001",
                    "country": "US",
                    "full_address": "123 Main St, New York, NY, 10001, US"
                },
                "profile": {
                    "birth_date": "1990-01-01",
                    "gender": "male",
                    "timezone": "America/New_York"
                },
                "statistics": {
                    "total_orders": 5,
                    "total_spent": 1250.00,
                    "average_order_value": 250.00,
                    "last_order_date": "2024-01-15 10:30:00"
                },
                "created_at": "2024-01-01T00:00:00.000000Z"
            }
        ],
        "pagination": {
            "current_page": 1,
            "last_page": 10,
            "per_page": 20,
            "total": 200
        }
    }
}
```

### 2. Order Data Export

**Endpoint**: `GET /api/core-transaction-1/orders`

**Query Parameters**:
- `customer_id` - Filter by customer ID
- `status` - Filter by order status
- `payment_status` - Filter by payment status
- `date_from` - Filter orders from date
- `date_to` - Filter orders to date
- `seller_id` - Filter by seller ID
- `per_page` - Number of orders per page

**Example Request**:
```bash
curl -X GET "https://your-domain.com/api/core-transaction-1/orders?status=completed&date_from=2024-01-01" \
  -H "X-API-Key: your_api_key" \
  -H "X-API-Secret: your_api_secret"
```

**Response**:
```json
{
    "success": true,
    "data": {
        "orders": [
            {
                "id": 1,
                "order_number": "ORD-2024-001",
                "status": "completed",
                "payment_status": "paid",
                "customer": {
                    "id": 1,
                    "name": "John Doe",
                    "email": "john@example.com",
                    "phone": "+1234567890"
                },
                "shipping": {
                    "address": {
                        "line1": "123 Main St",
                        "city": "New York",
                        "state": "NY",
                        "postal_code": "10001"
                    },
                    "contact_number": "+1234567890",
                    "tracking": {
                        "tracking_number": "TRK123456789",
                        "carrier": "J&T Express",
                        "status": "delivered"
                    }
                },
                "financial": {
                    "subtotal": 100.00,
                    "tax": 10.00,
                    "shipping_cost": 5.00,
                    "total": 115.00
                },
                "items": [
                    {
                        "id": 1,
                        "product_id": 1,
                        "product_name": "Gaming Mouse",
                        "quantity": 2,
                        "price": 50.00,
                        "subtotal": 100.00,
                        "seller": {
                            "id": 1,
                            "business_name": "TechStore PH"
                        }
                    }
                ],
                "created_at": "2024-01-15T10:30:00.000000Z"
            }
        ]
    }
}
```

### 3. Product Data Export

**Endpoint**: `GET /api/core-transaction-1/products`

**Query Parameters**:
- `seller_id` - Filter by seller ID
- `category` - Filter by category
- `status` - Filter by status
- `in_stock` - Filter by stock availability
- `search` - Search in name/description
- `per_page` - Number of products per page

**Example Request**:
```bash
curl -X GET "https://your-domain.com/api/core-transaction-1/products?category=Electronics&in_stock=true" \
  -H "X-API-Key: your_api_key" \
  -H "X-API-Secret: your_api_secret"
```

### 4. Review Data Export

**Endpoint**: `GET /api/core-transaction-1/reviews`

**Query Parameters**:
- `product_id` - Filter by product ID
- `user_id` - Filter by user ID
- `rating` - Filter by rating
- `status` - Filter by status
- `date_from` - Filter from date
- `date_to` - Filter to date
- `per_page` - Number of reviews per page

### 5. Analytics Export

**Endpoint**: `GET /api/core-transaction-1/analytics`

**Query Parameters**:
- `date_from` - Analytics from date
- `date_to` - Analytics to date

**Response**:
```json
{
    "success": true,
    "data": {
        "period": {
            "start_date": "2024-01-01",
            "end_date": "2024-01-31"
        },
        "customers": {
            "total_customers": 1000,
            "new_customers": 100,
            "active_customers": 500,
            "customer_growth_rate": 10.5
        },
        "orders": {
            "total_orders": 5000,
            "orders_in_period": 500,
            "completed_orders": 450,
            "cancelled_orders": 25,
            "average_order_value": 250.00,
            "total_revenue": 112500.00
        },
        "products": {
            "total_products": 10000,
            "active_products": 8000,
            "out_of_stock": 500,
            "top_categories": {
                "Electronics": 3000,
                "Fashion": 2500,
                "Home & Garden": 2000
            }
        },
        "reviews": {
            "total_reviews": 15000,
            "reviews_in_period": 1500,
            "average_rating": 4.2,
            "rating_distribution": {
                "5": 8000,
                "4": 4000,
                "3": 2000,
                "2": 800,
                "1": 200
            }
        },
        "conversion": {
            "cart_to_order_rate": 15.5,
            "visitor_to_customer_rate": 2.5,
            "repeat_customer_rate": 35.0
        }
    }
}
```

## 🔄 Real-time Webhook Events

### Event Types

| Event | Description | Sent To |
|-------|-------------|---------|
| `customer.created` | New customer registered | Core Trans 2, 3 |
| `customer.updated` | Customer profile updated | Core Trans 2, 3 |
| `order.created` | New order placed | Core Trans 2, 3 |
| `order.updated` | Order details updated | Core Trans 2, 3 |
| `order.status_changed` | Order status changed | Core Trans 2, 3 |
| `payment.status_changed` | Payment status changed | Core Trans 2, 3 |
| `product.viewed` | Product viewed by customer | Core Trans 2, 3 |
| `product.added_to_cart` | Product added to cart | Core Trans 2, 3 |
| `review.created` | New review submitted | Core Trans 2, 3 |
| `review.updated` | Review updated | Core Trans 2, 3 |
| `cart.abandoned` | Cart abandoned by customer | Core Trans 2, 3 |
| `marketplace.analytics` | Analytics data updated | Core Trans 3 |

### Webhook Payload Format

```json
{
    "event": "order.created",
    "timestamp": "2024-01-15T10:30:00.000000Z",
    "source": "core_transaction_1",
    "data": {
        "id": 1,
        "order_number": "ORD-2024-001",
        "status": "pending",
        "customer": {
            "id": 1,
            "name": "John Doe",
            "email": "john@example.com"
        },
        "items": [
            {
                "product_id": 1,
                "product_name": "Gaming Mouse",
                "quantity": 2,
                "price": 50.00,
                "seller": {
                    "id": 1,
                    "business_name": "TechStore PH"
                }
            }
        ],
        "total": 115.00,
        "created_at": "2024-01-15T10:30:00.000000Z"
    }
}
```

### Webhook Security

All webhooks include HMAC signature verification:

```http
X-Webhook-Signature: sha256=abc123def456...
Content-Type: application/json
User-Agent: Core-Transaction-1/1.0
```

## 🛠️ Integration Examples

### For Core Transaction 2 (Seller Operations)

```php
<?php

class CoreTransaction2Client
{
    private $baseUrl = 'https://ecommerce-platform.com/api/core-transaction-1';
    private $apiKey = 'your_api_key';
    private $apiSecret = 'your_api_secret';

    // Get orders for seller
    public function getSellerOrders($sellerId, $filters = [])
    {
        $filters['seller_id'] = $sellerId;
        $query = http_build_query($filters);
        
        return $this->get("/orders?{$query}");
    }

    // Get customer data
    public function getCustomer($customerId)
    {
        return $this->get("/customers/{$customerId}");
    }

    // Get product reviews
    public function getProductReviews($productId, $filters = [])
    {
        $filters['product_id'] = $productId;
        $query = http_build_query($filters);
        
        return $this->get("/reviews?{$query}");
    }

    // Get real-time events
    public function getRealTimeEvents($lastEventId = 0, $eventTypes = [])
    {
        $params = ['last_event_id' => $lastEventId];
        if (!empty($eventTypes)) {
            $params['event_types'] = $eventTypes;
        }
        
        $query = http_build_query($params);
        return $this->get("/events?{$query}");
    }

    private function get($endpoint)
    {
        $client = new \GuzzleHttp\Client();
        return $client->get($this->baseUrl . $endpoint, [
            'headers' => [
                'X-API-Key' => $this->apiKey,
                'X-API-Secret' => $this->apiSecret
            ]
        ]);
    }
}
```

### For Core Transaction 3 (Platform Control)

```php
<?php

class CoreTransaction3Client
{
    private $baseUrl = 'https://ecommerce-platform.com/api/core-transaction-1';
    private $apiKey = 'your_api_key';
    private $apiSecret = 'your_api_secret';

    // Get marketplace analytics
    public function getMarketplaceAnalytics($filters = [])
    {
        $query = http_build_query($filters);
        return $this->get("/analytics?{$query}");
    }

    // Get all customers
    public function getAllCustomers($filters = [])
    {
        $query = http_build_query($filters);
        return $this->get("/customers?{$query}");
    }

    // Get all orders
    public function getAllOrders($filters = [])
    {
        $query = http_build_query($filters);
        return $this->get("/orders?{$query}");
    }

    // Get all products
    public function getAllProducts($filters = [])
    {
        $query = http_build_query($filters);
        return $this->get("/products?{$query}");
    }

    // Get all reviews
    public function getAllReviews($filters = [])
    {
        $query = http_build_query($filters);
        return $this->get("/reviews?{$query}");
    }

    private function get($endpoint)
    {
        $client = new \GuzzleHttp\Client();
        return $client->get($this->baseUrl . $endpoint, [
            'headers' => [
                'X-API-Key' => $this->apiKey,
                'X-API-Secret' => $this->apiSecret
            ]
        ]);
    }
}
```

## 🔧 Webhook Implementation

### Webhook Endpoint Handler

```php
<?php

// In your Core Transaction 2 or 3 system
Route::post('/webhooks/ecommerce-updates', function(Request $request) {
    // Verify webhook signature
    $signature = $request->header('X-Webhook-Signature');
    $payload = $request->getContent();
    $expectedSignature = hash_hmac('sha256', $payload, config('webhooks.core_transaction_1_secret'));
    
    if (!hash_equals($signature, $expectedSignature)) {
        return response()->json(['error' => 'Invalid signature'], 401);
    }
    
    $data = $request->json()->all();
    $event = $data['event'];
    
    switch ($event) {
        case 'customer.created':
            $this->handleCustomerCreated($data['data']);
            break;
        case 'order.created':
            $this->handleOrderCreated($data['data']);
            break;
        case 'order.status_changed':
            $this->handleOrderStatusChanged($data['data']);
            break;
        case 'review.created':
            $this->handleReviewCreated($data['data']);
            break;
        case 'marketplace.analytics':
            $this->handleMarketplaceAnalytics($data['data']);
            break;
    }
    
    return response()->json(['success' => true]);
});
```

## 📈 Performance Features

### Caching
- Customer data cached for 1 hour
- Product data cached for 30 minutes
- Analytics data cached for 1 hour
- Real-time events cached for 5 minutes

### Database Optimization
- Indexed columns for frequently queried fields
- Eager loading to prevent N+1 queries
- Query optimization for better performance

## 🔒 Security Features

### API Key Security
- Secure API key generation
- Permission-based access control
- IP and domain restrictions
- Usage monitoring and logging

### Webhook Security
- HMAC signature verification
- HTTPS endpoint requirements
- Rate limiting protection
- Event logging and monitoring

## 📊 Monitoring & Analytics

### Webhook Monitoring
- Delivery status tracking
- Success rate monitoring
- Retry mechanism with exponential backoff
- Error logging and alerting

### API Usage Monitoring
- API key usage tracking
- Rate limiting monitoring
- Performance metrics
- Error rate monitoring

## 🧪 Testing

### Test Webhook Connectivity

```bash
curl -X POST "https://your-domain.com/api/core-transaction-1/webhooks/test" \
  -H "X-API-Key: your_api_key" \
  -H "X-API-Secret: your_api_secret" \
  -H "Content-Type: application/json" \
  -d '{"system": "core_transaction_2"}'
```

### Test Data Export

```bash
curl -X GET "https://your-domain.com/api/core-transaction-1/stats" \
  -H "X-API-Key: your_api_key" \
  -H "X-API-Secret: your_api_secret"
```

## 📚 API Reference

### Data Export Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/core-transaction-1/customers` | Export customer data |
| GET | `/api/core-transaction-1/customers/{id}` | Export specific customer |
| GET | `/api/core-transaction-1/orders` | Export order data |
| GET | `/api/core-transaction-1/orders/{id}` | Export specific order |
| GET | `/api/core-transaction-1/products` | Export product data |
| GET | `/api/core-transaction-1/products/{id}` | Export specific product |
| GET | `/api/core-transaction-1/reviews` | Export review data |
| GET | `/api/core-transaction-1/analytics` | Export analytics data |
| GET | `/api/core-transaction-1/events` | Get real-time events |

### Webhook Management Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/api/core-transaction-1/webhooks/test` | Test webhook connectivity |
| GET | `/api/core-transaction-1/webhooks/status` | Get webhook status |
| POST | `/api/core-transaction-1/webhooks/send` | Send manual webhook |
| GET | `/api/core-transaction-1/stats` | Get export statistics |

## 🚨 Error Handling

### Retry Logic
- Webhook retry with exponential backoff
- Maximum 3 retry attempts
- Configurable retry delays
- Dead letter queue for failed events

### Error Monitoring
- Comprehensive error logging
- Error rate monitoring
- Alert system for critical errors
- Performance metrics tracking

## 🚀 Deployment

### Environment Setup
```bash
# Run migrations
php artisan migrate

# Generate API keys
php artisan api:generate --name="Core Transaction 1 Export" --permissions=core_transaction_1_export

# Clear cache
php artisan cache:clear
php artisan config:cache
```

### Production Considerations
- Use Redis for caching
- Implement database connection pooling
- Set up monitoring and alerting
- Configure backup strategies
- Implement disaster recovery

## 🆘 Troubleshooting

### Common Issues
1. **Webhook not receiving updates**
   - Check webhook URL configuration
   - Verify webhook signature validation
   - Check webhook endpoint accessibility

2. **Data export failures**
   - Check API key permissions
   - Verify data validation rules
   - Check database connectivity

3. **Performance issues**
   - Enable caching
   - Optimize database queries
   - Implement rate limiting

## 📋 Checklist

### Setup Checklist
- [ ] Generate API keys
- [ ] Configure webhook URLs
- [ ] Run database migrations
- [ ] Test webhook connectivity
- [ ] Test data export endpoints
- [ ] Set up monitoring and alerting

### Integration Checklist
- [ ] Implement client libraries
- [ ] Configure webhook handlers
- [ ] Set up error handling
- [ ] Implement retry logic
- [ ] Configure caching
- [ ] Set up monitoring
- [ ] Test all endpoints
- [ ] Document integration

## 🎯 Benefits

### For Core Transaction 2 (Seller Operations)
- Real-time order notifications
- Customer data access
- Product performance insights
- Review and feedback data
- Cart abandonment alerts

### For Core Transaction 3 (Platform Control)
- Marketplace analytics
- Customer behavior insights
- Order and revenue tracking
- Product performance metrics
- Review sentiment analysis

### For Core Transaction 1 (Marketplace)
- Seamless data sharing
- Real-time notifications
- Performance monitoring
- Error tracking
- Analytics insights

---

This comprehensive data sharing solution ensures that Core Transaction 1 can effectively share marketplace data with Core Transaction 2 and 3 systems while maintaining data integrity, security, and performance! 🚀
