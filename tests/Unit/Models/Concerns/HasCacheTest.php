<?php

declare(strict_types=1);

use SapB1\Toolkit\Cache\CacheManager;
use SapB1\Toolkit\Cache\CacheResolver;
use SapB1\Toolkit\Models\Concerns\HasCache;

it('trait exists', function () {
    expect(trait_exists(HasCache::class))->toBeTrue();
});

it('has isCacheEnabled method', function () {
    $methods = get_class_methods(HasCache::class);

    expect(in_array('isCacheEnabled', $methods))->toBeTrue();
});

it('has getCacheTtl method', function () {
    $methods = get_class_methods(HasCache::class);

    expect(in_array('getCacheTtl', $methods))->toBeTrue();
});

it('has getCacheResolver method', function () {
    $methods = get_class_methods(HasCache::class);

    expect(in_array('getCacheResolver', $methods))->toBeTrue();
});

it('has getCacheManager method', function () {
    $methods = get_class_methods(HasCache::class);

    expect(in_array('getCacheManager', $methods))->toBeTrue();
});

it('has flushCache method', function () {
    $methods = get_class_methods(HasCache::class);

    expect(in_array('flushCache', $methods))->toBeTrue();
});

it('has forgetCached method', function () {
    $methods = get_class_methods(HasCache::class);

    expect(in_array('forgetCached', $methods))->toBeTrue();
});

// ==================== CONCRETE IMPLEMENTATION TESTS ====================

// Model without cache properties
class TestModelWithoutCache
{
    use HasCache;

    protected static string $entity = 'TestEntities';

    public static function getEntity(): string
    {
        return static::$entity;
    }
}

// Model with cache enabled
class TestModelWithCacheEnabled
{
    use HasCache;

    protected static string $entity = 'CachedEntities';

    protected static bool $cacheEnabled = true;

    protected static int $cacheTtl = 7200;

    public static function getEntity(): string
    {
        return static::$entity;
    }
}

// Model with cache disabled
class TestModelWithCacheDisabled
{
    use HasCache;

    protected static string $entity = 'NonCachedEntities';

    protected static bool $cacheEnabled = false;

    public static function getEntity(): string
    {
        return static::$entity;
    }
}

it('isCacheEnabled returns null when property not defined', function () {
    expect(TestModelWithoutCache::isCacheEnabled())->toBeNull();
});

it('isCacheEnabled returns true when enabled', function () {
    expect(TestModelWithCacheEnabled::isCacheEnabled())->toBeTrue();
});

it('isCacheEnabled returns false when disabled', function () {
    expect(TestModelWithCacheDisabled::isCacheEnabled())->toBeFalse();
});

it('getCacheTtl returns null when property not defined', function () {
    expect(TestModelWithoutCache::getCacheTtl())->toBeNull();
});

it('getCacheTtl returns custom TTL when defined', function () {
    expect(TestModelWithCacheEnabled::getCacheTtl())->toBe(7200);
});

it('getCacheResolver returns CacheResolver instance', function () {
    $resolver = TestModelWithCacheEnabled::getCacheResolver();

    expect($resolver)->toBeInstanceOf(CacheResolver::class);
});

it('getCacheResolver uses model entity', function () {
    $resolver = TestModelWithCacheEnabled::getCacheResolver();
    $state = $resolver->getState();

    expect($state['entity'])->toBe('CachedEntities');
});

it('getCacheResolver includes model cache setting', function () {
    $resolver = TestModelWithCacheEnabled::getCacheResolver();
    $state = $resolver->getState();

    expect($state['model_enabled'])->toBeTrue();
});

it('getCacheManager returns CacheManager instance', function () {
    $manager = TestModelWithCacheEnabled::getCacheManager();

    expect($manager)->toBeInstanceOf(CacheManager::class);
});

it('getCacheManager resolver has model settings', function () {
    $manager = TestModelWithCacheEnabled::getCacheManager();
    $resolver = $manager->getResolver();
    $state = $resolver->getState();

    expect($state['entity'])->toBe('CachedEntities');
    expect($state['model_enabled'])->toBeTrue();
});
