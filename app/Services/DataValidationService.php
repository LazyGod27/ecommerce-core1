<?php

namespace App\Services;

use App\Models\Seller;
use App\Models\Product;
use App\Models\Order;
use App\Models\Earning;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class DataValidationService
{
    /**
     * Validate seller data
     */
    public function validateSellerData($data, $sellerId = null)
    {
        $rules = [
            'business_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'status' => 'required|in:active,inactive,suspended,pending',
            'verification_status' => 'required|in:pending,verified,rejected',
            'commission_rate' => 'required|numeric|min:0|max:100',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'business_type' => 'nullable|string|max:100',
            'tax_id' => 'nullable|string|max:50'
        ];

        // Add unique validation for email if creating new seller
        if (!$sellerId) {
            $rules['email'] .= '|unique:users,email';
        } else {
            $rules['email'] .= '|unique:users,email,' . Seller::find($sellerId)->user_id;
        }

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }

    /**
     * Validate product data
     */
    public function validateProductData($data, $productId = null)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:2000',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category' => 'required|string|max:100',
            'status' => 'required|in:draft,active,inactive,suspended',
            'seller_id' => 'required|exists:sellers,id',
            'shop_id' => 'required|exists:shops,id',
            'sku' => 'nullable|string|max:100',
            'barcode' => 'nullable|string|max:50',
            'weight' => 'nullable|numeric|min:0',
            'dimensions' => 'nullable|array',
            'brand' => 'nullable|string|max:100',
            'model' => 'nullable|string|max:100',
            'condition' => 'nullable|in:new,used,refurbished',
            'is_digital' => 'boolean',
            'tags' => 'nullable|array',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'seo_keywords' => 'nullable|array'
        ];

        // Add unique validation for SKU if provided
        if (isset($data['sku']) && $data['sku']) {
            $rules['sku'] .= '|unique:products,sku';
            if ($productId) {
                $rules['sku'] .= ',' . $productId;
            }
        }

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }

    /**
     * Validate order data
     */
    public function validateOrderData($data, $orderId = null)
    {
        $rules = [
            'status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled,returned',
            'payment_status' => 'required|in:pending,paid,failed,refunded,partially_refunded',
            'shipping_status' => 'required|in:pending,processing,shipped,delivered,returned',
            'total_amount' => 'required|numeric|min:0',
            'shipping_address' => 'required|array',
            'billing_address' => 'required|array',
            'tracking_number' => 'nullable|string|max:100',
            'carrier' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:1000'
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }

    /**
     * Validate earnings data
     */
    public function validateEarningsData($data)
    {
        $rules = [
            'seller_id' => 'required|exists:sellers,id',
            'order_id' => 'required|exists:orders,id',
            'amount' => 'required|numeric|min:0',
            'commission_amount' => 'required|numeric|min:0',
            'net_amount' => 'required|numeric|min:0',
            'period' => 'required|in:week,month,year'
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }

    /**
     * Validate platform settings data
     */
    public function validatePlatformSettingsData($data)
    {
        $rules = [
            'platform_name' => 'required|string|max:255',
            'default_commission_rate' => 'required|numeric|min:0|max:100',
            'min_payout_amount' => 'required|numeric|min:0',
            'auto_approve_payout_limit' => 'required|numeric|min:0',
            'supported_payment_methods' => 'required|array',
            'supported_payment_methods.*' => 'string|in:gcash,paymaya,bank_transfer,stripe',
            'supported_shipping_carriers' => 'required|array',
            'supported_shipping_carriers.*' => 'string|in:jnt,ninjavan,lbc,flash',
            'currency' => 'required|string|in:PHP,USD,EUR',
            'timezone' => 'required|string|max:50'
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }

    /**
     * Resolve data conflicts between systems
     */
    public function resolveDataConflict($entityType, $entityId, $localData, $remoteData, $conflictResolution = 'remote_wins')
    {
        try {
            switch ($conflictResolution) {
                case 'remote_wins':
                    return $this->resolveWithRemoteWins($entityType, $entityId, $localData, $remoteData);
                
                case 'local_wins':
                    return $this->resolveWithLocalWins($entityType, $entityId, $localData, $remoteData);
                
                case 'merge':
                    return $this->resolveWithMerge($entityType, $entityId, $localData, $remoteData);
                
                case 'timestamp_based':
                    return $this->resolveWithTimestamp($entityType, $entityId, $localData, $remoteData);
                
                default:
                    throw new \InvalidArgumentException("Unknown conflict resolution strategy: {$conflictResolution}");
            }
        } catch (\Exception $e) {
            Log::error("Failed to resolve data conflict: " . $e->getMessage(), [
                'entity_type' => $entityType,
                'entity_id' => $entityId,
                'conflict_resolution' => $conflictResolution
            ]);
            throw $e;
        }
    }

    /**
     * Resolve conflict with remote data winning
     */
    private function resolveWithRemoteWins($entityType, $entityId, $localData, $remoteData)
    {
        Log::info("Resolving conflict with remote data winning", [
            'entity_type' => $entityType,
            'entity_id' => $entityId
        ]);

        return $remoteData;
    }

    /**
     * Resolve conflict with local data winning
     */
    private function resolveWithLocalWins($entityType, $entityId, $localData, $remoteData)
    {
        Log::info("Resolving conflict with local data winning", [
            'entity_type' => $entityType,
            'entity_id' => $entityId
        ]);

        return $localData;
    }

    /**
     * Resolve conflict by merging data
     */
    private function resolveWithMerge($entityType, $entityId, $localData, $remoteData)
    {
        Log::info("Resolving conflict by merging data", [
            'entity_type' => $entityType,
            'entity_id' => $entityId
        ]);

        // Define merge rules for each entity type
        $mergeRules = $this->getMergeRules($entityType);
        
        $mergedData = $localData;
        
        foreach ($mergeRules as $field => $rule) {
            if (isset($remoteData[$field])) {
                switch ($rule) {
                    case 'remote_wins':
                        $mergedData[$field] = $remoteData[$field];
                        break;
                    case 'local_wins':
                        // Keep local value
                        break;
                    case 'latest_timestamp':
                        if (isset($remoteData['updated_at']) && isset($localData['updated_at'])) {
                            $remoteTime = strtotime($remoteData['updated_at']);
                            $localTime = strtotime($localData['updated_at']);
                            if ($remoteTime > $localTime) {
                                $mergedData[$field] = $remoteData[$field];
                            }
                        }
                        break;
                    case 'array_merge':
                        if (is_array($localData[$field]) && is_array($remoteData[$field])) {
                            $mergedData[$field] = array_unique(array_merge($localData[$field], $remoteData[$field]));
                        }
                        break;
                }
            }
        }

        return $mergedData;
    }

    /**
     * Resolve conflict based on timestamp
     */
    private function resolveWithTimestamp($entityType, $entityId, $localData, $remoteData)
    {
        Log::info("Resolving conflict based on timestamp", [
            'entity_type' => $entityType,
            'entity_id' => $entityId
        ]);

        $localTimestamp = isset($localData['updated_at']) ? strtotime($localData['updated_at']) : 0;
        $remoteTimestamp = isset($remoteData['updated_at']) ? strtotime($remoteData['updated_at']) : 0;

        return $remoteTimestamp > $localTimestamp ? $remoteData : $localData;
    }

    /**
     * Get merge rules for entity type
     */
    private function getMergeRules($entityType)
    {
        $rules = [
            'seller' => [
                'business_name' => 'remote_wins',
                'email' => 'remote_wins',
                'status' => 'remote_wins',
                'verification_status' => 'remote_wins',
                'commission_rate' => 'remote_wins',
                'phone' => 'remote_wins',
                'address' => 'remote_wins',
                'business_type' => 'remote_wins',
                'tax_id' => 'remote_wins',
                'tags' => 'array_merge'
            ],
            'product' => [
                'name' => 'remote_wins',
                'description' => 'remote_wins',
                'price' => 'remote_wins',
                'stock' => 'remote_wins',
                'category' => 'remote_wins',
                'status' => 'remote_wins',
                'sku' => 'remote_wins',
                'barcode' => 'remote_wins',
                'weight' => 'remote_wins',
                'dimensions' => 'remote_wins',
                'brand' => 'remote_wins',
                'model' => 'remote_wins',
                'condition' => 'remote_wins',
                'is_digital' => 'remote_wins',
                'tags' => 'array_merge',
                'meta_title' => 'remote_wins',
                'meta_description' => 'remote_wins',
                'seo_keywords' => 'array_merge'
            ],
            'order' => [
                'status' => 'remote_wins',
                'payment_status' => 'remote_wins',
                'shipping_status' => 'remote_wins',
                'total_amount' => 'remote_wins',
                'tracking_number' => 'remote_wins',
                'carrier' => 'remote_wins',
                'notes' => 'remote_wins',
                'shipping_address' => 'remote_wins',
                'billing_address' => 'remote_wins'
            ]
        ];

        return $rules[$entityType] ?? [];
    }

    /**
     * Validate data integrity
     */
    public function validateDataIntegrity($entityType, $entityId, $data)
    {
        try {
            switch ($entityType) {
                case 'seller':
                    return $this->validateSellerIntegrity($entityId, $data);
                case 'product':
                    return $this->validateProductIntegrity($entityId, $data);
                case 'order':
                    return $this->validateOrderIntegrity($entityId, $data);
                default:
                    return true;
            }
        } catch (\Exception $e) {
            Log::error("Data integrity validation failed: " . $e->getMessage(), [
                'entity_type' => $entityType,
                'entity_id' => $entityId
            ]);
            return false;
        }
    }

    /**
     * Validate seller data integrity
     */
    private function validateSellerIntegrity($sellerId, $data)
    {
        $seller = Seller::find($sellerId);
        if (!$seller) {
            return false;
        }

        // Check if user exists
        if (!$seller->user) {
            Log::error("Seller has no associated user", ['seller_id' => $sellerId]);
            return false;
        }

        // Check if email matches user email
        if (isset($data['email']) && $data['email'] !== $seller->user->email) {
            Log::error("Seller email mismatch", [
                'seller_id' => $sellerId,
                'seller_email' => $data['email'],
                'user_email' => $seller->user->email
            ]);
            return false;
        }

        return true;
    }

    /**
     * Validate product data integrity
     */
    private function validateProductIntegrity($productId, $data)
    {
        $product = Product::find($productId);
        if (!$product) {
            return false;
        }

        // Check if seller exists
        if (!$product->seller) {
            Log::error("Product has no associated seller", ['product_id' => $productId]);
            return false;
        }

        // Check if shop exists and belongs to seller
        if (!$product->shop || $product->shop->seller_id !== $product->seller_id) {
            Log::error("Product shop mismatch", [
                'product_id' => $productId,
                'shop_id' => $product->shop_id,
                'seller_id' => $product->seller_id
            ]);
            return false;
        }

        return true;
    }

    /**
     * Validate order data integrity
     */
    private function validateOrderIntegrity($orderId, $data)
    {
        $order = Order::find($orderId);
        if (!$order) {
            return false;
        }

        // Check if user exists
        if (!$order->user) {
            Log::error("Order has no associated user", ['order_id' => $orderId]);
            return false;
        }

        // Check if order has items
        if ($order->items->isEmpty()) {
            Log::error("Order has no items", ['order_id' => $orderId]);
            return false;
        }

        return true;
    }

    /**
     * Sanitize data before processing
     */
    public function sanitizeData($data)
    {
        $sanitized = [];

        foreach ($data as $key => $value) {
            if (is_string($value)) {
                // Remove HTML tags and trim whitespace
                $sanitized[$key] = trim(strip_tags($value));
            } elseif (is_array($value)) {
                // Recursively sanitize arrays
                $sanitized[$key] = $this->sanitizeData($value);
            } else {
                $sanitized[$key] = $value;
            }
        }

        return $sanitized;
    }

    /**
     * Check for data conflicts
     */
    public function checkDataConflicts($entityType, $entityId, $localData, $remoteData)
    {
        $conflicts = [];

        foreach ($localData as $key => $localValue) {
            if (isset($remoteData[$key])) {
                $remoteValue = $remoteData[$key];
                
                if ($localValue !== $remoteValue) {
                    $conflicts[] = [
                        'field' => $key,
                        'local_value' => $localValue,
                        'remote_value' => $remoteValue,
                        'conflict_type' => $this->getConflictType($key, $localValue, $remoteValue)
                    ];
                }
            }
        }

        return $conflicts;
    }

    /**
     * Get conflict type
     */
    private function getConflictType($field, $localValue, $remoteValue)
    {
        if (is_numeric($localValue) && is_numeric($remoteValue)) {
            return 'numeric_difference';
        } elseif (is_array($localValue) && is_array($remoteValue)) {
            return 'array_difference';
        } elseif (is_string($localValue) && is_string($remoteValue)) {
            return 'string_difference';
        } else {
            return 'type_difference';
        }
    }
}
