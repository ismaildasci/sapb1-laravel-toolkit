<?php

declare(strict_types=1);

use SapB1\Toolkit\Cache\CacheResolver;

it('class exists', function () {
    expect(class_exists(CacheResolver::class))->toBeTrue();
});

it('can be instantiated with entity', function () {
    $resolver = new CacheResolver('Items');

    expect($resolver)->toBeInstanceOf(CacheResolver::class);
});

it('can be instantiated without entity', function () {
    $resolver = new CacheResolver();

    expect($resolver)->toBeInstanceOf(CacheResolver::class);
});

// ==================== PRIORITY TESTS ====================

it('returns false by default when global cache is disabled', function () {
    // Global config default is false
    config(['laravel-toolkit.cache.enabled' => false]);

    $resolver = new CacheResolver('Items');

    expect($resolver->shouldCache())->toBeFalse();
});

it('returns true when global cache is enabled', function () {
    config(['laravel-toolkit.cache.enabled' => true]);

    $resolver = new CacheResolver('Items');

    expect($resolver->shouldCache())->toBeTrue();
});

it('query-level cache() overrides global disabled', function () {
    config(['laravel-toolkit.cache.enabled' => false]);

    $resolver = new CacheResolver('Items');
    $resolver->enableForQuery();

    expect($resolver->shouldCache())->toBeTrue();
});

it('query-level noCache() overrides global enabled', function () {
    config(['laravel-toolkit.cache.enabled' => true]);

    $resolver = new CacheResolver('Items');
    $resolver->disableForQuery();

    expect($resolver->shouldCache())->toBeFalse();
});

it('model-level enabled overrides global disabled', function () {
    config(['laravel-toolkit.cache.enabled' => false]);

    $resolver = new CacheResolver('Items');
    $resolver->setModelEnabled(true);

    expect($resolver->shouldCache())->toBeTrue();
});

it('model-level disabled overrides global enabled', function () {
    config(['laravel-toolkit.cache.enabled' => true]);

    $resolver = new CacheResolver('Items');
    $resolver->setModelEnabled(false);

    expect($resolver->shouldCache())->toBeFalse();
});

it('entity config enabled overrides global disabled', function () {
    config(['laravel-toolkit.cache.enabled' => false]);
    config(['laravel-toolkit.cache.entities.Items.enabled' => true]);

    $resolver = new CacheResolver('Items');

    expect($resolver->shouldCache())->toBeTrue();
});

it('entity config disabled overrides global enabled', function () {
    config(['laravel-toolkit.cache.enabled' => true]);
    config(['laravel-toolkit.cache.entities.Orders.enabled' => false]);

    $resolver = new CacheResolver('Orders');

    expect($resolver->shouldCache())->toBeFalse();
});

it('query-level overrides model-level', function () {
    $resolver = new CacheResolver('Items');
    $resolver->setModelEnabled(true);
    $resolver->disableForQuery();

    expect($resolver->shouldCache())->toBeFalse();
});

it('model-level overrides entity config', function () {
    config(['laravel-toolkit.cache.entities.Items.enabled' => true]);

    $resolver = new CacheResolver('Items');
    $resolver->setModelEnabled(false);

    expect($resolver->shouldCache())->toBeFalse();
});

// ==================== TTL TESTS ====================

it('returns global TTL by default', function () {
    config(['laravel-toolkit.cache.ttl' => 3600]);

    $resolver = new CacheResolver('Items');

    expect($resolver->getTtl())->toBe(3600);
});

it('returns entity TTL when configured', function () {
    config(['laravel-toolkit.cache.ttl' => 3600]);
    config(['laravel-toolkit.cache.entities.Items.ttl' => 7200]);

    $resolver = new CacheResolver('Items');

    expect($resolver->getTtl())->toBe(7200);
});

it('returns query TTL when set', function () {
    config(['laravel-toolkit.cache.ttl' => 3600]);
    config(['laravel-toolkit.cache.entities.Items.ttl' => 7200]);

    $resolver = new CacheResolver('Items');
    $resolver->enableForQuery(600);

    expect($resolver->getTtl())->toBe(600);
});

// ==================== PREFIX & TAGS TESTS ====================

it('returns configured prefix', function () {
    config(['laravel-toolkit.cache.prefix' => 'sapb1_toolkit_']);

    $resolver = new CacheResolver('Items');

    expect($resolver->getPrefix())->toBe('sapb1_toolkit_');
});

it('returns tags including entity', function () {
    config(['laravel-toolkit.cache.prefix' => 'sapb1_']);

    $resolver = new CacheResolver('Items');
    $tags = $resolver->getTags();

    expect($tags)->toBe(['sapb1_all', 'sapb1_Items']);
});

it('returns tags without entity when empty', function () {
    config(['laravel-toolkit.cache.prefix' => 'sapb1_']);

    $resolver = new CacheResolver();
    $tags = $resolver->getTags();

    expect($tags)->toBe(['sapb1_all']);
});

// ==================== UTILITY TESTS ====================

it('can set entity', function () {
    $resolver = new CacheResolver();
    $resolver->setEntity('BusinessPartners');

    $state = $resolver->getState();

    expect($state['entity'])->toBe('BusinessPartners');
});

it('can reset query overrides', function () {
    $resolver = new CacheResolver('Items');
    $resolver->enableForQuery(600);
    $resolver->reset();

    $state = $resolver->getState();

    expect($state['query_override'])->toBeNull();
    expect($state['query_ttl'])->toBeNull();
});

it('returns state for debugging', function () {
    $resolver = new CacheResolver('Items');
    $resolver->enableForQuery(600);

    $state = $resolver->getState();

    expect($state)->toHaveKeys([
        'entity',
        'query_override',
        'query_ttl',
        'model_enabled',
        'should_cache',
        'ttl',
        'tags',
    ]);
    expect($state['entity'])->toBe('Items');
    expect($state['query_override'])->toBeTrue();
    expect($state['query_ttl'])->toBe(600);
});

it('checks if entity config exists', function () {
    config(['laravel-toolkit.cache.entities.Items.enabled' => true]);

    $resolver = new CacheResolver();

    expect($resolver->hasEntityConfig('Items'))->toBeTrue();
    expect($resolver->hasEntityConfig('NonExistent'))->toBeFalse();
});

it('returns configured store', function () {
    config(['laravel-toolkit.cache.store' => 'redis']);

    $resolver = new CacheResolver('Items');

    expect($resolver->getStore())->toBe('redis');
});

it('returns null store when not configured', function () {
    config(['laravel-toolkit.cache.store' => null]);

    $resolver = new CacheResolver('Items');

    expect($resolver->getStore())->toBeNull();
});
