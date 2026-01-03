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
    | Configure caching for master data like Items, BusinessPartners, etc.
    |
    */
    'cache' => [
        'enabled' => env('SAPB1_TOOLKIT_CACHE_ENABLED', false),
        'ttl' => env('SAPB1_TOOLKIT_CACHE_TTL', 3600), // seconds
        'prefix' => 'sapb1_toolkit_',
    ],
];
