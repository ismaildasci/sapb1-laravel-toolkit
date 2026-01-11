<?php

declare(strict_types=1);

use SapB1\Toolkit\ChangeTracking\Change;
use SapB1\Toolkit\ChangeTracking\ChangeType;
use SapB1\Toolkit\ChangeTracking\Events\EntityChangeDetected;

it('class exists', function () {
    expect(class_exists(EntityChangeDetected::class))->toBeTrue();
});

it('can be instantiated', function () {
    $change = Change::created('Orders', 123, []);
    $event = new EntityChangeDetected($change);

    expect($event)->toBeInstanceOf(EntityChangeDetected::class);
});

it('stores the change', function () {
    $change = Change::created('Orders', 123, ['CardCode' => 'C001']);
    $event = new EntityChangeDetected($change);

    expect($event->change)->toBe($change);
});

it('returns entity name', function () {
    $change = Change::created('Orders', 123, []);
    $event = new EntityChangeDetected($change);

    expect($event->entity())->toBe('Orders');
});

it('returns change type', function () {
    $change = Change::updated('Orders', 123, []);
    $event = new EntityChangeDetected($change);

    expect($event->type())->toBe(ChangeType::Updated);
});

it('returns primary key', function () {
    $change = Change::created('Orders', 123, []);
    $event = new EntityChangeDetected($change);

    expect($event->primaryKey())->toBe(123);
});

it('returns data', function () {
    $data = ['CardCode' => 'C001', 'DocTotal' => 100];
    $change = Change::created('Orders', 123, $data);
    $event = new EntityChangeDetected($change);

    expect($event->data())->toBe($data);
});

it('checks if created', function () {
    $change = Change::created('Orders', 123, []);
    $event = new EntityChangeDetected($change);

    expect($event->isCreated())->toBeTrue();
    expect($event->isUpdated())->toBeFalse();
    expect($event->isDeleted())->toBeFalse();
});

it('checks if updated', function () {
    $change = Change::updated('Orders', 123, []);
    $event = new EntityChangeDetected($change);

    expect($event->isCreated())->toBeFalse();
    expect($event->isUpdated())->toBeTrue();
    expect($event->isDeleted())->toBeFalse();
});

it('checks if deleted', function () {
    $change = Change::deleted('Orders', 123);
    $event = new EntityChangeDetected($change);

    expect($event->isCreated())->toBeFalse();
    expect($event->isUpdated())->toBeFalse();
    expect($event->isDeleted())->toBeTrue();
});

it('supports string primary key', function () {
    $change = Change::created('Items', 'A001', []);
    $event = new EntityChangeDetected($change);

    expect($event->primaryKey())->toBe('A001');
});
