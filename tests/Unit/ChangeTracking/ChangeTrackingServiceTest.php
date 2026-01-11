<?php

declare(strict_types=1);

use SapB1\Toolkit\ChangeTracking\CacheStateStore;
use SapB1\Toolkit\ChangeTracking\ChangeTrackingService;
use SapB1\Toolkit\ChangeTracking\WatcherConfig;

it('class exists', function () {
    expect(class_exists(ChangeTrackingService::class))->toBeTrue();
});

it('can be instantiated', function () {
    $service = new ChangeTrackingService;

    expect($service)->toBeInstanceOf(ChangeTrackingService::class);
});

it('can be instantiated with state store', function () {
    $store = new CacheStateStore;
    $service = new ChangeTrackingService($store);

    expect($service)->toBeInstanceOf(ChangeTrackingService::class);
});

// ==================== WATCHER REGISTRATION ====================

it('watches an entity', function () {
    $service = new ChangeTrackingService;
    $config = $service->watch('Orders');

    expect($config)->toBeInstanceOf(WatcherConfig::class);
    expect($config->entity)->toBe('Orders');
});

it('registers a watcher config', function () {
    $service = new ChangeTrackingService;
    $config = WatcherConfig::for('Orders');

    $service->register($config);

    expect($service->isWatching('Orders'))->toBeTrue();
});

it('unwatches an entity', function () {
    $service = new ChangeTrackingService;
    $service->watch('Orders');

    expect($service->isWatching('Orders'))->toBeTrue();

    $service->unwatch('Orders');

    expect($service->isWatching('Orders'))->toBeFalse();
});

it('checks if entity is being watched', function () {
    $service = new ChangeTrackingService;

    expect($service->isWatching('Orders'))->toBeFalse();

    $service->watch('Orders');

    expect($service->isWatching('Orders'))->toBeTrue();
});

it('gets all watchers', function () {
    $service = new ChangeTrackingService;
    $service->watch('Orders');
    $service->watch('Invoices');

    $watchers = $service->getWatchers();

    expect($watchers)->toHaveKey('Orders');
    expect($watchers)->toHaveKey('Invoices');
    expect(count($watchers))->toBe(2);
});

// ==================== SHORTCUTS ====================

it('has watchOrders shortcut', function () {
    $service = new ChangeTrackingService;
    $config = $service->watchOrders();

    expect($config->entity)->toBe('Orders');
    expect($config->primaryKey)->toBe('DocEntry');
});

it('has watchInvoices shortcut', function () {
    $service = new ChangeTrackingService;
    $config = $service->watchInvoices();

    expect($config->entity)->toBe('Invoices');
    expect($config->primaryKey)->toBe('DocEntry');
});

it('has watchItems shortcut', function () {
    $service = new ChangeTrackingService;
    $config = $service->watchItems();

    expect($config->entity)->toBe('Items');
    expect($config->primaryKey)->toBe('ItemCode');
});

it('has watchPartners shortcut', function () {
    $service = new ChangeTrackingService;
    $config = $service->watchPartners();

    expect($config->entity)->toBe('BusinessPartners');
    expect($config->primaryKey)->toBe('CardCode');
});

it('has watchPurchaseOrders shortcut', function () {
    $service = new ChangeTrackingService;
    $config = $service->watchPurchaseOrders();

    expect($config->entity)->toBe('PurchaseOrders');
    expect($config->primaryKey)->toBe('DocEntry');
});

it('has watchDeliveries shortcut', function () {
    $service = new ChangeTrackingService;
    $config = $service->watchDeliveries();

    expect($config->entity)->toBe('DeliveryNotes');
    expect($config->primaryKey)->toBe('DocEntry');
});

// ==================== CONFIGURATION ====================

it('sets connection', function () {
    $service = new ChangeTrackingService;
    $result = $service->connection('custom');

    expect($result)->toBeInstanceOf(ChangeTrackingService::class);
});

it('sets state store', function () {
    $service = new ChangeTrackingService;
    $store = new CacheStateStore;
    $result = $service->stateStore($store);

    expect($result)->toBeInstanceOf(ChangeTrackingService::class);
});

it('sets dispatch events flag', function () {
    $service = new ChangeTrackingService;
    $result = $service->dispatchEvents(false);

    expect($result)->toBeInstanceOf(ChangeTrackingService::class);
});

// ==================== RESET ====================

it('resets single entity', function () {
    $service = new ChangeTrackingService;
    $result = $service->reset('Orders');

    expect($result)->toBeInstanceOf(ChangeTrackingService::class);
});

it('resets all entities', function () {
    $service = new ChangeTrackingService;
    $service->watch('Orders');
    $service->watch('Invoices');

    $result = $service->resetAll();

    expect($result)->toBeInstanceOf(ChangeTrackingService::class);
});

// ==================== POLLING METHODS ====================

it('has pollEntity method', function () {
    expect(method_exists(ChangeTrackingService::class, 'pollEntity'))->toBeTrue();
});

it('has pollAll method', function () {
    expect(method_exists(ChangeTrackingService::class, 'pollAll'))->toBeTrue();
});

it('has poll method', function () {
    expect(method_exists(ChangeTrackingService::class, 'poll'))->toBeTrue();
});

it('returns empty collection for unregistered entity', function () {
    $service = new ChangeTrackingService;
    $changes = $service->pollEntity('NonExistent');

    expect($changes->isEmpty())->toBeTrue();
});
