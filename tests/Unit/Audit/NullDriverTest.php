<?php

declare(strict_types=1);

use SapB1\Toolkit\Audit\AuditEntry;
use SapB1\Toolkit\Audit\Drivers\NullDriver;

describe('NullDriver', function () {
    it('has correct name', function () {
        $driver = new NullDriver;

        expect($driver->getName())->toBe('null');
    });

    it('stores entries and returns true', function () {
        $driver = new NullDriver;
        $entry = AuditEntry::created('Items', 'A001', ['ItemCode' => 'A001']);

        $result = $driver->store($entry);

        expect($result)->toBeTrue();
    });

    it('returns empty array for entity queries', function () {
        $driver = new NullDriver;

        $entries = $driver->getForEntity('Items', 'A001');

        expect($entries)->toBe([]);
    });

    it('returns empty array for user queries', function () {
        $driver = new NullDriver;

        $entries = $driver->getByUser('1', 'App\Models\User');

        expect($entries)->toBe([]);
    });

    it('returns empty array for entity type queries', function () {
        $driver = new NullDriver;

        $entries = $driver->getByEntityType('Items');

        expect($entries)->toBe([]);
    });

    it('returns zero for prune', function () {
        $driver = new NullDriver;

        $pruned = $driver->prune(365);

        expect($pruned)->toBe(0);
    });
});
