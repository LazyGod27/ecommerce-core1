<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Core Transaction 2 Webhook Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for sending webhooks to Core Transaction 2 system
    |
    */
    'core_transaction_2_url' => env('CORE_TRANSACTION_2_WEBHOOK_URL', 'https://core-transaction-2.example.com/webhooks/ecommerce-updates'),
    'core_transaction_2_secret' => env('CORE_TRANSACTION_2_WEBHOOK_SECRET', 'core_transaction_2_secret_key'),

    /*
    |--------------------------------------------------------------------------
    | Core Transaction 3 Webhook Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for sending webhooks to Core Transaction 3 system
    |
    */
    'core_transaction_3_url' => env('CORE_TRANSACTION_3_WEBHOOK_URL', 'https://core-transaction-3.example.com/webhooks/ecommerce-updates'),
    'core_transaction_3_secret' => env('CORE_TRANSACTION_3_WEBHOOK_SECRET', 'core_transaction_3_secret_key'),

    /*
    |--------------------------------------------------------------------------
    | Webhook Settings
    |--------------------------------------------------------------------------
    |
    | General webhook configuration settings
    |
    */
    'timeout' => env('WEBHOOK_TIMEOUT', 30),
    'retry_attempts' => env('WEBHOOK_RETRY_ATTEMPTS', 3),
    'retry_delay' => env('WEBHOOK_RETRY_DELAY', 5), // seconds

    /*
    |--------------------------------------------------------------------------
    | Event Types
    |--------------------------------------------------------------------------
    |
    | Available event types that can be sent via webhooks
    |
    */
    'event_types' => [
        'customer.created',
        'customer.updated',
        'order.created',
        'order.updated',
        'order.status_changed',
        'payment.status_changed',
        'product.viewed',
        'product.added_to_cart',
        'review.created',
        'review.updated',
        'cart.abandoned',
        'marketplace.analytics'
    ],

    /*
    |--------------------------------------------------------------------------
    | System Mapping
    |--------------------------------------------------------------------------
    |
    | Maps event types to which systems should receive them
    |
    */
    'system_mapping' => [
        'customer.created' => ['core_transaction_2', 'core_transaction_3'],
        'customer.updated' => ['core_transaction_2', 'core_transaction_3'],
        'order.created' => ['core_transaction_2', 'core_transaction_3'],
        'order.updated' => ['core_transaction_2', 'core_transaction_3'],
        'order.status_changed' => ['core_transaction_2', 'core_transaction_3'],
        'payment.status_changed' => ['core_transaction_2', 'core_transaction_3'],
        'product.viewed' => ['core_transaction_2', 'core_transaction_3'],
        'product.added_to_cart' => ['core_transaction_2', 'core_transaction_3'],
        'review.created' => ['core_transaction_2', 'core_transaction_3'],
        'review.updated' => ['core_transaction_2', 'core_transaction_3'],
        'cart.abandoned' => ['core_transaction_2', 'core_transaction_3'],
        'marketplace.analytics' => ['core_transaction_3']
    ]
];
