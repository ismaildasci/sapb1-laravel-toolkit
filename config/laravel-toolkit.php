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

    /*
    |--------------------------------------------------------------------------
    | Multi-Tenant Support (v2.9.0)
    |--------------------------------------------------------------------------
    |
    | Configure multi-tenant SAP B1 connections. This allows a single Laravel
    | application to connect to multiple SAP B1 databases (tenants).
    |
    | Resolver options:
    | - 'config'  : Reads tenant from manually set context (ConfigTenantResolver)
    | - 'header'  : Reads tenant from X-Tenant-ID header (HeaderTenantResolver)
    | - 'user'    : Reads tenant from authenticated user (AuthUserTenantResolver)
    | - 'custom'  : Your own resolver implementing TenantResolverInterface
    |
    */
    'multi_tenant' => [
        // Enable multi-tenant mode
        'enabled' => env('SAPB1_TOOLKIT_MULTI_TENANT_ENABLED', false),

        // Default resolver type: 'config', 'header', 'user', or class name
        'resolver' => env('SAPB1_TOOLKIT_MULTI_TENANT_RESOLVER', 'config'),

        // Header name for header-based resolution
        'header' => env('SAPB1_TOOLKIT_MULTI_TENANT_HEADER', 'X-Tenant-ID'),

        // Query parameter name for URL-based resolution
        'query_param' => 'tenant_id',

        // User attribute name for user-based resolution
        'user_attribute' => 'tenant_id',

        /*
        |--------------------------------------------------------------------------
        | Subdomain-based Resolution
        |--------------------------------------------------------------------------
        |
        | Enable subdomain-based tenant resolution (e.g., tenant1.example.com).
        |
        */
        'subdomain' => [
            'enabled' => env('SAPB1_TOOLKIT_MULTI_TENANT_SUBDOMAIN_ENABLED', false),
            'base_domain' => env('SAPB1_TOOLKIT_MULTI_TENANT_BASE_DOMAIN', ''),
        ],

        /*
        |--------------------------------------------------------------------------
        | Tenant Configurations
        |--------------------------------------------------------------------------
        |
        | Define SAP B1 connection configuration for each tenant.
        |
        | Example:
        | 'tenants' => [
        |     'tenant-1' => [
        |         'sap_url' => 'https://sap1.example.com:50000/b1s/v1',
        |         'sap_database' => 'SBO_TENANT1',
        |         'sap_username' => 'manager',
        |         'sap_password' => env('TENANT1_SAP_PASSWORD'),
        |     ],
        |     'tenant-2' => [
        |         'sap_url' => 'https://sap2.example.com:50000/b1s/v1',
        |         'sap_database' => 'SBO_TENANT2',
        |         'sap_username' => 'manager',
        |         'sap_password' => env('TENANT2_SAP_PASSWORD'),
        |     ],
        | ],
        |
        */
        'tenants' => [
            // 'tenant-1' => [
            //     'sap_url' => 'https://sap1.example.com:50000/b1s/v1',
            //     'sap_database' => 'SBO_TENANT1',
            //     'sap_username' => 'manager',
            //     'sap_password' => env('TENANT1_SAP_PASSWORD'),
            // ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Audit Logging (v3.0.0)
    |--------------------------------------------------------------------------
    |
    | Configure audit logging for SAP B1 operations. This feature tracks all
    | create, update, and delete operations on SAP entities for compliance
    | and debugging purposes.
    |
    | Available drivers:
    | - 'database' : Store to database (recommended for production)
    | - 'log'      : Write to Laravel log (useful for debugging)
    | - 'null'     : No-op driver (for testing)
    | - Custom class implementing AuditDriverInterface
    |
    */
    'audit' => [
        // Enable/disable audit logging globally
        'enabled' => env('SAPB1_TOOLKIT_AUDIT_ENABLED', true),

        // Audit storage driver: 'database', 'log', 'null', or custom class
        'driver' => env('SAPB1_TOOLKIT_AUDIT_DRIVER', 'database'),

        // Database table name for audit logs
        'table' => 'sap_audit_logs',

        // Database connection (null = default)
        'connection' => env('SAPB1_TOOLKIT_AUDIT_CONNECTION'),

        // Log channel for 'log' driver
        'log_channel' => env('SAPB1_TOOLKIT_AUDIT_LOG_CHANNEL', 'stack'),

        // Log level for 'log' driver
        'log_level' => 'info',

        // Dispatch events (AuditRecorded, AuditFailed)
        'dispatch_events' => env('SAPB1_TOOLKIT_AUDIT_DISPATCH_EVENTS', true),

        /*
        |--------------------------------------------------------------------------
        | Global Field Exclusions
        |--------------------------------------------------------------------------
        |
        | Fields that should NEVER be logged for security reasons.
        | These are excluded from ALL entities.
        |
        */
        'exclude' => [
            'Password',
            'EncryptedPassword',
            'ApiKey',
            'Token',
            'Secret',
        ],

        /*
        |--------------------------------------------------------------------------
        | Retention Policy
        |--------------------------------------------------------------------------
        |
        | Configure how long audit logs should be retained.
        | Use 'php artisan model:prune' to clean old logs.
        |
        */
        'retention' => [
            'enabled' => env('SAPB1_TOOLKIT_AUDIT_RETENTION_ENABLED', true),
            'days' => env('SAPB1_TOOLKIT_AUDIT_RETENTION_DAYS', 365),
        ],

        /*
        |--------------------------------------------------------------------------
        | Context Capture
        |--------------------------------------------------------------------------
        |
        | Configure what context information to capture with each audit entry.
        |
        */
        'context' => [
            'user' => true,        // Capture user ID and type
            'ip_address' => true,  // Capture IP address
            'user_agent' => true,  // Capture user agent
            'tenant' => true,      // Capture tenant ID (if multi-tenant)
        ],

        /*
        |--------------------------------------------------------------------------
        | Entity-Specific Audit Settings
        |--------------------------------------------------------------------------
        |
        | Configure auditing per entity. If not specified, global settings apply.
        |
        | Example:
        | 'entities' => [
        |     'Orders' => [
        |         'enabled' => true,
        |         'events' => ['created', 'updated'],
        |         'exclude' => ['InternalNotes'],
        |     ],
        |     'BusinessPartners' => [
        |         'enabled' => true,
        |         'exclude' => ['BankAccount', 'TaxId'],
        |     ],
        |     'Items' => [
        |         'enabled' => false,  // Disable auditing for Items
        |     ],
        | ],
        |
        */
        'entities' => [
            // 'Orders' => [
            //     'enabled' => true,
            //     'events' => ['created', 'updated'],
            // ],
            // 'BusinessPartners' => [
            //     'enabled' => true,
            //     'exclude' => ['BankAccount'],
            // ],
        ],
    ],
];
