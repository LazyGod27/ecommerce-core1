# 🔗 Core Transaction 2 & 3 Data Sharing Solution

## 📋 Overview

This document provides a comprehensive solution for data sharing between Core Transaction 2 (Seller Operations) and Core Transaction 3 (Platform Control) systems. The solution enables seamless data synchronization, real-time updates, and conflict resolution.

## 🏗️ Architecture Components

### 1. DataSyncService
- **Location**: `app/Services/DataSyncService.php`
- **Purpose**: Central service for data synchronization between systems
- **Features**:
  - Sync seller, product, order, and earnings data
  - Real-time webhook notifications
  - Caching for performance optimization
  - Comprehensive data retrieval methods

### 2. SharedDataController
- **Location**: `app/Http/Controllers/Api/SharedDataController.php`
- **Purpose**: API endpoints for shared data access
- **Features**:
  - Data retrieval endpoints
  - Data synchronization endpoints
  - Webhook management
  - Real-time updates polling

### 3. DataValidationService
- **Location**: `app/Services/DataValidationService.php`
- **Purpose**: Data validation and conflict resolution
- **Features**:
  - Comprehensive data validation rules
  - Conflict detection and resolution
  - Data integrity checks
  - Data sanitization

### 4. Webhook System
- **Models**: `WebhookEndpoint.php`, `WebhookEvent.php`
- **Purpose**: Real-time data updates between systems
- **Features**:
  - Event-driven notifications
  - Retry mechanism with exponential backoff
  - Signature verification for security
  - Event logging and monitoring

## 🚀 Quick Setup

### 1. Run the Setup Script
```bash
php setup_data_sharing.php
```

This will:
- Run database migrations
- Generate API keys for both systems
- Create sample webhook endpoints
- Set up platform settings and commission rules
- Create subscription plans

### 2. Generate API Keys Manually
```bash
# Core Transaction 2
php artisan api:generate --name="Core Transaction 2" --permissions=product_management,shared_data

# Core Transaction 3
php artisan api:generate --name="Core Transaction 3" --permissions=platform_management,shared_data

# Shared Data Access
php artisan api:generate --name="Shared Data Access" --permissions=shared_data
```

## 📊 Data Sharing Methods

### 1. Real-time Synchronization
Both systems can sync data instantly using the shared endpoints:

```php
// Sync seller data
POST /api/shared/sellers/{id}/sync
{
    "status": "active",
    "commission_rate": 5.0,
    "verification_status": "verified"
}

// Sync product data
POST /api/shared/products/{id}/sync
{
    "price": 299.99,
    "stock": 100,
    "status": "active"
}

// Sync order data
POST /api/shared/orders/{id}/sync
{
    "status": "shipped",
    "tracking_number": "TRK123456789"
}
```

### 2. Shared Data Retrieval
Both systems can access shared data:

```php
// Get sellers data
GET /api/shared/data?type=sellers&status=active&per_page=20

// Get products data
GET /api/shared/data?type=products&seller_id=123&category=Electronics

// Get orders data
GET /api/shared/data?type=orders&seller_id=123&date_from=2024-01-01

// Get earnings data
GET /api/shared/data?type=earnings&seller_id=123&period=month

// Get platform stats
GET /api/shared/data?type=platform_stats&date_from=2024-01-01
```

### 3. Webhook Notifications
Real-time updates via webhooks:

```json
{
    "event": "seller.updated",
    "timestamp": "2024-01-15T10:30:00.000000Z",
    "data": {
        "id": 123,
        "business_name": "TechStore PH",
        "status": "active",
        "commission_rate": 5.0
    }
}
```

## 🔄 Data Flow Examples

### Example 1: Seller Approval Flow
1. **Core Transaction 3** approves seller via `/api/platform/sellers/{id}/approve`
2. **DataSyncService** automatically syncs seller data
3. **Webhook** notifies Core Transaction 2
4. **Core Transaction 2** updates local seller data

