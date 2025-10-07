# Core Transaction 2 - API Integration Guide

## Overview
This guide explains how the Core Transaction 2 team can connect to the e-commerce platform to post seller products and manage product data.

## Authentication

### API Key Authentication
All external API requests require API key authentication using the following headers:

```
X-API-Key: your_api_key_here
X-API-Secret: your_api_secret_here
```

### Getting API Keys
Contact the platform administrator to generate API keys for your team. API keys can be generated with specific permissions and restrictions.

## Base URL
```
https://your-domain.com/api/external
```

## Product Management Endpoints

### 1. Create Product
**POST** `/products`

Creates a new product for a seller.

#### Request Body
```json
{
    "seller_id": 1,
    "shop_id": 1,
    "name": "Gaming Mouse Pro",
    "description": "High-performance gaming mouse with RGB lighting",
    "price": 2999.99,
    "stock": 100,
    "category": "Electronics",
    "image": "https://example.com/images/gaming-mouse.jpg",
    "sku": "GM-PRO-001",
    "barcode": "1234567890123",
    "weight": 0.5,
    "dimensions": {
        "length": 12.5,
        "width": 6.5,
        "height": 4.0
    },
    "brand": "TechBrand",
    "model": "GM-Pro",
    "condition": "new",
    "is_digital": false,
    "tags": ["gaming", "mouse", "rgb", "wireless"],
    "meta_title": "Gaming Mouse Pro - Best Gaming Mouse 2024",
    "meta_description": "Professional gaming mouse with advanced features",
    "seo_keywords": ["gaming mouse", "rgb mouse", "wireless mouse"],
    "status": "active"
}
```

#### Response
```json
{
    "success": true,
    "message": "Product created successfully",
    "data": {
        "id": 123,
        "seller_id": 1,
        "shop_id": 1,
        "name": "Gaming Mouse Pro",
        "description": "High-performance gaming mouse with RGB lighting",
        "price": "2999.99",
        "stock": 100,
        "category": "Electronics",
        "image": "https://example.com/images/gaming-mouse.jpg",
        "sku": "GM-PRO-001",
        "status": "active",
        "created_at": "2024-01-15T10:30:00.000000Z",
        "updated_at": "2024-01-15T10:30:00.000000Z",
        "seller": {
            "id": 1,
            "business_name": "TechStore Pro"
        },
        "shop": {
            "id": 1,
            "name": "TechStore Pro Shop"
        }
    }
}
```

### 2. Update Product
**PUT** `/products/{id}`

Updates an existing product.

#### Request Body
```json
{
    "name": "Gaming Mouse Pro v2",
    "price": 2799.99,
    "stock": 150,
    "description": "Updated gaming mouse with improved features"
}
```

#### Response
```json
{
    "success": true,
    "message": "Product updated successfully",
    "data": {
        "id": 123,
        "name": "Gaming Mouse Pro v2",
        "price": "2799.99",
        "stock": 150,
        "updated_at": "2024-01-15T11:00:00.000000Z"
    }
}
```

### 3. Bulk Create Products
**POST** `/products/bulk`

Creates multiple products in a single request (max 100 products).

#### Request Body
```json
{
    "products": [
        {
            "seller_id": 1,
            "shop_id": 1,
            "name": "Product 1",
            "description": "Description 1",
            "price": 100.00,
            "stock": 50,
            "category": "Electronics",
            "image": "https://example.com/image1.jpg"
        },
        {
            "seller_id": 1,
            "shop_id": 1,
            "name": "Product 2",
            "description": "Description 2",
            "price": 200.00,
            "stock": 30,
            "category": "Electronics",
            "image": "https://example.com/image2.jpg"
        }
    ]
}
```

#### Response
```json
{
    "success": true,
    "message": "Bulk product creation completed",
    "data": {
        "created_products": [
            {
                "id": 124,
                "name": "Product 1",
                "price": "100.00"
            },
            {
                "id": 125,
                "name": "Product 2",
                "price": "200.00"
            }
        ],
        "failed_products": [],
        "summary": {
            "total_requested": 2,
            "successfully_created": 2,
            "failed": 0
        }
    }
}
```

### 4. Get Product
**GET** `/products/{id}`

Retrieves a specific product by ID.

#### Response
```json
{
    "success": true,
    "data": {
        "id": 123,
        "name": "Gaming Mouse Pro",
        "description": "High-performance gaming mouse",
        "price": "2999.99",
        "stock": 100,
        "category": "Electronics",
        "image": "https://example.com/images/gaming-mouse.jpg",
        "sku": "GM-PRO-001",
        "status": "active",
        "seller": {
            "id": 1,
            "business_name": "TechStore Pro"
        },
        "shop": {
            "id": 1,
            "name": "TechStore Pro Shop"
        },
        "reviews": [
            {
                "id": 1,
                "rating": 5,
                "comment": "Great mouse!"
            }
        ]
    }
}
```

### 5. Get Seller Products
**GET** `/sellers/{sellerId}/products`

Retrieves all products for a specific seller.

#### Query Parameters
- `status` (optional): Filter by product status (draft, active, inactive, suspended)
- `category` (optional): Filter by category
- `shop_id` (optional): Filter by shop ID
- `per_page` (optional): Number of products per page (default: 15)

#### Example Request
```
GET /sellers/1/products?status=active&category=Electronics&per_page=20
```

