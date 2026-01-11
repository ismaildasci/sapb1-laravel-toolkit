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
];
