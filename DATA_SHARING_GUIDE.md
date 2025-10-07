# 🔗 Data Sharing Guide for Core Transaction 2 & 3 Systems

This guide explains how Core Transaction 2 (Seller Operations) and Core Transaction 3 (Platform Control) systems can share data effectively.

## 📋 Overview

The data sharing system provides:
- **Real-time data synchronization** between both systems
- **Webhook-based notifications** for instant updates
- **Shared data endpoints** for cross-system access
- **Conflict resolution** and data validation
- **Caching** for improved performance

## 🏗️ Architecture

```
┌─────────────────────┐    ┌─────────────────────┐
│   Core Transaction 2│    │   Core Transaction 3│
│   (Seller Ops)      │    │   (Platform Control)│
└─────────┬───────────┘    └─────────┬───────────┘
          │                          │
          └──────────┬─────────────────┘
                     │
          ┌─────────▼───────────┐
          │   DataSyncService   │
          │   + Webhooks        │
          └─────────┬───────────┘
                    │
          ┌─────────▼───────────┐
          │   Shared Database   │
          │   + Cache Layer     │
          └─────────────────────┘
```

## 🚀 Quick Start

### 1. Generate API Keys

First, generate API keys for both systems:

```bash
# For Core Transaction 2 (Seller Operations)
php artisan api:generate --name="Core Transaction 2" --type=external --permissions=product_management,shared_data

# For Core Transaction 3 (Platform Control)  
php artisan api:generate --name="Core Transaction 3" --type=external --permissions=platform_management,shared_data
```

### 2. Register Webhook Endpoints

Each system should register webhook endpoints to receive real-time updates:

```bash
# Core Transaction 2 webhook registration
curl -X POST https://your-domain.com/api/shared/webhooks/register \
  -H "X-API-Key: your_api_key" \
  -H "X-API-Secret: your_api_secret" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Core Transaction 2 Webhook",
    "url": "https://core-transaction-2.com/webhooks/ecommerce-updates",
    "events": ["seller.updated", "product.updated", "order.updated"],
    "description": "Receives updates from ecommerce platform"
  }'

# Core Transaction 3 webhook registration
curl -X POST https://your-domain.com/api/shared/webhooks/register \
  -H "X-API-Key: your_api_key" \
  -H "X-API-Secret: your_api_secret" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Core Transaction 3 Webhook",
    "url": "https://core-transaction-3.com/webhooks/ecommerce-updates",
    "events": ["platform.settings.updated", "commission.updated", "payout.updated"],
    "description": "Receives platform updates from ecommerce platform"
  }'
```

## 📊 Data Sharing Methods

### 1. Real-time Data Sync

Sync data between systems instantly:

```php
// Sync seller data
$response = $client->post('/api/shared/sellers/123/sync', [
    'status' => 'active',
    'commission_rate' => 5.0,
    'verification_status' => 'verified'
]);

// Sync product data
$response = $client->post('/api/shared/products/456/sync', [
    'price' => 299.99,
    'stock' => 100,
    'status' => 'active'
]);

// Sync order data
$response = $client->post('/api/shared/orders/789/sync', [
    'status' => 'shipped',
    'tracking_number' => 'TRK123456789'
]);
```

### 2. Shared Data Retrieval

Get data that both systems need:

```php
// Get sellers data
$sellers = $client->get('/api/shared/data?type=sellers&status=active&per_page=20');

// Get products data
$products = $client->get('/api/shared/data?type=products&seller_id=123&category=Electronics');

// Get orders data
$orders = $client->get('/api/shared/data?type=orders&seller_id=123&date_from=2024-01-01');

// Get earnings data
$earnings = $client->get('/api/shared/data?type=earnings&seller_id=123&period=month');

// Get platform stats
$stats = $client->get('/api/shared/data?type=platform_stats&date_from=2024-01-01');
```

### 3. Real-time Updates

Get real-time updates via webhooks or polling:

```php
// Poll for updates
$updates = $client->get('/api/shared/realtime-updates?last_update=2024-01-15T10:30:00Z&events[]=seller.updated&events[]=product.updated');

// Webhook payload example
{
    "event": "seller.updated",
    "timestamp": "2024-01-15T10:30:00.000000Z",
    "data": {
        "id": 123,
        "business_name": "TechStore PH",
        "status": "active",
        "commission_rate": 5.0,
        "verification_status": "verified"
    }
}
```

## 🔄 Data Flow Examples

### Example 1: Seller Approval Flow

1. **Core Transaction 3** approves a seller
2. **DataSyncService** syncs seller data
3. **Webhook** notifies Core Transaction 2
4. **Core Transaction 2** updates its local data

