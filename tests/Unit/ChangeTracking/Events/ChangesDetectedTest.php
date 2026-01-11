<?php

declare(strict_types=1);

use Illuminate\Support\Collection;
use SapB1\Toolkit\ChangeTracking\Change;
use SapB1\Toolkit\ChangeTracking\Events\ChangesDetected;

it('class exists', function () {
    expect(class_exists(ChangesDetected::class))->toBeTrue();
});

it('can be instantiated', function () {
    $changes = new Collection;
    $event = new ChangesDetected('Orders', $changes);

    expect($event)->toBeInstanceOf(ChangesDetected::class);
});

it('stores entity name', function () {
    $event = new ChangesDetected('Orders', new Collection);

    expect($event->entity)->toBe('Orders');
});

it('stores changes collection', function () {
    $changes = new Collection([
        Change::created('Orders', 1, []),
        Change::updated('Orders', 2, []),
    ]);

    $event = new ChangesDetected('Orders', $changes);

    expect($event->changes->count())->toBe(2);
});

it('counts changes', function () {
    $changes = new Collection([
        Change::created('Orders', 1, []),
        Change::created('Orders', 2, []),
        Change::created('Orders', 3, []),
    ]);

    $event = new ChangesDetected('Orders', $changes);

    expect($event->count())->toBe(3);
});

it('filters created changes', function () {
    $changes = new Collection([
        Change::created('Orders', 1, []),
        Change::updated('Orders', 2, []),
        Change::created('Orders', 3, []),
    ]);

    $event = new ChangesDetected('Orders', $changes);
    $created = $event->created();

    expect($created->count())->toBe(2);
});

it('filters updated changes', function () {
    $changes = new Collection([
        Change::created('Orders', 1, []),
        Change::updated('Orders', 2, []),
        Change::updated('Orders', 3, []),
    ]);

    $event = new ChangesDetected('Orders', $changes);
    $updated = $event->updated();

    expect($updated->count())->toBe(2);
});

it('filters deleted changes', function () {
    $changes = new Collection([
        Change::created('Orders', 1, []),
        Change::deleted('Orders', 2),
        Change::deleted('Orders', 3),
    ]);

    $event = new ChangesDetected('Orders', $changes);
    $deleted = $event->deleted();

    expect($deleted->count())->toBe(2);
});
