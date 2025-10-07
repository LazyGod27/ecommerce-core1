# Core Transaction 3: Platform Control & Revenue Management API Documentation

## Overview
This documentation covers the platform management system for Core Transaction 3 team, which handles platform control, revenue management, seller oversight, commission management, and business operations.

## Base URL
```
https://your-domain.com/api/platform
```

## Authentication
All requests require API key authentication with `platform_management` permission:
```
X-API-Key: your_api_key_here
X-API-Secret: your_api_secret_here
```

## Platform Dashboard

### 1. Get Platform Dashboard
**GET** `/dashboard`

Retrieves comprehensive platform overview with key metrics and analytics.

#### Query Parameters
- `period` (optional): Number of days for metrics (default: 30)

#### Response
```json
{
    "success": true,
    "data": {
        "stats": {
            "total_users": 1500,
            "total_sellers": 250,
            "active_sellers": 200,
            "total_products": 5000,
            "total_orders": 10000,
            "total_revenue": 2500000.00,
            "active_subscriptions": 180
        },
        "metrics": {
            "revenue_growth": 15.5,
            "seller_growth": 8.2,
            "order_growth": 12.3,
            "conversion_rate": 3.2
        },
        "platform_data": {
            "revenue_breakdown": {
                "total_revenue": 2500000.00,
                "commission_earned": 125000.00,
                "platform_fee_earned": 50000.00,
                "subscription_revenue": 45000.00,
                "by_carrier": [
                    {
                        "carrier": "J&T Express",
                        "revenue": 1200000.00
                    }
                ],
                "by_category": [
                    {
                        "category": "Electronics",
                        "revenue": 800000.00
                    }
                ]
            },
            "top_sellers": [
                {
                    "id": 1,
                    "business_name": "TechStore PH",
                    "orders_count": 150,
                    "user": {
                        "name": "John Doe",
                        "email": "john@techstore.com"
                    }
                }
            ],
            "top_products": [
                {
                    "id": 1,
                    "name": "Gaming Laptop",
                    "order_items_count": 45,
                    "seller": {
                        "business_name": "TechStore PH"
                    }
                }
            ],
            "recent_activities": {
                "new_sellers": 12,
                "new_orders": 150,
                "pending_approvals": 8,
                "pending_payouts": 25
            },
            "system_health": {
                "database_status": "healthy",
                "api_response_time": "120ms",
                "error_rate": "0.1%",
                "uptime": "99.9%",
                "active_users": 450
            }
        }
    }
}
```

## Seller Management

### 2. Get Sellers
**GET** `/sellers`

Retrieves paginated list of sellers with filtering options.

#### Query Parameters
- `status` (optional): Filter by seller status (active, pending, suspended)
- `verification_status` (optional): Filter by verification status
- `subscription_plan` (optional): Filter by subscription plan
- `search` (optional): Search by business name or email
- `date_from` (optional): Filter from date
- `date_to` (optional): Filter to date
- `per_page` (optional): Items per page (default: 15)

#### Response
```json
{
    "success": true,
    "data": {
        "current_page": 1,
        "data": [
            {
                "id": 1,
                "business_name": "TechStore PH",
                "email": "john@techstore.com",
                "status": "active",
                "verification_status": "verified",
                "subscription_plan": "premium",
                "total_sales": 150000.00,
                "total_products": 45,
                "commission_rate": 5.0,
                "created_at": "2024-01-01T00:00:00Z",
                "user": {
                    "name": "John Doe",
                    "email": "john@techstore.com"
                },
                "shops": [
                    {
                        "id": 1,
                        "name": "TechStore Main",
                        "status": "active"
                    }
                ],
                "subscriptions": [
                    {
                        "id": 1,
                        "plan": "premium",
                        "status": "active"
                    }
                ]
            }
        ],
        "total": 250,
        "per_page": 15,
        "last_page": 17
    }
}
```

### 3. Approve Seller
**POST** `/sellers/{sellerId}/approve`

Approves a pending seller application.

#### Request Body
```json
{
    "notes": "Seller application approved after document verification",
    "commission_rate": 5.0,
    "subscription_plan": "standard"
}
```

#### Response
```json
{
    "success": true,
    "message": "Seller approved successfully",
    "data": {
        "id": 1,
        "business_name": "TechStore PH",
        "status": "active",
        "verification_status": "verified",
        "approved_at": "2024-01-15T10:30:00Z",
        "user": {
            "name": "John Doe",
            "email": "john@techstore.com"
        }
    }
}
```

### 4. Suspend Seller
**POST** `/sellers/{sellerId}/suspend`

Suspends a seller account.

#### Request Body
```json
{
    "reason": "Violation of platform policies - selling prohibited items",
    "duration": 30,
    "notify_seller": true
}
```

