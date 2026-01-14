<?php

declare(strict_types=1);

use SapB1\Toolkit\Audit\Drivers\LogDriver;

beforeEach(function () {
    config([
        'laravel-toolkit.audit.log_channel' => 'stack',
        'laravel-toolkit.audit.log_level' => 'info',
        'laravel-toolkit.multi_tenant.enabled' => false,
    ]);
});

describe('LogDriver', function () {
    it('has correct name', function () {
        $driver = new LogDriver;

        expect($driver->getName())->toBe('log');
    });

    it('is write-only - returns empty for queries', function () {
        $driver = new LogDriver;

        expect($driver->getForEntity('Items', 'A001'))->toBe([]);
        expect($driver->getByUser('1'))->toBe([]);
        expect($driver->getByEntityType('Items'))->toBe([]);
    });

    it('returns zero for prune', function () {
        $driver = new LogDriver;

        expect($driver->prune(365))->toBe(0);
    });
});
