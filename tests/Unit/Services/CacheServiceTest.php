<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Cache;
use SapB1\Toolkit\Services\CacheService;

beforeEach(function () {
    Cache::flush();
});

it('can be instantiated', function () {
    $service = new CacheService;

    expect($service)->toBeInstanceOf(CacheService::class);
});

it('can set custom TTL', function () {
    $service = new CacheService;
    $result = $service->ttl(7200);

    expect($result)->toBeInstanceOf(CacheService::class);
});

it('can set connection', function () {
    $service = new CacheService;
    $result = $service->connection('secondary');

    expect($result)->toBeInstanceOf(CacheService::class);
});

it('can forget specific cache key', function () {
    Cache::put('sapb1_toolkit_items', ['test'], 3600);

    $service = new CacheService;
    $result = $service->forget('items');

    expect($result)->toBeTrue();
    expect(Cache::has('sapb1_toolkit_items'))->toBeFalse();
});

it('can flush all cache', function () {
    Cache::put('sapb1_toolkit_items', ['test'], 3600);
    Cache::put('sapb1_toolkit_warehouses', ['test'], 3600);

    $service = new CacheService;
    $service->flush();

    expect(Cache::has('sapb1_toolkit_items'))->toBeFalse();
    expect(Cache::has('sapb1_toolkit_warehouses'))->toBeFalse();
});

it('generates unique cache keys for different parameters', function () {
    $service = new CacheService;

    // Use reflection to test protected method
    $reflection = new ReflectionClass($service);
    $method = $reflection->getMethod('key');
    $method->setAccessible(true);

    $key1 = $method->invoke($service, 'items', ['ItemCode']);
    $key2 = $method->invoke($service, 'items', ['ItemCode', 'ItemName']);
    $key3 = $method->invoke($service, 'items', ['ItemCode']);

    expect($key1)->not->toBe($key2);
    expect($key1)->toBe($key3);
});

it('includes connection in cache key', function () {
    $service1 = (new CacheService)->connection('default');
    $service2 = (new CacheService)->connection('secondary');

    $reflection = new ReflectionClass($service1);
    $method = $reflection->getMethod('key');
    $method->setAccessible(true);

    $key1 = $method->invoke($service1, 'items');
    $key2 = $method->invoke($service2, 'items');

    expect($key1)->not->toBe($key2);
    expect($key1)->toContain('default');
    expect($key2)->toContain('secondary');
});