```php
// Step 1: Core Transaction 3 approves seller
POST /api/platform/sellers/123/approve
{
    "notes": "Seller approved after verification",
    "commission_rate": 5.0,
    "subscription_plan": "standard"
}

// Step 2: DataSyncService automatically syncs data
// Step 3: Webhook sent to Core Transaction 2
{
    "event": "seller.updated",
    "data": {
        "id": 123,
        "status": "active",
        "commission_rate": 5.0
    }
}
```

### Example 2: Product Update Flow

1. **Core Transaction 2** updates product
2. **DataSyncService** syncs product data
3. **Webhook** notifies Core Transaction 3
4. **Core Transaction 3** updates platform analytics

```php
// Step 1: Core Transaction 2 updates product
POST /api/external/products/456
{
    "price": 299.99,
    "stock": 100,
    "status": "active"
}

// Step 2: DataSyncService automatically syncs data
// Step 3: Webhook sent to Core Transaction 3
{
    "event": "product.updated",
    "data": {
        "id": 456,
        "price": 299.99,
        "stock": 100,
        "status": "active"
    }
}
```

### Example 3: Order Status Update Flow

1. **Core Transaction 2** updates order status
2. **DataSyncService** syncs order data
3. **Webhook** notifies Core Transaction 3
4. **Core Transaction 3** updates earnings and payouts

```php
// Step 1: Core Transaction 2 updates order
POST /api/external/orders/789/tracking
{
    "status": "shipped",
    "tracking_number": "TRK123456789",
    "carrier": "J&T Express"
}

// Step 2: DataSyncService automatically syncs data
// Step 3: Webhook sent to Core Transaction 3
{
    "event": "order.updated",
    "data": {
        "id": 789,
        "status": "shipped",
        "tracking_number": "TRK123456789"
    }
}
```

## 🛠️ Implementation Guide

### For Core Transaction 2 (Seller Operations)

```php
<?php

class CoreTransaction2Client
{
    private $baseUrl;
    private $apiKey;
    private $apiSecret;

    public function __construct($baseUrl, $apiKey, $apiSecret)
    {
        $this->baseUrl = $baseUrl;
        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;
    }

    // Sync seller data when updated
    public function syncSellerData($sellerId, $data)
    {
        return $this->post("/api/shared/sellers/{$sellerId}/sync", $data);
    }

    // Sync product data when updated
    public function syncProductData($productId, $data)
    {
        return $this->post("/api/shared/products/{$productId}/sync", $data);
    }

    // Sync order data when updated
    public function syncOrderData($orderId, $data)
    {
        return $this->post("/api/shared/orders/{$orderId}/sync", $data);
    }

    // Get platform settings
    public function getPlatformSettings()
    {
        return $this->get("/api/shared/data?type=platform_stats");
    }

    // Register webhook for real-time updates
    public function registerWebhook($url, $events)
    {
        return $this->post("/api/shared/webhooks/register", [
            'name' => 'Core Transaction 2 Webhook',
            'url' => $url,
            'events' => $events
        ]);
    }

    private function post($endpoint, $data)
    {
        $client = new \GuzzleHttp\Client();
        return $client->post($this->baseUrl . $endpoint, [
            'json' => $data,
            'headers' => [
                'X-API-Key' => $this->apiKey,
                'X-API-Secret' => $this->apiSecret,
                'Content-Type' => 'application/json'
            ]
        ]);
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
    private $baseUrl;
    private $apiKey;
    private $apiSecret;

    public function __construct($baseUrl, $apiKey, $apiSecret)
    {
        $this->baseUrl = $baseUrl;
        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;
    }

    // Sync platform settings
    public function syncPlatformSettings()
    {
        return $this->post("/api/shared/platform/settings/sync");
    }

    // Get sellers data
    public function getSellersData($filters = [])
    {
        $query = http_build_query($filters);
        return $this->get("/api/shared/data?type=sellers&{$query}");
    }

    // Get products data
    public function getProductsData($filters = [])
    {
        $query = http_build_query($filters);
        return $this->get("/api/shared/data?type=products&{$query}");
    }

    // Get orders data
    public function getOrdersData($filters = [])
    {
        $query = http_build_query($filters);
        return $this->get("/api/shared/data?type=orders&{$query}");
    }

    // Get earnings data
    public function getEarningsData($filters = [])
    {
        $query = http_build_query($filters);
        return $this->get("/api/shared/data?type=earnings&{$query}");
    }

    // Register webhook for real-time updates
    public function registerWebhook($url, $events)
    {
        return $this->post("/api/shared/webhooks/register", [
            'name' => 'Core Transaction 3 Webhook',
            'url' => $url,
            'events' => $events
        ]);
    }

    private function post($endpoint, $data = [])
    {
        $client = new \GuzzleHttp\Client();
        return $client->post($this->baseUrl . $endpoint, [
            'json' => $data,
            'headers' => [
                'X-API-Key' => $this->apiKey,
                'X-API-Secret' => $this->apiSecret,
                'Content-Type' => 'application/json'
            ]
        ]);
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

// In your Core Transaction 2 system
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
            // Update local seller data
            $this->updateLocalSeller($data['data']);
            break;
            
        case 'product.updated':
            // Update local product data
            $this->updateLocalProduct($data['data']);
            break;
            
        case 'order.updated':
            // Update local order data
            $this->updateLocalOrder($data['data']);
            break;
            
        case 'platform.settings.updated':
            // Update local platform settings
            $this->updateLocalSettings($data['data']);
            break;
    }
    
    return response()->json(['success' => true]);
});
```

