# Order Tracking API Documentation

## Overview
This documentation covers the order tracking system that allows Core Transaction 2 team to manage order tracking, update delivery statuses, and integrate with shipping providers.

## Base URL
```
https://your-domain.com/api/external
```

## Authentication
All requests require API key authentication:
```
X-API-Key: your_api_key_here
X-API-Secret: your_api_secret_here
```

## Order Tracking Endpoints

### 1. Create Tracking
**POST** `/orders/{orderId}/tracking`

Creates tracking information for an order.

#### Request Body
```json
{
    "tracking_number": "JNT123456789",
    "carrier": "J&T Express",
    "carrier_code": "jnt",
    "status": "shipped",
    "status_description": "Package has been shipped",
    "estimated_delivery": "2024-01-20T10:00:00Z",
    "shipping_address": {
        "name": "John Doe",
        "address": "123 Main St",
        "city": "Manila",
        "postal_code": "1000",
        "phone": "+639123456789"
    },
    "weight": 1.5,
    "dimensions": {
        "length": 20,
        "width": 15,
        "height": 10
    },
    "shipping_cost": 150.00,
    "insurance_amount": 50.00,
    "special_instructions": "Handle with care",
    "signature_required": true
}
```

#### Response
```json
{
    "success": true,
    "message": "Tracking created successfully",
    "data": {
        "id": 123,
        "order_id": 456,
        "tracking_number": "JNT123456789",
        "carrier": "J&T Express",
        "carrier_code": "jnt",
        "status": "shipped",
        "status_description": "Package has been shipped",
        "estimated_delivery": "2024-01-20T10:00:00Z",
        "shipped_at": "2024-01-15T14:30:00Z",
        "created_at": "2024-01-15T14:30:00Z",
        "updated_at": "2024-01-15T14:30:00Z",
        "order": {
            "id": 456,
            "order_number": "ORD-2024-001",
            "status": "shipped"
        }
    }
}
```

### 2. Update Tracking Status
**PATCH** `/tracking/{trackingId}/status`

Updates the tracking status and adds a history entry.

#### Request Body
```json
{
    "status": "in_transit",
    "status_description": "Package is in transit to destination",
    "location": "Manila Sorting Facility",
    "estimated_delivery": "2024-01-20T10:00:00Z",
    "delivery_notes": "Package is on schedule"
}
```

#### Response
```json
{
    "success": true,
    "message": "Tracking status updated successfully",
    "data": {
        "id": 123,
        "status": "in_transit",
        "status_description": "Package is in transit to destination",
        "last_updated_at": "2024-01-16T09:15:00Z",
        "history": [
            {
                "status": "shipped",
                "description": "Package has been shipped",
                "timestamp": "2024-01-15T14:30:00Z"
            },
            {
                "status": "in_transit",
                "description": "Package is in transit to destination",
                "location": "Manila Sorting Facility",
                "timestamp": "2024-01-16T09:15:00Z"
            }
        ]
    }
}
```

### 3. Add Tracking History
**POST** `/tracking/{trackingId}/history`

Adds a new entry to the tracking history.

#### Request Body
```json
{
    "status": "out_for_delivery",
    "description": "Package is out for delivery",
    "location": "Quezon City Hub",
    "timestamp": "2024-01-19T08:00:00Z"
}
```

#### Response
```json
{
    "success": true,
    "message": "Tracking history added successfully",
    "data": {
        "id": 123,
        "history": [
            {
                "status": "shipped",
                "description": "Package has been shipped",
                "timestamp": "2024-01-15T14:30:00Z"
            },
            {
                "status": "in_transit",
                "description": "Package is in transit to destination",
                "location": "Manila Sorting Facility",
                "timestamp": "2024-01-16T09:15:00Z"
            },
            {
                "status": "out_for_delivery",
                "description": "Package is out for delivery",
                "location": "Quezon City Hub",
                "timestamp": "2024-01-19T08:00:00Z"
            }
        ]
    }
}
```