#### Response
```json
{
    "success": true,
    "message": "Seller suspended successfully",
    "data": {
        "id": 1,
        "business_name": "TechStore PH",
        "status": "suspended",
        "suspension_reason": "Violation of platform policies",
        "suspended_at": "2024-01-15T10:30:00Z"
    }
}
```

## Subscription Plans Management

### 5. Get Subscription Plans
**GET** `/subscription-plans`

Retrieves all subscription plans with filtering options.

#### Query Parameters
- `type` (optional): Filter by plan type (basic, standard, premium, enterprise)
- `is_active` (optional): Filter by active status

#### Response
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "name": "Basic Plan",
            "slug": "basic",
            "description": "Perfect for small sellers",
            "type": "basic",
            "price": 99.00,
            "currency": "PHP",
            "billing_cycle": "monthly",
            "trial_days": 7,
            "features": [
                "Up to 50 products",
                "Basic analytics",
                "Email support"
            ],
            "limits": {
                "max_products": 50,
                "max_orders_per_month": 100
            },
            "commission_rate": 8.0,
            "is_popular": false,
            "is_active": true,
            "sort_order": 1,
            "subscriptions": [
                {
                    "id": 1,
                    "seller_id": 1,
                    "status": "active"
                }
            ]
        }
    ]
}
```

### 6. Create Subscription Plan
**POST** `/subscription-plans`

Creates a new subscription plan.

#### Request Body
```json
{
    "name": "Enterprise Plan",
    "slug": "enterprise",
    "description": "For large businesses with high volume",
    "type": "enterprise",
    "price": 999.00,
    "currency": "PHP",
    "billing_cycle": "monthly",
    "trial_days": 14,
    "features": [
        "Unlimited products",
        "Advanced analytics",
        "Priority support",
        "Custom branding"
    ],
    "limits": {
        "max_products": -1,
        "max_orders_per_month": -1
    },
    "commission_rate": 3.0,
    "is_popular": true,
    "is_active": true,
    "sort_order": 4
}
```

#### Response
```json
{
    "success": true,
    "message": "Subscription plan created successfully",
    "data": {
        "id": 4,
        "name": "Enterprise Plan",
        "slug": "enterprise",
        "type": "enterprise",
        "price": 999.00,
        "commission_rate": 3.0,
        "created_at": "2024-01-15T10:30:00Z"
    }
}
```

## Commission Management

### 7. Get Commission Rules
**GET** `/commission-rules`

Retrieves all commission rules with filtering options.

#### Query Parameters
- `category` (optional): Filter by product category
- `is_active` (optional): Filter by active status

#### Response
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "name": "Electronics Commission",
            "description": "Standard commission for electronics",
            "category": "Electronics",
            "commission_rate": 5.0,
            "min_amount": 0,
            "max_amount": 10000,
            "subscription_plan_id": null,
            "priority": 1,
            "is_active": true,
            "effective_from": "2024-01-01T00:00:00Z",
            "effective_to": null,
            "subscription_plan": null
        }
    ]
}
```

### 8. Create Commission Rule
**POST** `/commission-rules`

Creates a new commission rule.

#### Request Body
```json
{
    "name": "Premium Electronics Commission",
    "description": "Reduced commission for premium electronics",
    "category": "Electronics",
    "commission_rate": 3.5,
    "min_amount": 1000,
    "max_amount": 50000,
    "subscription_plan_id": 3,
    "priority": 2,
    "is_active": true,
    "effective_from": "2024-02-01T00:00:00Z",
    "effective_to": "2024-12-31T23:59:59Z"
}
```

#### Response
```json
{
    "success": true,
    "message": "Commission rule created successfully",
    "data": {
        "id": 2,
        "name": "Premium Electronics Commission",
        "category": "Electronics",
        "commission_rate": 3.5,
        "priority": 2,
        "created_at": "2024-01-15T10:30:00Z"
    }
}
```

## Payout Management

### 9. Get Payout Requests
**GET** `/payout-requests`

Retrieves paginated list of payout requests with filtering options.

#### Query Parameters
- `status` (optional): Filter by status (pending, approved, rejected, processed)
- `seller_id` (optional): Filter by seller ID
- `date_from` (optional): Filter from date
- `date_to` (optional): Filter to date
- `per_page` (optional): Items per page (default: 15)

