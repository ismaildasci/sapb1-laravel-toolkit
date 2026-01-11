<?php

declare(strict_types=1);

use SapB1\Toolkit\ChangeTracking\Change;
use SapB1\Toolkit\ChangeTracking\ChangeType;
use SapB1\Toolkit\ChangeTracking\WatcherConfig;

it('class exists', function () {
    expect(class_exists(WatcherConfig::class))->toBeTrue();
});

it('can be created with entity', function () {
    $config = new WatcherConfig('Orders');

    expect($config->entity)->toBe('Orders');
});

it('can be created with static factory', function () {
    $config = WatcherConfig::for('Orders');

    expect($config)->toBeInstanceOf(WatcherConfig::class);
    expect($config->entity)->toBe('Orders');
});

// ==================== DEFAULT VALUES ====================

it('has default primary key', function () {
    $config = WatcherConfig::for('Orders');

    expect($config->primaryKey)->toBe('DocEntry');
});

it('has default detection settings', function () {
    $config = WatcherConfig::for('Orders');

    expect($config->detectCreated)->toBeTrue();
    expect($config->detectUpdated)->toBeTrue();
    expect($config->detectDeleted)->toBeFalse();
});

it('has default batch size', function () {
    $config = WatcherConfig::for('Orders');

    expect($config->batchSize)->toBe(100);
});

// ==================== FLUENT CONFIGURATION ====================

it('sets primary key', function () {
    $config = WatcherConfig::for('Items')->primaryKey('ItemCode');

    expect($config->primaryKey)->toBe('ItemCode');
});

it('sets update date field', function () {
    $config = WatcherConfig::for('Orders')->updateDateField('ModifyDate');

    expect($config->updateDateField)->toBe('ModifyDate');
});

it('sets create date field', function () {
    $config = WatcherConfig::for('Orders')->createDateField('CreationDate');

    expect($config->createDateField)->toBe('CreationDate');
});

it('enables/disables created detection', function () {
    $config = WatcherConfig::for('Orders')->detectCreated(false);

    expect($config->detectCreated)->toBeFalse();
});

it('enables/disables updated detection', function () {
    $config = WatcherConfig::for('Orders')->detectUpdated(false);

    expect($config->detectUpdated)->toBeFalse();
});

it('enables/disables deleted detection', function () {
    $config = WatcherConfig::for('Orders')->detectDeleted(true);

    expect($config->detectDeleted)->toBeTrue();
});

it('sets only created mode', function () {
    $config = WatcherConfig::for('Orders')->onlyCreated();

    expect($config->detectCreated)->toBeTrue();
    expect($config->detectUpdated)->toBeFalse();
    expect($config->detectDeleted)->toBeFalse();
});

it('sets only updated mode', function () {
    $config = WatcherConfig::for('Orders')->onlyUpdated();

    expect($config->detectCreated)->toBeFalse();
    expect($config->detectUpdated)->toBeTrue();
    expect($config->detectDeleted)->toBeFalse();
});

it('sets filter', function () {
    $config = WatcherConfig::for('Orders')->filter("CardCode eq 'C001'");

    expect($config->filter)->toBe("CardCode eq 'C001'");
});

it('sets select fields', function () {
    $config = WatcherConfig::for('Orders')->select(['DocEntry', 'CardCode', 'DocTotal']);

    expect($config->select)->toBe(['DocEntry', 'CardCode', 'DocTotal']);
});

it('sets batch size', function () {
    $config = WatcherConfig::for('Orders')->batchSize(50);

    expect($config->batchSize)->toBe(50);
});

// ==================== CALLBACKS ====================

it('registers created callback', function () {
    $called = false;
    $config = WatcherConfig::for('Orders')->onCreated(function (Change $change) use (&$called) {
        $called = true;
    });

    $callback = $config->getCallback(ChangeType::Created);
    expect($callback)->not->toBeNull();

    $change = Change::created('Orders', 123, []);
    $callback($change);

    expect($called)->toBeTrue();
});

it('registers updated callback', function () {
    $called = false;
    $config = WatcherConfig::for('Orders')->onUpdated(function (Change $change) use (&$called) {
        $called = true;
    });

    $callback = $config->getCallback(ChangeType::Updated);
    expect($callback)->not->toBeNull();

    $change = Change::updated('Orders', 123, []);
    $callback($change);

    expect($called)->toBeTrue();
});

it('registers deleted callback', function () {
    $called = false;
    $config = WatcherConfig::for('Orders')->onDeleted(function (Change $change) use (&$called) {
        $called = true;
    });

    $callback = $config->getCallback(ChangeType::Deleted);
    expect($callback)->not->toBeNull();

    $change = Change::deleted('Orders', 123);
    $callback($change);

    expect($called)->toBeTrue();
});

it('registers any change callback', function () {
    $called = false;
    $config = WatcherConfig::for('Orders')->onChange(function (Change $change) use (&$called) {
        $called = true;
    });

    $callback = $config->getAnyCallback();
    expect($callback)->not->toBeNull();

    $change = Change::created('Orders', 123, []);
    $callback($change);

    expect($called)->toBeTrue();
});

it('returns null for unregistered callback', function () {
    $config = WatcherConfig::for('Orders');

    expect($config->getCallback(ChangeType::Created))->toBeNull();
    expect($config->getCallback(ChangeType::Updated))->toBeNull();
    expect($config->getCallback(ChangeType::Deleted))->toBeNull();
    expect($config->getAnyCallback())->toBeNull();
});

it('checks if has callbacks', function () {
    $config1 = WatcherConfig::for('Orders');
    $config2 = WatcherConfig::for('Orders')->onCreated(fn () => null);

    expect($config1->hasCallbacks())->toBeFalse();
    expect($config2->hasCallbacks())->toBeTrue();
});

// ==================== FLUENT CHAINING ====================

it('supports fluent chaining', function () {
    $config = WatcherConfig::for('Orders')
        ->primaryKey('DocEntry')
        ->detectCreated(true)
        ->detectUpdated(true)
        ->detectDeleted(false)
        ->filter("CardCode eq 'C001'")
        ->batchSize(50)
        ->onCreated(fn () => null);

    expect($config->entity)->toBe('Orders');
    expect($config->primaryKey)->toBe('DocEntry');
    expect($config->filter)->toBe("CardCode eq 'C001'");
    expect($config->batchSize)->toBe(50);
});