### 4. Get Tracking Information
**GET** `/tracking/{trackingId}`

Retrieves detailed tracking information.

#### Response
```json
{
    "success": true,
    "data": {
        "id": 123,
        "order_id": 456,
        "tracking_number": "JNT123456789",
        "carrier": "J&T Express",
        "carrier_code": "jnt",
        "status": "delivered",
        "status_description": "Package delivered successfully",
        "estimated_delivery": "2024-01-20T10:00:00Z",
        "actual_delivery": "2024-01-20T09:45:00Z",
        "shipped_at": "2024-01-15T14:30:00Z",
        "delivery_attempts": 1,
        "signature_received": true,
        "delivery_photo": "https://example.com/delivery-photo.jpg",
        "history": [
            {
                "status": "shipped",
                "description": "Package has been shipped",
                "timestamp": "2024-01-15T14:30:00Z"
            },
            {
                "status": "in_transit",
                "description": "Package is in transit",
                "location": "Manila Sorting Facility",
                "timestamp": "2024-01-16T09:15:00Z"
            },
            {
                "status": "out_for_delivery",
                "description": "Package is out for delivery",
                "location": "Quezon City Hub",
                "timestamp": "2024-01-19T08:00:00Z"
            },
            {
                "status": "delivered",
                "description": "Package delivered successfully",
                "location": "Quezon City",
                "timestamp": "2024-01-20T09:45:00Z"
            }
        ],
        "order": {
            "id": 456,
            "order_number": "ORD-2024-001",
            "status": "completed",
            "user": {
                "id": 1,
                "name": "John Doe",
                "email": "john@example.com"
            }
        }
    }
}
```

### 5. Get Tracking by Number
**GET** `/tracking/number/{trackingNumber}`

Retrieves tracking information by tracking number.

#### Example Request
```
GET /tracking/number/JNT123456789
```

#### Response
Same as above tracking information response.

### 6. Get Order Tracking
**GET** `/orders/{orderId}/tracking`

Retrieves tracking information for a specific order.

#### Response
Same as tracking information response.

### 7. Bulk Update Tracking
**POST** `/tracking/bulk-update`

Updates multiple tracking statuses in a single request.

#### Request Body
```json
{
    "updates": [
        {
            "tracking_id": 123,
            "status": "delivered",
            "status_description": "Package delivered",
            "location": "Quezon City"
        },
        {
            "tracking_id": 124,
            "status": "in_transit",
            "status_description": "Package in transit",
            "location": "Manila Hub"
        }
    ]
}
```

#### Response
```json
{
    "success": true,
    "message": "Bulk tracking update completed",
    "data": {
        "updated_trackings": [
            {
                "tracking_id": 123,
                "order_id": 456,
                "old_status": "out_for_delivery",
                "new_status": "delivered"
            },
            {
                "tracking_id": 124,
                "order_id": 457,
                "old_status": "shipped",
                "new_status": "in_transit"
            }
        ],
        "failed_updates": [],
        "summary": {
            "total_requested": 2,
            "successfully_updated": 2,
            "failed": 0
        }
    }
}
```

### 8. Get Tracking Statistics
**GET** `/tracking/stats`

Retrieves tracking statistics with optional filters.

#### Query Parameters
- `carrier` (optional): Filter by carrier
- `status` (optional): Filter by status
- `date_from` (optional): Filter from date
- `date_to` (optional): Filter to date

#### Example Request
```
GET /tracking/stats?carrier=jnt&status=delivered&date_from=2024-01-01&date_to=2024-01-31
```

