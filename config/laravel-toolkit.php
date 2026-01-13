<?php

declare(strict_types=1);

// config for SapB1/Toolkit
return [
    /*
    |--------------------------------------------------------------------------
    | Default Connection
    |--------------------------------------------------------------------------
    |
    | The default SAP B1 connection to use. This references the connections
    | defined in the sapb1/laravel-sdk configuration.
    |
    */
    'default_connection' => env('SAPB1_TOOLKIT_CONNECTION', 'default'),

    /*
    |--------------------------------------------------------------------------
    | Event Dispatching
    |--------------------------------------------------------------------------
    |
    | Enable or disable event dispatching for document operations.
    | When enabled, events like DocumentCreated, DocumentUpdated, etc.
    | will be dispatched for relevant operations.
    |
    */
    'dispatch_events' => env('SAPB1_TOOLKIT_DISPATCH_EVENTS', true),

    /*
    |--------------------------------------------------------------------------
    | Caching
    |--------------------------------------------------------------------------
    |
    | Configure caching for SAP B1 data. Cache is DISABLED by default.
    | You can enable it globally, per-entity, or per-query.
    |
    | Priority Order (Highest to Lowest):
    | 1. Query-level  → Model::cache(600)->find($id) or Model::noCache()->find($id)
    | 2. Model-level  → protected static bool $cacheEnabled = true (in model class)
    | 3. Entity-level → entities.{Entity}.enabled below
    | 4. Global-level → enabled below (default: false)
    |
    */
    'cache' => [
        // Global cache switch (default: disabled)
        'enabled' => env('SAPB1_TOOLKIT_CACHE_ENABLED', false),

        // Default TTL in seconds (1 hour)
        'ttl' => env('SAPB1_TOOLKIT_CACHE_TTL', 3600),

        // Cache key prefix
        'prefix' => 'sapb1_toolkit_',

        // Cache store (null = default Laravel cache store)
        // Use 'redis', 'file', 'database', etc.
        'store' => env('SAPB1_TOOLKIT_CACHE_STORE'),

        /*
        |--------------------------------------------------------------------------
        | Entity-Specific Cache Settings
        |--------------------------------------------------------------------------
        |
        | Configure caching per entity. If not specified, global settings are used.
        |
        | Example:
        | 'entities' => [
        |     'Items' => [
        |         'enabled' => true,
        |         'ttl' => 7200,  // 2 hours for Items
        |     ],
        |     'BusinessPartners' => [
        |         'enabled' => true,
        |         'ttl' => 1800,  // 30 minutes for BusinessPartners
        |     ],
        |     'Orders' => [
        |         'enabled' => false,  // Never cache Orders
        |     ],
        | ],
        |
        */
        'entities' => [
            // 'Items' => [
            //     'enabled' => true,
            //     'ttl' => 7200,
            // ],
            // 'BusinessPartners' => [
            //     'enabled' => true,
            //     'ttl' => 1800,
            // ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Local Database Sync (v2.7.0)
    |--------------------------------------------------------------------------
    |
    | Configure synchronization of SAP B1 data to local database.
    | This feature is OPT-IN: run 'php artisan sapb1:sync-setup' to create
    | migrations for the entities you want to sync.
    |
    | Available entities:
    | - Master Data: Items, BusinessPartners
    | - Sales: Orders, Invoices, DeliveryNotes, Quotations, CreditNotes
    | - Purchase: PurchaseOrders, PurchaseInvoices, GoodsReceiptPO
    |
    */
    'sync' => [
        // Global sync switch (default: enabled when tables exist)
        'enabled' => env('SAPB1_TOOLKIT_SYNC_ENABLED', true),

        // Default batch size for sync operations
        'batch_size' => env('SAPB1_TOOLKIT_SYNC_BATCH_SIZE', 5000),

        // Table name prefix for synced entities
        'table_prefix' => 'sap_',

        // Track soft deletes (detect deleted records in SAP)
        'track_deletes' => env('SAPB1_TOOLKIT_SYNC_TRACK_DELETES', true),

        // Dispatch events (SyncStarted, SyncCompleted, SyncFailed)
        'dispatch_events' => env('SAPB1_TOOLKIT_SYNC_DISPATCH_EVENTS', true),

        /*
        |--------------------------------------------------------------------------
        | Queue Configuration (v2.8.0)
        |--------------------------------------------------------------------------
        |
        | Configure queue settings for async sync operations.
        | Use SyncEntityJob::dispatch('Items') to queue sync jobs.
        |
        */
        'queue' => [
            // Default queue connection for sync jobs
            'connection' => env('SAPB1_TOOLKIT_SYNC_QUEUE_CONNECTION'),

            // Default queue name for sync jobs
            'queue' => env('SAPB1_TOOLKIT_SYNC_QUEUE', 'default'),

            // Number of retry attempts
            'tries' => env('SAPB1_TOOLKIT_SYNC_QUEUE_TRIES', 3),

            // Backoff time between retries (seconds)
            'backoff' => env('SAPB1_TOOLKIT_SYNC_QUEUE_BACKOFF', 60),
        ],

        /*
        |--------------------------------------------------------------------------
        | Entity-Specific Sync Settings
        |--------------------------------------------------------------------------
        |
        | Configure sync per entity. If not specified, defaults are used.
        |
        | Example:
        | 'entities' => [
        |     'Items' => [
        |         'enabled' => true,
        |         'batch_size' => 10000,
        |         'track_deletes' => true,
        |     ],
        |     'Orders' => [
        |         'enabled' => true,
        |         'sync_lines' => true,
        |     ],
        | ],
        |
        */
        'entities' => [
            // 'Items' => [
            //     'enabled' => true,
            //     'batch_size' => 10000,
            // ],
            // 'BusinessPartners' => [
            //     'enabled' => true,
            // ],
        ],
    ],
];
