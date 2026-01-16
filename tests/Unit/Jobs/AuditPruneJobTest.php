<?php

declare(strict_types=1);

use SapB1\Toolkit\Jobs\AuditPruneJob;

it('can be instantiated with default days', function () {
    $job = new AuditPruneJob;

    expect($job)->toBeInstanceOf(AuditPruneJob::class);
    expect($job->days)->toBeNull();
});

it('can be instantiated with custom days', function () {
    $job = new AuditPruneJob(days: 30);

    expect($job->days)->toBe(30);
});

it('has correct tries setting', function () {
    $job = new AuditPruneJob;

    expect($job->tries)->toBe(3);
});

it('has correct backoff setting', function () {
    $job = new AuditPruneJob;

    expect($job->backoff)->toBe(60);
});

it('has tags method', function () {
    $job = new AuditPruneJob;

    expect($job->tags())->toBe(['audit', 'prune']);
});

it('is queueable', function () {
    expect(in_array(\Illuminate\Contracts\Queue\ShouldQueue::class, class_implements(AuditPruneJob::class)))->toBeTrue();
});

it('is dispatchable', function () {
    expect(method_exists(AuditPruneJob::class, 'dispatch'))->toBeTrue();
});