#### Response
```json
{
    "success": true,
    "data": {
        "total_trackings": 150,
        "by_status": [
            {
                "status": "delivered",
                "count": 120
            },
            {
                "status": "in_transit",
                "count": 20
            },
            {
                "status": "shipped",
                "count": 10
            }
        ],
        "by_carrier": [
            {
                "carrier": "J&T Express",
                "count": 80
            },
            {
                "carrier": "Ninja Van",
                "count": 40
            },
            {
                "carrier": "LBC Express",
                "count": 30
            }
        ],
        "delivered_count": 120,
        "in_transit_count": 20,
        "pending_delivery_count": 30
    }
}
```

## Tracking Status Values

### Valid Status Values
- `pending` - Tracking created but not yet shipped
- `shipped` - Package has been shipped
- `in_transit` - Package is in transit
- `out_for_delivery` - Package is out for delivery
- `delivered` - Package has been delivered
- `delivery_attempted` - Delivery was attempted but failed
- `returned` - Package has been returned
- `cancelled` - Tracking has been cancelled

### Status Flow
```
pending → shipped → in_transit → out_for_delivery → delivered
                ↓
            delivery_attempted → delivered (on retry)
                ↓
            returned (if failed multiple times)
```

## Shipping Providers

### Supported Carriers
- **J&T Express** (`jnt`)
- **Ninja Van** (`ninjavan`)
- **LBC Express** (`lbc`)
- **Flash Express** (`flash`)

### Carrier Codes
Use the carrier codes for API requests:
- `jnt` - J&T Express
- `ninjavan` - Ninja Van
- `lbc` - LBC Express
- `flash` - Flash Express

## Webhook Integration

### Tracking Events
The system sends webhooks for the following tracking events:

- `tracking.created` - New tracking created
- `tracking.updated` - Tracking status updated
- `tracking.delivered` - Package delivered
- `order.status_updated` - Order status changed due to tracking update

### Webhook Payload
```json
{
    "event": "tracking.updated",
    "timestamp": "2024-01-16T09:15:00.000000Z",
    "data": {
        "tracking_id": 123,
        "order_id": 456,
        "tracking_number": "JNT123456789",
        "old_status": "shipped",
        "new_status": "in_transit",
        "carrier": "J&T Express"
    }
}
```

## Shipping Provider Webhooks

### Provider Webhook Endpoints
- **J&T Express**: `POST /api/webhooks/shipping/jnt`
- **Ninja Van**: `POST /api/webhooks/shipping/ninjavan`
- **LBC Express**: `POST /api/webhooks/shipping/lbc`
- **Flash Express**: `POST /api/webhooks/shipping/flash`

### Provider Webhook Payload Example
```json
{
    "tracking_number": "JNT123456789",
    "status": "delivered",
    "description": "Package delivered successfully",
    "location": "Quezon City",
    "timestamp": "2024-01-20T09:45:00Z",
    "delivery_photo": "https://example.com/delivery-photo.jpg",
    "signature_received": true
}
```

## Error Handling

### Error Response Format
```json
{
    "success": false,
    "message": "Error description",
    "error": "error_code",
    "errors": {
        "field_name": ["Validation error message"]
    }
}
```

### Common Error Codes
- `tracking_exists` - Tracking already exists for order
- `tracking_not_found` - Tracking not found
- `invalid_status` - Invalid tracking status
- `validation_failed` - Request validation failed

### HTTP Status Codes
- `200` - Success
- `201` - Created
- `400` - Bad Request
- `401` - Unauthorized
- `403` - Forbidden
- `404` - Not Found
- `409` - Conflict (tracking already exists)
- `422` - Validation Error
- `500` - Internal Server Error

## Integration Examples

