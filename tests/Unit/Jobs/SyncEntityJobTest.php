<?php

declare(strict_types=1);

use Illuminate\Contracts\Queue\ShouldQueue;
use SapB1\Toolkit\Jobs\SyncEntityJob;

describe('SyncEntityJob', function () {
    it('implements ShouldQueue', function () {
        $job = new SyncEntityJob('Items');

        expect($job)->toBeInstanceOf(ShouldQueue::class);
    });

    it('creates job with entity name', function () {
        $job = new SyncEntityJob('Items');

        expect($job->entity)->toBe('Items');
        expect($job->fullSync)->toBeFalse();
        expect($job->since)->toBeNull();
        expect($job->sapConnection)->toBeNull();
    });

    it('creates job with full sync option', function () {
        $job = new SyncEntityJob('Items', fullSync: true);

        expect($job->fullSync)->toBeTrue();
    });

    it('creates job with since date', function () {
        $job = new SyncEntityJob('Orders', since: '2026-01-01');

        expect($job->since)->toBe('2026-01-01');
    });

    it('creates job with custom SAP connection', function () {
        $job = new SyncEntityJob('Items', sapConnection: 'secondary');

        expect($job->sapConnection)->toBe('secondary');
    });

    it('has default retry configuration', function () {
        $job = new SyncEntityJob('Items');

        expect($job->tries)->toBe(3);
        expect($job->backoff)->toBe(60);
        expect($job->maxExceptions)->toBe(2);
    });

    it('generates appropriate tags', function () {
        $incrementalJob = new SyncEntityJob('Items');
        $fullSyncJob = new SyncEntityJob('Items', fullSync: true);

        expect($incrementalJob->tags())->toContain('sync');
        expect($incrementalJob->tags())->toContain('entity:Items');
        expect($incrementalJob->tags())->toContain('incremental-sync');

        expect($fullSyncJob->tags())->toContain('full-sync');
    });

    it('sets retry until time', function () {
        $job = new SyncEntityJob('Items');
        $retryUntil = $job->retryUntil();

        expect($retryUntil)->toBeInstanceOf(DateTime::class);
        expect($retryUntil->getTimestamp())->toBeGreaterThan(time());
    });
});