## 📈 Performance Optimization

### Caching Strategy

```php
// Data is automatically cached for 1 hour
$sellerData = Cache::get("seller_data_{$sellerId}");

// Platform settings cached for 1 hour
$platformSettings = Cache::get('platform_settings');

// Earnings data cached for 30 minutes
$earningsData = Cache::get("earnings_data_{$sellerId}_{$period}");
```

### Database Optimization

```sql
-- Indexes for better performance
CREATE INDEX idx_sellers_status ON sellers(status);
CREATE INDEX idx_products_seller_status ON products(seller_id, status);
CREATE INDEX idx_orders_seller_created ON orders(seller_id, created_at);
CREATE INDEX idx_earnings_seller_created ON earnings(seller_id, created_at);
```

## 🔒 Security Considerations

### API Key Security

- Store API keys securely
- Use environment variables
- Rotate keys regularly
- Monitor API usage

### Webhook Security

- Verify webhook signatures
- Use HTTPS endpoints
- Implement rate limiting
- Log all webhook events

### Data Validation

```php
// Validate incoming data
$validated = $request->validate([
    'status' => 'required|in:active,inactive,suspended',
    'commission_rate' => 'required|numeric|min:0|max:100',
    'verification_status' => 'required|in:pending,verified,rejected'
]);
```

## 🚨 Error Handling

### Retry Logic

```php
// Webhook retry with exponential backoff
$maxRetries = 3;
$retryDelay = 1; // seconds

for ($i = 0; $i < $maxRetries; $i++) {
    try {
        $response = $this->sendWebhook($endpoint, $event, $data);
        if ($response->getStatusCode() === 200) {
            break; // Success
        }
    } catch (\Exception $e) {
        if ($i === $maxRetries - 1) {
            throw $e; // Final attempt failed
        }
        sleep($retryDelay * pow(2, $i)); // Exponential backoff
    }
}
```

### Error Monitoring

```php
// Log all errors
Log::error('Data sync failed', [
    'system' => 'Core Transaction 2',
    'operation' => 'sync_seller_data',
    'seller_id' => $sellerId,
    'error' => $e->getMessage()
]);
```

## 📊 Monitoring & Analytics

### Webhook Monitoring

```php
// Check webhook delivery status
$webhookEvents = WebhookEvent::where('endpoint_id', $webhookId)
    ->where('created_at', '>', now()->subDay())
    ->get();

$successRate = $webhookEvents->where('status', 'successful')->count() / $webhookEvents->count() * 100;
```

### API Usage Monitoring

```php
// Monitor API key usage
$apiKey = ApiKey::where('key', $apiKey)->first();
$usageCount = $apiKey->usage_count;
$lastUsed = $apiKey->last_used_at;
```

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
    
    $this->assertDatabaseHas('sellers', [
        'id' => $seller->id,
        'status' => 'active',
        'commission_rate' => 5.0
    ]);
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

## 🆘 Troubleshooting

### Common Issues

1. **Webhook not receiving updates**
   - Check webhook URL is accessible
   - Verify webhook signature validation
   - Check webhook endpoint is active

2. **Data sync failures**
   - Check API key permissions
   - Verify data validation rules
   - Check database connectivity

3. **Performance issues**
   - Enable caching
   - Optimize database queries
   - Implement rate limiting

### Debug Mode

```php
// Enable debug logging
Log::debug('Data sync operation', [
    'operation' => 'sync_seller_data',
    'seller_id' => $sellerId,
    'data' => $data,
    'timestamp' => now()
]);
```

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

---

This comprehensive data sharing system ensures that Core Transaction 2 and 3 systems can seamlessly share data while maintaining data integrity, security, and performance! 🚀