### PHP Integration Example
```php
<?php

class OrderTrackingClient
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

    public function createTracking($orderId, $trackingData)
    {
        $url = $this->baseUrl . "/orders/{$orderId}/tracking";
        
        $headers = [
            'Content-Type: application/json',
            'X-API-Key: ' . $this->apiKey,
            'X-API-Secret: ' . $this->apiSecret
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($trackingData));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return [
            'status_code' => $httpCode,
            'response' => json_decode($response, true)
        ];
    }

    public function updateTrackingStatus($trackingId, $statusData)
    {
        $url = $this->baseUrl . "/tracking/{$trackingId}/status";
        
        $headers = [
            'Content-Type: application/json',
            'X-API-Key: ' . $this->apiKey,
            'X-API-Secret: ' . $this->apiSecret
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($statusData));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return [
            'status_code' => $httpCode,
            'response' => json_decode($response, true)
        ];
    }
}

// Usage
$client = new OrderTrackingClient(
    'https://your-domain.com/api/external',
    'your_api_key',
    'your_api_secret'
);

// Create tracking
$trackingData = [
    'tracking_number' => 'JNT123456789',
    'carrier' => 'J&T Express',
    'carrier_code' => 'jnt',
    'status' => 'shipped',
    'status_description' => 'Package has been shipped',
    'estimated_delivery' => '2024-01-20T10:00:00Z'
];

$result = $client->createTracking(456, $trackingData);

if ($result['status_code'] === 201) {
    echo "Tracking created successfully!";
    echo "Tracking ID: " . $result['response']['data']['id'];
} else {
    echo "Error: " . $result['response']['message'];
}

// Update tracking status
$statusData = [
    'status' => 'delivered',
    'status_description' => 'Package delivered successfully',
    'location' => 'Quezon City',
    'actual_delivery' => '2024-01-20T09:45:00Z'
];

$result = $client->updateTrackingStatus(123, $statusData);

if ($result['status_code'] === 200) {
    echo "Tracking status updated successfully!";
} else {
    echo "Error: " . $result['response']['message'];
}
```

### cURL Examples

#### Create Tracking
```bash
curl -X POST https://your-domain.com/api/external/orders/456/tracking \
  -H "Content-Type: application/json" \
  -H "X-API-Key: your_api_key" \
  -H "X-API-Secret: your_api_secret" \
  -d '{
    "tracking_number": "JNT123456789",
    "carrier": "J&T Express",
    "carrier_code": "jnt",
    "status": "shipped",
    "status_description": "Package has been shipped",
    "estimated_delivery": "2024-01-20T10:00:00Z"
  }'
```

#### Update Tracking Status
```bash
curl -X PATCH https://your-domain.com/api/external/tracking/123/status \
  -H "Content-Type: application/json" \
  -H "X-API-Key: your_api_key" \
  -H "X-API-Secret: your_api_secret" \
  -d '{
    "status": "delivered",
    "status_description": "Package delivered successfully",
    "location": "Quezon City",
    "actual_delivery": "2024-01-20T09:45:00Z"
  }'
```

#### Get Tracking Information
```bash
curl -X GET https://your-domain.com/api/external/tracking/123 \
  -H "X-API-Key: your_api_key" \
  -H "X-API-Secret: your_api_secret"
```

## Best Practices

### 1. Status Updates
- Always provide meaningful status descriptions
- Include location information when available
- Update estimated delivery dates as needed
- Use appropriate status values

### 2. Error Handling
- Implement proper error handling and retry logic
- Log failed requests for debugging
- Handle rate limiting gracefully
- Validate data before sending requests

### 3. Performance
- Use bulk update endpoints for multiple status changes
- Implement caching for frequently accessed data
- Monitor API usage and performance
- Use webhooks for real-time updates

### 4. Security
- Keep API keys secure
- Use HTTPS for all requests
- Implement proper webhook signature verification
- Monitor for suspicious activity

## Support

### Contact Information
- **Technical Support**: tech-support@your-domain.com
- **API Issues**: api-support@your-domain.com
- **Documentation**: docs@your-domain.com

### Resources
- **API Status Page**: https://status.your-domain.com
- **Developer Portal**: https://developers.your-domain.com
- **Changelog**: https://changelog.your-domain.com

---

This comprehensive order tracking system provides everything needed for Core Transaction 2 team to manage order tracking, update delivery statuses, and integrate with shipping providers seamlessly!