#### Response
```json
{
    "success": true,
    "data": {
        "current_page": 1,
        "data": [
            {
                "id": 1,
                "seller_id": 1,
                "amount": 2500.00,
                "currency": "PHP",
                "payment_method": "bank_transfer",
                "bank_details": {
                    "bank_name": "BDO",
                    "account_number": "1234567890",
                    "account_name": "John Doe"
                },
                "status": "pending",
                "requested_at": "2024-01-15T10:30:00Z",
                "earnings_ids": [1, 2, 3],
                "seller": {
                    "id": 1,
                    "business_name": "TechStore PH",
                    "user": {
                        "name": "John Doe",
                        "email": "john@techstore.com"
                    }
                },
                "earnings": [
                    {
                        "id": 1,
                        "amount": 1000.00,
                        "commission_amount": 50.00,
                        "net_amount": 950.00
                    }
                ]
            }
        ],
        "total": 25,
        "per_page": 15,
        "last_page": 2
    }
}
```

### 10. Process Payout Request
**POST** `/payout-requests/{requestId}/process`

Processes a payout request (approve or reject).

#### Request Body
```json
{
    "action": "approve",
    "notes": "Payout approved after verification",
    "transaction_id": "TXN123456789"
}
```

#### Response
```json
{
    "success": true,
    "message": "Payout request approved successfully",
    "data": {
        "id": 1,
        "status": "approved",
        "approved_at": "2024-01-15T11:00:00Z",
        "transaction_id": "TXN123456789",
        "notes": "Payout approved after verification"
    }
}
```

## Platform Settings

### 11. Update Platform Settings
**PUT** `/settings`

Updates platform-wide settings.

#### Request Body
```json
{
    "settings": [
        {
            "key": "platform_name",
            "value": "IMarketPH",
            "description": "Platform display name"
        },
        {
            "key": "default_commission_rate",
            "value": 5.0,
            "description": "Default commission rate for new sellers"
        },
        {
            "key": "min_payout_amount",
            "value": 100,
            "description": "Minimum amount for payout requests"
        },
        {
            "key": "auto_approve_payout_limit",
            "value": 500,
            "description": "Auto-approve payouts below this amount"
        },
        {
            "key": "supported_payment_methods",
            "value": ["gcash", "paymaya", "bank_transfer"],
            "description": "Supported payment methods"
        }
    ]
}
```

#### Response
```json
{
    "success": true,
    "message": "Platform settings updated successfully"
}
```

## Reports

### 12. Generate Report
**POST** `/reports`

Generates comprehensive platform reports.

#### Request Body
```json
{
    "type": "revenue",
    "format": "json",
    "filters": {
        "seller_id": 1,
        "category": "Electronics"
    },
    "date_from": "2024-01-01",
    "date_to": "2024-01-31",
    "group_by": "day"
}
```

#### Response
```json
{
    "success": true,
    "data": {
        "report_type": "revenue",
        "period": {
            "from": "2024-01-01",
            "to": "2024-01-31"
        },
        "summary": {
            "total_revenue": 150000.00,
            "total_commission": 7500.00,
            "total_platform_fee": 3000.00,
            "total_orders": 150,
            "average_order_value": 1000.00
        },
        "data": [
            {
                "date": "2024-01-01",
                "revenue": 5000.00,
                "orders": 5,
                "commission": 250.00
            },
            {
                "date": "2024-01-02",
                "revenue": 7500.00,
                "orders": 7,
                "commission": 375.00
            }
        ],
        "generated_at": "2024-01-15T10:30:00Z"
    }
}
```

## Report Types

### Available Report Types
- `sales` - Sales performance report
- `sellers` - Seller performance report
- `products` - Product performance report
- `earnings` - Commission and earnings report
- `revenue` - Revenue breakdown report
- `subscriptions` - Subscription analytics report

### Report Formats
- `json` - JSON format (default)
- `csv` - CSV format for export
- `pdf` - PDF format for printing

### Group By Options
- `day` - Group by day
- `week` - Group by week
- `month` - Group by month
- `year` - Group by year

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
- `seller_not_found` - Seller not found
- `invalid_status` - Invalid seller status
- `insufficient_permissions` - Insufficient permissions
- `validation_failed` - Request validation failed
- `payout_limit_exceeded` - Payout amount exceeds limit

### HTTP Status Codes
- `200` - Success
- `201` - Created
- `400` - Bad Request
- `401` - Unauthorized
- `403` - Forbidden
- `404` - Not Found
- `422` - Validation Error
- `500` - Internal Server Error

## Integration Examples

