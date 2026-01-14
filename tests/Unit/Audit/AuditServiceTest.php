<?php

declare(strict_types=1);

use SapB1\Toolkit\Audit\AuditEntry;
use SapB1\Toolkit\Audit\AuditLogger;
use SapB1\Toolkit\Audit\AuditService;
use SapB1\Toolkit\Audit\Contracts\AuditDriverInterface;
use SapB1\Toolkit\Audit\Drivers\NullDriver;

beforeEach(function () {
    config([
        'laravel-toolkit.audit.enabled' => true,
        'laravel-toolkit.audit.entities' => [],
        'laravel-toolkit.multi_tenant.enabled' => false,
    ]);
});

describe('AuditService', function () {
    it('checks if auditing is enabled', function () {
        config(['laravel-toolkit.audit.enabled' => true]);
        $service = new AuditService(new AuditLogger(new NullDriver));
        expect($service->isEnabled())->toBeTrue();

        config(['laravel-toolkit.audit.enabled' => false]);
        $service = new AuditService(new AuditLogger(new NullDriver));
        expect($service->isEnabled())->toBeFalse();
    });

    it('logs entries when enabled', function () {
        $driver = Mockery::mock(AuditDriverInterface::class);
        $driver->shouldReceive('store')->once()->andReturn(true);
        $driver->shouldReceive('getName')->andReturn('mock');

        $logger = new AuditLogger($driver);
        $service = new AuditService($logger);

        $result = $service->log('Items', 'A001', 'created', null, ['ItemCode' => 'A001']);

        expect($result)->toBeTrue();
    });

    it('does not log when disabled', function () {
        config(['laravel-toolkit.audit.enabled' => false]);

        $driver = Mockery::mock(AuditDriverInterface::class);
        $driver->shouldNotReceive('store');

        $logger = new AuditLogger($driver);
        $service = new AuditService($logger);

        $result = $service->log('Items', 'A001', 'created');

        expect($result)->toBeFalse();
    });

    it('respects entity-specific enabled setting', function () {
        config(['laravel-toolkit.audit.entities.Items.enabled' => false]);

        $driver = Mockery::mock(AuditDriverInterface::class);
        $driver->shouldNotReceive('store');

        $logger = new AuditLogger($driver);
        $service = new AuditService($logger);

        $result = $service->log('Items', 'A001', 'created');

        expect($result)->toBeFalse();
    });

    it('logs created events', function () {
        $driver = Mockery::mock(AuditDriverInterface::class);
        $driver->shouldReceive('store')->once()->andReturn(true);
        $driver->shouldReceive('getName')->andReturn('mock');

        $logger = new AuditLogger($driver);
        $service = new AuditService($logger);

        $result = $service->logCreated('Orders', 123, ['DocNum' => 100]);

        expect($result)->toBeTrue();
    });

    it('logs updated events', function () {
        $driver = Mockery::mock(AuditDriverInterface::class);
        $driver->shouldReceive('store')->once()->andReturn(true);
        $driver->shouldReceive('getName')->andReturn('mock');

        $logger = new AuditLogger($driver);
        $service = new AuditService($logger);

        $result = $service->logUpdated('Orders', 123, ['Status' => 'O'], ['Status' => 'C']);

        expect($result)->toBeTrue();
    });

    it('logs deleted events', function () {
        $driver = Mockery::mock(AuditDriverInterface::class);
        $driver->shouldReceive('store')->once()->andReturn(true);
        $driver->shouldReceive('getName')->andReturn('mock');

        $logger = new AuditLogger($driver);
        $service = new AuditService($logger);

        $result = $service->logDeleted('Orders', 123, ['DocNum' => 100]);

        expect($result)->toBeTrue();
    });

    it('queries for specific entity', function () {
        $expectedEntries = [
            AuditEntry::created('Items', 'A001', ['test' => true]),
        ];

        $driver = Mockery::mock(AuditDriverInterface::class);
        $driver->shouldReceive('getForEntity')
            ->with('Items', 'A001')
            ->once()
            ->andReturn($expectedEntries);

        $logger = new AuditLogger($driver);
        $service = new AuditService($logger);

        $entries = $service->for('Items', 'A001')->get();

        expect($entries)->toBe($expectedEntries);
    });

    it('queries by user', function () {
        $expectedEntries = [];

        $driver = Mockery::mock(AuditDriverInterface::class);
        $driver->shouldReceive('getByUser')
            ->with('1', null)
            ->once()
            ->andReturn($expectedEntries);

        $logger = new AuditLogger($driver);
        $service = new AuditService($logger);

        $entries = $service->byUser('1')->get();

        expect($entries)->toBe($expectedEntries);
    });

    it('queries by entity type', function () {
        $expectedEntries = [];

        $driver = Mockery::mock(AuditDriverInterface::class);
        $driver->shouldReceive('getByEntityType')
            ->with('Items', null, null)
            ->once()
            ->andReturn($expectedEntries);

        $logger = new AuditLogger($driver);
        $service = new AuditService($logger);

        $entries = $service->forEntity('Items')->get();

        expect($entries)->toBe($expectedEntries);
    });

    it('returns first result', function () {
        $entry = AuditEntry::created('Items', 'A001', ['test' => true]);

        $driver = Mockery::mock(AuditDriverInterface::class);
        $driver->shouldReceive('getByEntityType')
            ->andReturn([$entry]);

        $logger = new AuditLogger($driver);
        $service = new AuditService($logger);

        $result = $service->forEntity('Items')->first();

        expect($result)->toBe($entry);
    });

    it('returns null when no first result', function () {
        $driver = Mockery::mock(AuditDriverInterface::class);
        $driver->shouldReceive('getByEntityType')->andReturn([]);

        $logger = new AuditLogger($driver);
        $service = new AuditService($logger);

        $result = $service->forEntity('Items')->first();

        expect($result)->toBeNull();
    });

    it('calculates stats for entity type', function () {
        $entries = [
            AuditEntry::created('Items', 'A001', ['test' => true]),
            AuditEntry::created('Items', 'A002', ['test' => true]),
            AuditEntry::updated('Items', 'A001', ['old' => 1], ['new' => 2]),
            AuditEntry::deleted('Items', 'A003', ['test' => true]),
        ];

        $driver = Mockery::mock(AuditDriverInterface::class);
        $driver->shouldReceive('getByEntityType')->andReturn($entries);

        $logger = new AuditLogger($driver);
        $service = new AuditService($logger);

        $stats = $service->stats('Items');

        expect($stats)->toBe([
            'created' => 2,
            'updated' => 1,
            'deleted' => 1,
            'total' => 4,
        ]);
    });

    it('prunes old entries', function () {
        $driver = Mockery::mock(AuditDriverInterface::class);
        $driver->shouldReceive('prune')
            ->with(90)
            ->once()
            ->andReturn(50);

        $logger = new AuditLogger($driver);
        $service = new AuditService($logger);

        $pruned = $service->prune(90);

        expect($pruned)->toBe(50);
    });

    it('uses default retention days when not specified', function () {
        config(['laravel-toolkit.audit.retention.days' => 180]);

        $driver = Mockery::mock(AuditDriverInterface::class);
        $driver->shouldReceive('prune')
            ->with(180)
            ->once()
            ->andReturn(100);

        $logger = new AuditLogger($driver);
        $service = new AuditService($logger);

        $pruned = $service->prune();

        expect($pruned)->toBe(100);
    });

    it('returns logger instance', function () {
        $logger = new AuditLogger(new NullDriver);
        $service = new AuditService($logger);

        expect($service->getLogger())->toBe($logger);
    });

    it('returns driver instance', function () {
        $driver = new NullDriver;
        $logger = new AuditLogger($driver);
        $service = new AuditService($logger);

        expect($service->getDriver())->toBe($driver);
    });

    it('supports fluent without events', function () {
        $driver = new NullDriver;
        $logger = new AuditLogger($driver);
        $service = new AuditService($logger);

        $result = $service->withoutEvents();

        expect($result)->toBe($service);
    });
});
