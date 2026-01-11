<?php

declare(strict_types=1);

use SapB1\Toolkit\Cache\CacheManager;
use SapB1\Toolkit\Cache\CacheResolver;

it('class exists', function () {
    expect(class_exists(CacheManager::class))->toBeTrue();
});

it('can be instantiated with resolver', function () {
    $resolver = new CacheResolver('Items');
    $manager = new CacheManager($resolver);

    expect($manager)->toBeInstanceOf(CacheManager::class);
});

it('can be created for entity using static method', function () {
    $manager = CacheManager::for('Items');

    expect($manager)->toBeInstanceOf(CacheManager::class);
});

it('returns resolver', function () {
    $resolver = new CacheResolver('Items');
    $manager = new CacheManager($resolver);

    expect($manager->getResolver())->toBe($resolver);
});

// ==================== KEY GENERATION TESTS ====================

it('generates unique query key', function () {
    $params1 = ['filter' => 'CardCode eq "C001"'];
    $params2 = ['filter' => 'CardCode eq "C002"'];

    $key1 = CacheManager::generateQueryKey('Items', $params1);
    $key2 = CacheManager::generateQueryKey('Items', $params2);

    expect($key1)->toStartWith('query:');
    expect($key2)->toStartWith('query:');
    expect($key1)->not->toBe($key2);
});

it('generates same key for same parameters', function () {
    $params = ['filter' => 'CardCode eq "C001"', 'top' => 10];

    $key1 = CacheManager::generateQueryKey('Items', $params);
    $key2 = CacheManager::generateQueryKey('Items', $params);

    expect($key1)->toBe($key2);
});

it('generates different keys for different entities', function () {
    $params = ['filter' => 'Code eq "001"'];

    $key1 = CacheManager::generateQueryKey('Items', $params);
    $key2 = CacheManager::generateQueryKey('BusinessPartners', $params);

    expect($key1)->not->toBe($key2);
});

it('generates record key with entity prefix', function () {
    $key = CacheManager::generateRecordKey('Items', 123);

    expect($key)->toBe('Items:record:123');
});

it('generates record key for string id', function () {
    $key = CacheManager::generateRecordKey('Items', 'A001');

    expect($key)->toBe('Items:record:A001');
});

// ==================== CACHE OPERATION TESTS (with cache disabled) ====================

it('remember returns callback result when cache disabled', function () {
    config(['laravel-toolkit.cache.enabled' => false]);

    $manager = CacheManager::for('Items');
    $result = $manager->remember('test-key', fn () => 'test-value');

    expect($result)->toBe('test-value');
});

it('get returns default when cache disabled', function () {
    config(['laravel-toolkit.cache.enabled' => false]);

    $manager = CacheManager::for('Items');
    $result = $manager->get('test-key', 'default');

    expect($result)->toBe('default');
});

it('put returns false when cache disabled', function () {
    config(['laravel-toolkit.cache.enabled' => false]);

    $manager = CacheManager::for('Items');
    $result = $manager->put('test-key', 'test-value');

    expect($result)->toBeFalse();
});

it('has returns false when cache disabled', function () {
    config(['laravel-toolkit.cache.enabled' => false]);

    $manager = CacheManager::for('Items');
    $result = $manager->has('test-key');

    expect($result)->toBeFalse();
});

// ==================== STATIC METHOD TESTS ====================

it('has invalidateEntity static method', function () {
    expect(method_exists(CacheManager::class, 'invalidateEntity'))->toBeTrue();
});

it('has flushAll static method', function () {
    expect(method_exists(CacheManager::class, 'flushAll'))->toBeTrue();
});

// ==================== INSTANCE METHOD TESTS ====================

it('has remember method', function () {
    expect(method_exists(CacheManager::class, 'remember'))->toBeTrue();
});

it('has get method', function () {
    expect(method_exists(CacheManager::class, 'get'))->toBeTrue();
});

it('has put method', function () {
    expect(method_exists(CacheManager::class, 'put'))->toBeTrue();
});

it('has has method', function () {
    expect(method_exists(CacheManager::class, 'has'))->toBeTrue();
});

it('has forget method', function () {
    expect(method_exists(CacheManager::class, 'forget'))->toBeTrue();
});

it('has flush method', function () {
    expect(method_exists(CacheManager::class, 'flush'))->toBeTrue();
});
