<?php

declare(strict_types=1);

use SapB1\Toolkit\ChangeTracking\CacheStateStore;
use SapB1\Toolkit\ChangeTracking\Contracts\StateStore;

it('class exists', function () {
    expect(class_exists(CacheStateStore::class))->toBeTrue();
});

it('implements StateStore interface', function () {
    $store = new CacheStateStore;

    expect($store)->toBeInstanceOf(StateStore::class);
});

// ==================== LAST CHECK TIME ====================

it('returns null for unset last check time', function () {
    $store = new CacheStateStore;

    expect($store->getLastCheckTime('TestEntity'))->toBeNull();
});

it('stores and retrieves last check time', function () {
    $store = new CacheStateStore;
    $timestamp = '2024-01-15 10:30:00';

    $store->setLastCheckTime('TestEntity', $timestamp);

    expect($store->getLastCheckTime('TestEntity'))->toBe($timestamp);
});

// ==================== LAST PRIMARY KEY ====================

it('returns null for unset last primary key', function () {
    $store = new CacheStateStore;

    expect($store->getLastPrimaryKey('TestEntity'))->toBeNull();
});

it('stores and retrieves last primary key (int)', function () {
    $store = new CacheStateStore;

    $store->setLastPrimaryKey('TestEntity', 123);

    expect($store->getLastPrimaryKey('TestEntity'))->toBe(123);
});

it('stores and retrieves last primary key (string)', function () {
    $store = new CacheStateStore;

    $store->setLastPrimaryKey('TestEntity', 'A001');

    expect($store->getLastPrimaryKey('TestEntity'))->toBe('A001');
});

// ==================== KNOWN KEYS ====================

it('returns empty array for unset known keys', function () {
    $store = new CacheStateStore;

    expect($store->getKnownKeys('TestEntity'))->toBe([]);
});

it('stores and retrieves known keys', function () {
    $store = new CacheStateStore;
    $keys = [1, 2, 3, 4, 5];

    $store->setKnownKeys('TestEntity', $keys);

    expect($store->getKnownKeys('TestEntity'))->toBe($keys);
});

it('stores and retrieves string known keys', function () {
    $store = new CacheStateStore;
    $keys = ['A001', 'A002', 'A003'];

    $store->setKnownKeys('TestEntity', $keys);

    expect($store->getKnownKeys('TestEntity'))->toBe($keys);
});

// ==================== CLEAR ====================

it('clears entity state', function () {
    $store = new CacheStateStore;

    $store->setLastCheckTime('TestEntity', '2024-01-15 10:30:00');
    $store->setLastPrimaryKey('TestEntity', 123);
    $store->setKnownKeys('TestEntity', [1, 2, 3]);

    $store->clear('TestEntity');

    expect($store->getLastCheckTime('TestEntity'))->toBeNull();
    expect($store->getLastPrimaryKey('TestEntity'))->toBeNull();
    expect($store->getKnownKeys('TestEntity'))->toBe([]);
});

it('does not clear other entities', function () {
    $store = new CacheStateStore;

    $store->setLastCheckTime('Entity1', '2024-01-15 10:30:00');
    $store->setLastCheckTime('Entity2', '2024-01-15 11:30:00');

    $store->clear('Entity1');

    expect($store->getLastCheckTime('Entity1'))->toBeNull();
    expect($store->getLastCheckTime('Entity2'))->toBe('2024-01-15 11:30:00');
});

// ==================== INTERFACE METHODS ====================

it('has getLastCheckTime method', function () {
    expect(method_exists(CacheStateStore::class, 'getLastCheckTime'))->toBeTrue();
});

it('has setLastCheckTime method', function () {
    expect(method_exists(CacheStateStore::class, 'setLastCheckTime'))->toBeTrue();
});

it('has getLastPrimaryKey method', function () {
    expect(method_exists(CacheStateStore::class, 'getLastPrimaryKey'))->toBeTrue();
});

it('has setLastPrimaryKey method', function () {
    expect(method_exists(CacheStateStore::class, 'setLastPrimaryKey'))->toBeTrue();
});

it('has getKnownKeys method', function () {
    expect(method_exists(CacheStateStore::class, 'getKnownKeys'))->toBeTrue();
});

it('has setKnownKeys method', function () {
    expect(method_exists(CacheStateStore::class, 'setKnownKeys'))->toBeTrue();
});

it('has clear method', function () {
    expect(method_exists(CacheStateStore::class, 'clear'))->toBeTrue();
});

it('has clearAll method', function () {
    expect(method_exists(CacheStateStore::class, 'clearAll'))->toBeTrue();
});
