<?php

declare(strict_types=1);

use SapB1\Toolkit\Sync\Events\SyncCompleted;
use SapB1\Toolkit\Sync\Events\SyncFailed;
use SapB1\Toolkit\Sync\Events\SyncProgress;
use SapB1\Toolkit\Sync\Events\SyncStarted;
use SapB1\Toolkit\Sync\SyncResult;

describe('SyncStarted Event', function () {
    it('creates event with entity and sync type', function () {
        $event = new SyncStarted('Items', 'incremental');

        expect($event->entity)->toBe('Items');
        expect($event->syncType)->toBe('incremental');
        expect($event->since)->toBeNull();
    });

    it('creates event with since date', function () {
        $event = new SyncStarted('Orders', 'incremental', '2026-01-01');

        expect($event->since)->toBe('2026-01-01');
    });

    it('detects full sync', function () {
        $event = new SyncStarted('Items', 'full');

        expect($event->isFullSync())->toBeTrue();
        expect($event->isIncrementalSync())->toBeFalse();
    });

    it('detects incremental sync', function () {
        $event = new SyncStarted('Items', 'incremental');

        expect($event->isFullSync())->toBeFalse();
        expect($event->isIncrementalSync())->toBeTrue();
    });
});

describe('SyncCompleted Event', function () {
    it('creates event with result', function () {
        $result = new SyncResult('Items', created: 10, updated: 20, deleted: 5, duration: 1.5);
        $event = new SyncCompleted('Items', $result);

        expect($event->entity)->toBe('Items');
        expect($event->result)->toBe($result);
    });

    it('exposes result data via methods', function () {
        $result = new SyncResult('Items', created: 10, updated: 20, deleted: 5, duration: 1.5);
        $event = new SyncCompleted('Items', $result);

        expect($event->created())->toBe(10);
        expect($event->updated())->toBe(20);
        expect($event->deleted())->toBe(5);
        expect($event->total())->toBe(35);
        expect($event->duration())->toBe(1.5);
    });
});

describe('SyncFailed Event', function () {
    it('creates event with error message', function () {
        $event = new SyncFailed('Items', 'Connection failed');

        expect($event->entity)->toBe('Items');
        expect($event->error)->toBe('Connection failed');
        expect($event->message())->toBe('Connection failed');
    });

    it('includes duration if provided', function () {
        $event = new SyncFailed('Items', 'Error', 2.5);

        expect($event->duration)->toBe(2.5);
    });

    it('includes exception if provided', function () {
        $exception = new RuntimeException('Test error');
        $event = new SyncFailed('Items', 'Error', 1.0, $exception);

        expect($event->hasException())->toBeTrue();
        expect($event->exception)->toBe($exception);
        expect($event->exceptionClass())->toBe(RuntimeException::class);
    });

    it('handles no exception', function () {
        $event = new SyncFailed('Items', 'Error');

        expect($event->hasException())->toBeFalse();
        expect($event->exceptionClass())->toBeNull();
    });
});

describe('SyncProgress Event', function () {
    it('creates event with progress data', function () {
        $event = new SyncProgress('Items', 500, 1000, 300, 200);

        expect($event->entity)->toBe('Items');
        expect($event->processed)->toBe(500);
        expect($event->total)->toBe(1000);
        expect($event->created)->toBe(300);
        expect($event->updated)->toBe(200);
    });

    it('calculates percentage correctly', function () {
        $event = new SyncProgress('Items', 500, 1000, 300, 200);

        expect($event->percentage())->toBe(50.0);
    });

    it('handles zero total', function () {
        $event = new SyncProgress('Items', 0, 0, 0, 0);

        expect($event->percentage())->toBe(100.0);
    });

    it('detects completion', function () {
        $incomplete = new SyncProgress('Items', 500, 1000, 300, 200);
        $complete = new SyncProgress('Items', 1000, 1000, 600, 400);

        expect($incomplete->isComplete())->toBeFalse();
        expect($complete->isComplete())->toBeTrue();
    });

    it('calculates remaining records', function () {
        $event = new SyncProgress('Items', 700, 1000, 400, 300);

        expect($event->remaining())->toBe(300);
    });
});
