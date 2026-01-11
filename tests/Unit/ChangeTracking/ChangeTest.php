<?php

declare(strict_types=1);

use SapB1\Toolkit\ChangeTracking\Change;
use SapB1\Toolkit\ChangeTracking\ChangeType;

it('class exists', function () {
    expect(class_exists(Change::class))->toBeTrue();
});

// ==================== FACTORY METHODS ====================

it('creates a created change', function () {
    $change = Change::created('Orders', 123, ['CardCode' => 'C001']);

    expect($change->entity)->toBe('Orders');
    expect($change->type)->toBe(ChangeType::Created);
    expect($change->primaryKey)->toBe(123);
    expect($change->data)->toBe(['CardCode' => 'C001']);
    expect($change->previousData)->toBeNull();
    expect($change->detectedAt)->not->toBeNull();
});

it('creates an updated change', function () {
    $change = Change::updated(
        'Orders',
        123,
        ['CardCode' => 'C001', 'DocTotal' => 200],
        ['CardCode' => 'C001', 'DocTotal' => 100]
    );

    expect($change->entity)->toBe('Orders');
    expect($change->type)->toBe(ChangeType::Updated);
    expect($change->primaryKey)->toBe(123);
    expect($change->data['DocTotal'])->toBe(200);
    expect($change->previousData['DocTotal'])->toBe(100);
});

it('creates a deleted change', function () {
    $change = Change::deleted('Orders', 123, ['CardCode' => 'C001']);

    expect($change->entity)->toBe('Orders');
    expect($change->type)->toBe(ChangeType::Deleted);
    expect($change->primaryKey)->toBe(123);
    expect($change->data)->toBe([]);
    expect($change->previousData)->toBe(['CardCode' => 'C001']);
});

// ==================== TYPE CHECKING ====================

it('checks if created', function () {
    $change = Change::created('Orders', 123, []);

    expect($change->isCreated())->toBeTrue();
    expect($change->isUpdated())->toBeFalse();
    expect($change->isDeleted())->toBeFalse();
});

it('checks if updated', function () {
    $change = Change::updated('Orders', 123, []);

    expect($change->isCreated())->toBeFalse();
    expect($change->isUpdated())->toBeTrue();
    expect($change->isDeleted())->toBeFalse();
});

it('checks if deleted', function () {
    $change = Change::deleted('Orders', 123);

    expect($change->isCreated())->toBeFalse();
    expect($change->isUpdated())->toBeFalse();
    expect($change->isDeleted())->toBeTrue();
});

// ==================== DATA ACCESS ====================

it('gets field from data', function () {
    $change = Change::created('Orders', 123, ['CardCode' => 'C001', 'DocTotal' => 100]);

    expect($change->get('CardCode'))->toBe('C001');
    expect($change->get('DocTotal'))->toBe(100);
    expect($change->get('NonExistent'))->toBeNull();
    expect($change->get('NonExistent', 'default'))->toBe('default');
});

// ==================== CHANGE DETECTION ====================

it('detects field change', function () {
    $change = Change::updated(
        'Orders',
        123,
        ['CardCode' => 'C001', 'DocTotal' => 200],
        ['CardCode' => 'C001', 'DocTotal' => 100]
    );

    expect($change->hasFieldChanged('DocTotal'))->toBeTrue();
    expect($change->hasFieldChanged('CardCode'))->toBeFalse();
});

it('returns false for field change without previous data', function () {
    $change = Change::created('Orders', 123, ['CardCode' => 'C001']);

    expect($change->hasFieldChanged('CardCode'))->toBeFalse();
});

it('gets changed fields', function () {
    $change = Change::updated(
        'Orders',
        123,
        ['CardCode' => 'C001', 'DocTotal' => 200, 'Comments' => 'Updated'],
        ['CardCode' => 'C001', 'DocTotal' => 100, 'Comments' => 'Original']
    );

    $changed = $change->getChangedFields();

    expect($changed)->toHaveKey('DocTotal');
    expect($changed)->toHaveKey('Comments');
    expect($changed)->not->toHaveKey('CardCode');

    expect($changed['DocTotal']['old'])->toBe(100);
    expect($changed['DocTotal']['new'])->toBe(200);
});

it('returns empty changed fields without previous data', function () {
    $change = Change::created('Orders', 123, ['CardCode' => 'C001']);

    expect($change->getChangedFields())->toBe([]);
});

// ==================== SERIALIZATION ====================

it('converts to array', function () {
    $change = Change::created('Orders', 123, ['CardCode' => 'C001']);

    $array = $change->toArray();

    expect($array)->toHaveKey('entity');
    expect($array)->toHaveKey('type');
    expect($array)->toHaveKey('primary_key');
    expect($array)->toHaveKey('data');
    expect($array)->toHaveKey('previous_data');
    expect($array)->toHaveKey('detected_at');

    expect($array['entity'])->toBe('Orders');
    expect($array['type'])->toBe('created');
    expect($array['primary_key'])->toBe(123);
});

// ==================== STRING PRIMARY KEY ====================

it('supports string primary key', function () {
    $change = Change::created('Items', 'A001', ['ItemName' => 'Test Item']);

    expect($change->primaryKey)->toBe('A001');
});
