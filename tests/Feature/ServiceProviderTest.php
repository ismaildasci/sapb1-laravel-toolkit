<?php

declare(strict_types=1);

use SapB1\Toolkit\Services\ApprovalService;
use SapB1\Toolkit\Services\DocumentFlowService;
use SapB1\Toolkit\Services\InventoryService;
use SapB1\Toolkit\Services\PaymentService;
use SapB1\Toolkit\Services\ReportingService;
use SapB1\Toolkit\Services\SyncService;
use SapB1\Toolkit\ToolkitServiceProvider;

it('registers the service provider', function () {
    expect(app()->getProviders(ToolkitServiceProvider::class))
        ->toHaveCount(1);
});

it('registers all services as singletons', function () {
    $services = [
        DocumentFlowService::class,
        PaymentService::class,
        InventoryService::class,
        ReportingService::class,
        ApprovalService::class,
        SyncService::class,
    ];

    foreach ($services as $service) {
        expect(app()->bound($service))->toBeTrue("Service {$service} should be bound");
    }
});

it('resolves the same instance for singleton services', function () {
    $services = [
        DocumentFlowService::class,
        PaymentService::class,
        InventoryService::class,
        ReportingService::class,
        ApprovalService::class,
        SyncService::class,
    ];

    foreach ($services as $service) {
        $instance1 = app($service);
        $instance2 = app($service);

        expect($instance1)->toBe($instance2, "Service {$service} should return the same instance");
    }
});

it('provides the toolkit facade', function () {
    expect(class_exists(\SapB1\Toolkit\Facades\Toolkit::class))->toBeTrue();
});

it('merges the configuration', function () {
    expect(config('laravel-toolkit'))->toBeArray();
    expect(config('laravel-toolkit.default_connection'))->not->toBeNull();
    expect(config('laravel-toolkit.dispatch_events'))->toBeBool();
    expect(config('laravel-toolkit.cache'))->toBeArray();
});

it('has default configuration values', function () {
    expect(config('laravel-toolkit.default_connection'))->toBe('default');
    expect(config('laravel-toolkit.dispatch_events'))->toBeTrue();
    expect(config('laravel-toolkit.cache.enabled'))->toBeFalse();
    expect(config('laravel-toolkit.cache.ttl'))->toBe(3600);
    expect(config('laravel-toolkit.cache.prefix'))->toBe('sapb1_toolkit_');
});

it('registers artisan commands', function () {
    $commands = Artisan::all();

    expect($commands)->toHaveKey('sapb1:install');
    expect($commands)->toHaveKey('sapb1:test');
    expect($commands)->toHaveKey('sapb1:sync');
    expect($commands)->toHaveKey('sapb1:cache');
    expect($commands)->toHaveKey('sapb1:report');
});