### Example 2: Product Update Flow
1. **Core Transaction 2** updates product via `/api/external/products/{id}`
2. **DataSyncService** automatically syncs product data
3. **Webhook** notifies Core Transaction 3
4. **Core Transaction 3** updates platform analytics

### Example 3: Order Status Update Flow
1. **Core Transaction 2** updates order via `/api/external/orders/{id}/tracking`
2. **DataSyncService** automatically syncs order data
3. **Webhook** notifies Core Transaction 3
4. **Core Transaction 3** updates earnings and payouts

## 🛠️ Integration Guide

### For Core Transaction 2 (Seller Operations)

```php
<?php

class CoreTransaction2Client
{
    private $baseUrl = 'https://ecommerce-platform.com/api';
    private $apiKey = 'your_api_key';
    private $apiSecret = 'your_api_secret';

    // Sync data when updated
    public function syncSellerData($sellerId, $data)
    {
        return $this->post("/shared/sellers/{$sellerId}/sync", $data);
    }

    public function syncProductData($productId, $data)
    {
        return $this->post("/shared/products/{$productId}/sync", $data);
    }

    public function syncOrderData($orderId, $data)
    {
        return $this->post("/shared/orders/{$orderId}/sync", $data);
    }

    // Get platform settings
    public function getPlatformSettings()
    {
        return $this->get("/shared/data?type=platform_stats");
    }

    // Register webhook
    public function registerWebhook($url, $events)
    {
        return $this->post("/shared/webhooks/register", [
            'name' => 'Core Transaction 2 Webhook',
            'url' => $url,
            'events' => $events
        ]);
    }
}
```

### For Core Transaction 3 (Platform Control)

```php
<?php

class CoreTransaction3Client
{
    private $baseUrl = 'https://ecommerce-platform.com/api';
    private $apiKey = 'your_api_key';
    private $apiSecret = 'your_api_secret';

    // Sync platform settings
    public function syncPlatformSettings()
    {
        return $this->post("/shared/platform/settings/sync");
    }

    // Get shared data
    public function getSellersData($filters = [])
    {
        $query = http_build_query($filters);
        return $this->get("/shared/data?type=sellers&{$query}");
    }

    public function getProductsData($filters = [])
    {
        $query = http_build_query($filters);
        return $this->get("/shared/data?type=products&{$query}");
    }

    public function getOrdersData($filters = [])
    {
        $query = http_build_query($filters);
        return $this->get("/shared/data?type=orders&{$query}");
    }

    public function getEarningsData($filters = [])
    {
        $query = http_build_query($filters);
        return $this->get("/shared/data?type=earnings&{$query}");
    }

    // Register webhook
    public function registerWebhook($url, $events)
    {
        return $this->post("/shared/webhooks/register", [
            'name' => 'Core Transaction 3 Webhook',
            'url' => $url,
            'events' => $events
        ]);
    }
}
```

## 🔧 Webhook Implementation

### Webhook Endpoint Handler

```php
<?php

// In your Core Transaction system
Route::post('/webhooks/ecommerce-updates', function(Request $request) {
    // Verify webhook signature
    $signature = $request->header('X-Webhook-Signature');
    $payload = $request->getContent();
    $expectedSignature = hash_hmac('sha256', $payload, config('webhooks.ecommerce_secret'));
    
    if (!hash_equals($signature, $expectedSignature)) {
        return response()->json(['error' => 'Invalid signature'], 401);
    }
    
    $data = $request->json()->all();
    $event = $data['event'];
    
    switch ($event) {
        case 'seller.updated':
            $this->updateLocalSeller($data['data']);
            break;
        case 'product.updated':
            $this->updateLocalProduct($data['data']);
            break;
        case 'order.updated':
            $this->updateLocalOrder($data['data']);
            break;
        case 'platform.settings.updated':
            $this->updateLocalSettings($data['data']);
            break;
    }
    
    return response()->json(['success' => true]);
});
```