#### Response
```json
{
    "success": true,
    "data": {
        "current_page": 1,
        "data": [
            {
                "id": 123,
                "name": "Gaming Mouse Pro",
                "price": "2999.99",
                "stock": 100,
                "status": "active"
            }
        ],
        "first_page_url": "http://localhost:8000/api/external/sellers/1/products?page=1",
        "from": 1,
        "last_page": 1,
        "last_page_url": "http://localhost:8000/api/external/sellers/1/products?page=1",
        "links": [...],
        "next_page_url": null,
        "path": "http://localhost:8000/api/external/sellers/1/products",
        "per_page": 15,
        "prev_page_url": null,
        "to": 1,
        "total": 1
    }
}
```

### 6. Update Product Stock
**PATCH** `/products/{id}/stock`

Updates the stock quantity for a product.

#### Request Body
```json
{
    "stock": 150,
    "operation": "set"
}
```

#### Operations
- `set`: Set stock to exact value
- `increment`: Add to current stock
- `decrement`: Subtract from current stock

#### Response
```json
{
    "success": true,
    "message": "Stock updated successfully",
    "data": {
        "product_id": 123,
        "current_stock": 150
    }
}
```

### 7. Delete Product
**DELETE** `/products/{id}`

Deletes a product (soft delete).

#### Response
```json
{
    "success": true,
    "message": "Product deleted successfully"
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
- `missing_credentials`: API key or secret missing
- `invalid_key`: Invalid API key
- `inactive_key`: API key is inactive
- `expired_key`: API key has expired
- `invalid_secret`: Invalid API secret
- `ip_not_allowed`: IP address not allowed
- `domain_not_allowed`: Domain not allowed
- `insufficient_permissions`: Insufficient permissions

### HTTP Status Codes
- `200`: Success
- `201`: Created
- `400`: Bad Request
- `401`: Unauthorized
- `403`: Forbidden
- `404`: Not Found
- `422`: Validation Error
- `500`: Internal Server Error

## Rate Limiting
- **Rate Limit**: 1000 requests per hour per API key
- **Burst Limit**: 100 requests per minute
- **Headers**: Rate limit information is included in response headers

## Webhook Integration

### Product Events
The system can send webhooks for the following events:

- `product.created`: New product created
- `product.updated`: Product updated
- `product.deleted`: Product deleted
- `product.stock_updated`: Product stock updated

### Webhook Payload
```json
{
    "event": "product.created",
    "timestamp": "2024-01-15T10:30:00.000000Z",
    "data": {
        "product_id": 123,
        "seller_id": 1,
        "shop_id": 1,
        "name": "Gaming Mouse Pro",
        "price": "2999.99",
        "status": "active"
    }
}
```

## Testing

### Test Environment
- **Base URL**: `https://staging.your-domain.com/api/external`
- **Test API Key**: Contact administrator for test credentials

### Sample cURL Commands

#### Create Product
```bash
curl -X POST https://your-domain.com/api/external/products \
  -H "Content-Type: application/json" \
  -H "X-API-Key: your_api_key" \
  -H "X-API-Secret: your_api_secret" \
  -d '{
    "seller_id": 1,
    "shop_id": 1,
    "name": "Test Product",
    "description": "Test Description",
    "price": 100.00,
    "stock": 50,
    "category": "Electronics",
    "image": "https://example.com/image.jpg"
  }'
```

#### Update Stock
```bash
curl -X PATCH https://your-domain.com/api/external/products/123/stock \
  -H "Content-Type: application/json" \
  -H "X-API-Key: your_api_key" \
  -H "X-API-Secret: your_api_secret" \
  -d '{
    "stock": 75,
    "operation": "set"
  }'
```

## Integration Checklist

### Before Integration
- [ ] Obtain API key and secret from platform administrator
- [ ] Test API connectivity with sample requests
- [ ] Review product data requirements
- [ ] Set up error handling and logging

### During Integration
- [ ] Implement proper authentication headers
- [ ] Handle rate limiting and retries
- [ ] Validate all input data
- [ ] Implement proper error handling
- [ ] Test all endpoints thoroughly

### After Integration
- [ ] Monitor API usage and performance
- [ ] Set up webhook endpoints (if needed)
- [ ] Implement monitoring and alerting
- [ ] Document any custom integrations

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

## Quick Start Example

Here's a complete example of how to integrate with the API:

```php
<?php

class EcommerceApiClient
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

    public function createProduct($productData)
    {
        $url = $this->baseUrl . '/products';
        
        $headers = [
            'Content-Type: application/json',
            'X-API-Key: ' . $this->apiKey,
            'X-API-Secret: ' . $this->apiSecret
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($productData));
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
$client = new EcommerceApiClient(
    'https://your-domain.com/api/external',
    'your_api_key',
    'your_api_secret'
);

$productData = [
    'seller_id' => 1,
    'shop_id' => 1,
    'name' => 'Gaming Mouse Pro',
    'description' => 'High-performance gaming mouse',
    'price' => 2999.99,
    'stock' => 100,
    'category' => 'Electronics',
    'image' => 'https://example.com/images/gaming-mouse.jpg'
];

$result = $client->createProduct($productData);

if ($result['status_code'] === 201) {
    echo "Product created successfully!";
    echo "Product ID: " . $result['response']['data']['id'];
} else {
    echo "Error: " . $result['response']['message'];
}
```

This integration guide provides everything the Core Transaction 2 team needs to successfully connect and post seller products to your e-commerce platform!