### PHP Integration Example
```php
<?php

class PlatformManagementClient
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

    public function getDashboard($period = 30)
    {
        $url = $this->baseUrl . "/dashboard?period={$period}";
        
        $headers = [
            'X-API-Key: ' . $this->apiKey,
            'X-API-Secret: ' . $this->apiSecret
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
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

    public function approveSeller($sellerId, $data)
    {
        $url = $this->baseUrl . "/sellers/{$sellerId}/approve";
        
        $headers = [
            'Content-Type: application/json',
            'X-API-Key: ' . $this->apiKey,
            'X-API-Secret: ' . $this->apiSecret
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
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

    public function processPayoutRequest($requestId, $action, $data = [])
    {
        $url = $this->baseUrl . "/payout-requests/{$requestId}/process";
        
        $data['action'] = $action;
        
        $headers = [
            'Content-Type: application/json',
            'X-API-Key: ' . $this->apiKey,
            'X-API-Secret: ' . $this->apiSecret
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
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
$client = new PlatformManagementClient(
    'https://your-domain.com/api/platform',
    'your_api_key',
    'your_api_secret'
);

// Get dashboard
$dashboard = $client->getDashboard(30);
if ($dashboard['status_code'] === 200) {
    echo "Total Revenue: " . $dashboard['response']['data']['stats']['total_revenue'];
}

// Approve seller
$approvalData = [
    'notes' => 'Seller approved after verification',
    'commission_rate' => 5.0,
    'subscription_plan' => 'standard'
];

$result = $client->approveSeller(1, $approvalData);
if ($result['status_code'] === 200) {
    echo "Seller approved successfully!";
}

// Process payout
$payoutData = [
    'notes' => 'Payout approved',
    'transaction_id' => 'TXN123456789'
];

$result = $client->processPayoutRequest(1, 'approve', $payoutData);
if ($result['status_code'] === 200) {
    echo "Payout processed successfully!";
}
```

### cURL Examples

#### Get Dashboard
```bash
curl -X GET https://your-domain.com/api/platform/dashboard?period=30 \
  -H "X-API-Key: your_api_key" \
  -H "X-API-Secret: your_api_secret"
```

#### Approve Seller
```bash
curl -X POST https://your-domain.com/api/platform/sellers/1/approve \
  -H "Content-Type: application/json" \
  -H "X-API-Key: your_api_key" \
  -H "X-API-Secret: your_api_secret" \
  -d '{
    "notes": "Seller approved after verification",
    "commission_rate": 5.0,
    "subscription_plan": "standard"
  }'
```

#### Process Payout Request
```bash
curl -X POST https://your-domain.com/api/platform/payout-requests/1/process \
  -H "Content-Type: application/json" \
  -H "X-API-Key: your_api_key" \
  -H "X-API-Secret: your_api_secret" \
  -d '{
    "action": "approve",
    "notes": "Payout approved after verification",
    "transaction_id": "TXN123456789"
  }'
```

#### Generate Report
```bash
curl -X POST https://your-domain.com/api/platform/reports \
  -H "Content-Type: application/json" \
  -H "X-API-Key: your_api_key" \
  -H "X-API-Secret: your_api_secret" \
  -d '{
    "type": "revenue",
    "format": "json",
    "date_from": "2024-01-01",
    "date_to": "2024-01-31",
    "group_by": "day"
  }'
```

## Best Practices

### 1. Seller Management
- Always verify seller documents before approval
- Set appropriate commission rates based on seller tier
- Monitor seller performance regularly
- Implement automated suspension for policy violations

### 2. Commission Management
- Use priority-based commission rules
- Set category-specific commission rates
- Monitor commission trends and adjust as needed
- Implement tier-based commission structures

### 3. Payout Management
- Verify bank details before processing payouts
- Implement minimum payout thresholds
- Use automated approval for small amounts
- Maintain detailed payout audit trails

### 4. Platform Settings
- Regularly review and update platform settings
- Implement feature flags for gradual rollouts
- Monitor system performance metrics
- Maintain backup and recovery procedures

### 5. Reporting
- Generate reports regularly for business insights
- Use appropriate date ranges for analysis
- Export reports in multiple formats
- Implement automated report scheduling

## Security Considerations

### 1. API Security
- Use strong API keys and secrets
- Implement rate limiting
- Monitor API usage patterns
- Use HTTPS for all communications

### 2. Data Protection
- Encrypt sensitive data
- Implement access controls
- Regular security audits
- Compliance with data protection regulations

### 3. Audit Logging
- Log all administrative actions
- Monitor for suspicious activities
- Implement alert systems
- Regular security reviews

## Support

### Contact Information
- **Platform Support**: platform-support@your-domain.com
- **Technical Issues**: tech-support@your-domain.com
- **Business Inquiries**: business@your-domain.com

### Resources
- **API Status Page**: https://status.your-domain.com
- **Platform Documentation**: https://docs.your-domain.com
- **Business Intelligence**: https://bi.your-domain.com

---

This comprehensive platform management system provides everything needed for Core Transaction 3 team to control the platform, manage revenue, oversee sellers, and handle all business operations effectively! 🚀