## 📈 Performance Features

### Caching
- Data is automatically cached for 1 hour
- Platform settings cached for 1 hour
- Earnings data cached for 30 minutes

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

### Data Validation
- Comprehensive validation rules
- Data sanitization
- Conflict resolution strategies
- Data integrity checks

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

### Unit Tests
```php
public function test_seller_data_sync()
{
    $seller = Seller::factory()->create();
    $data = ['status' => 'active', 'commission_rate' => 5.0];
    
    $response = $this->postJson("/api/shared/sellers/{$seller->id}/sync", $data);
    
    $response->assertStatus(200)
             ->assertJson(['success' => true]);
}
```

### Integration Tests
```php
public function test_webhook_delivery()
{
    $webhook = WebhookEndpoint::factory()->create();
    $eventData = ['event' => 'test', 'data' => ['test' => true]];
    
    $this->postJson("/api/shared/webhooks/{$webhook->id}/test", $eventData)
         ->assertStatus(200)
         ->assertJson(['success' => true]);
}
```

## 📚 API Reference

### Shared Data Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/shared/data` | Get shared data by type |
| GET | `/api/shared/realtime-updates` | Get real-time updates |
| POST | `/api/shared/sellers/{id}/sync` | Sync seller data |
| POST | `/api/shared/products/{id}/sync` | Sync product data |
| POST | `/api/shared/orders/{id}/sync` | Sync order data |
| POST | `/api/shared/sellers/{id}/earnings/sync` | Sync earnings data |
| POST | `/api/shared/platform/settings/sync` | Sync platform settings |
| POST | `/api/shared/webhooks/register` | Register webhook endpoint |
| POST | `/api/shared/webhooks/{id}/test` | Test webhook endpoint |

### Data Types

| Type | Description | Filters |
|------|-------------|---------|
| `sellers` | Seller information | `status`, `verification_status`, `search` |
| `products` | Product catalog | `seller_id`, `status`, `category`, `search` |
| `orders` | Order information | `seller_id`, `status`, `date_from`, `date_to` |
| `earnings` | Earnings data | `seller_id`, `date_from`, `date_to` |
| `platform_stats` | Platform statistics | `date_from`, `date_to` |

### Webhook Events

| Event | Description | Data |
|-------|-------------|------|
| `seller.updated` | Seller data changed | Seller object |
| `product.updated` | Product data changed | Product object |
| `order.updated` | Order data changed | Order object |
| `earnings.updated` | Earnings data changed | Earnings object |
| `platform.settings.updated` | Platform settings changed | Settings object |

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
php artisan api:generate --name="Core Transaction 2" --permissions=shared_data
php artisan api:generate --name="Core Transaction 3" --permissions=shared_data

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
   - Check webhook URL accessibility
   - Verify signature validation
   - Check webhook endpoint status

2. **Data sync failures**
   - Check API key permissions
   - Verify data validation rules
   - Check database connectivity

3. **Performance issues**
   - Enable caching
   - Optimize database queries
   - Implement rate limiting

## 📋 Checklist

### Setup Checklist
- [ ] Run database migrations
- [ ] Generate API keys for both systems
- [ ] Register webhook endpoints
- [ ] Configure platform settings
- [ ] Test data synchronization
- [ ] Verify webhook delivery
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
- Real-time access to platform settings
- Automatic seller data synchronization
- Instant product updates
- Order status tracking
- Earnings data access

### For Core Transaction 3 (Platform Control)
- Real-time seller data access
- Product catalog synchronization
- Order monitoring and analytics
- Earnings tracking and reporting
- Platform settings management

### For the Overall System
- Seamless data integration
- Real-time updates
- Conflict resolution
- Data consistency
- Performance optimization
- Security and monitoring

---

This comprehensive data sharing solution ensures that Core Transaction 2 and 3 systems can seamlessly share data while maintaining data integrity, security, and performance! 🚀
