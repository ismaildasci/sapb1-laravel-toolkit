<?php

declare(strict_types=1);

use SapB1\Toolkit\Audit\AuditContext;
use SapB1\Toolkit\Audit\AuditEntry;
use SapB1\Toolkit\Audit\AuditLogger;
use SapB1\Toolkit\Audit\Contracts\AuditDriverInterface;
use SapB1\Toolkit\Audit\Drivers\NullDriver;

beforeEach(function () {
    config([
        'laravel-toolkit.audit.enabled' => true,
        'laravel-toolkit.audit.dispatch_events' => true,
        'laravel-toolkit.multi_tenant.enabled' => false,
    ]);
});

describe('AuditLogger', function () {
    it('logs entries using driver', function () {
        $driver = Mockery::mock(AuditDriverInterface::class);
        $driver->shouldReceive('store')->once()->andReturn(true);
        $driver->shouldReceive('getName')->andReturn('mock');

        $logger = new AuditLogger($driver);
        $entry = AuditEntry::created('Items', 'A001', ['ItemCode' => 'A001']);

        $result = $logger->log($entry);

        expect($result)->toBeTrue();
    });

    it('returns false when driver fails to store', function () {
        $driver = Mockery::mock(AuditDriverInterface::class);
        $driver->shouldReceive('store')->once()->andReturn(false);
        $driver->shouldReceive('getName')->andReturn('mock');

        $logger = new AuditLogger($driver);
        $logger->withoutEvents();
        $entry = AuditEntry::created('Items', 'A001', ['ItemCode' => 'A001']);

        $result = $logger->log($entry);

        expect($result)->toBeFalse();
    });

    it('logs created events', function () {
        $driver = Mockery::mock(AuditDriverInterface::class);
        $driver->shouldReceive('store')
            ->once()
            ->withArgs(function (AuditEntry $entry) {
                return $entry->event === 'created'
                    && $entry->entityType === 'Items'
                    && $entry->entityId === 'A001';
            })
            ->andReturn(true);
        $driver->shouldReceive('getName')->andReturn('mock');

        $logger = new AuditLogger($driver);
        $result = $logger->logCreated('Items', 'A001', ['ItemCode' => 'A001']);

        expect($result)->toBeTrue();
    });

    it('logs updated events', function () {
        $driver = Mockery::mock(AuditDriverInterface::class);
        $driver->shouldReceive('store')
            ->once()
            ->withArgs(function (AuditEntry $entry) {
                return $entry->event === 'updated'
                    && $entry->oldValues === ['Price' => 100]
                    && $entry->newValues === ['Price' => 150];
            })
            ->andReturn(true);
        $driver->shouldReceive('getName')->andReturn('mock');

        $logger = new AuditLogger($driver);
        $result = $logger->logUpdated('Items', 'A001', ['Price' => 100], ['Price' => 150]);

        expect($result)->toBeTrue();
    });

    it('logs deleted events', function () {
        $driver = Mockery::mock(AuditDriverInterface::class);
        $driver->shouldReceive('store')
            ->once()
            ->withArgs(function (AuditEntry $entry) {
                return $entry->event === 'deleted'
                    && $entry->oldValues === ['ItemCode' => 'A001'];
            })
            ->andReturn(true);
        $driver->shouldReceive('getName')->andReturn('mock');

        $logger = new AuditLogger($driver);
        $result = $logger->logDeleted('Items', 'A001', ['ItemCode' => 'A001']);

        expect($result)->toBeTrue();
    });

    it('logs custom events', function () {
        $driver = Mockery::mock(AuditDriverInterface::class);
        $driver->shouldReceive('store')
            ->once()
            ->withArgs(function (AuditEntry $entry) {
                return $entry->event === 'approved';
            })
            ->andReturn(true);
        $driver->shouldReceive('getName')->andReturn('mock');

        $logger = new AuditLogger($driver);
        $result = $logger->logCustom('Orders', 123, 'approved');

        expect($result)->toBeTrue();
    });

    it('uses custom context when provided', function () {
        $customContext = new AuditContext(
            userId: 'custom-user',
            metadata: ['custom' => true],
        );

        $driver = Mockery::mock(AuditDriverInterface::class);
        $driver->shouldReceive('store')
            ->once()
            ->withArgs(function (AuditEntry $entry) {
                return $entry->context->userId === 'custom-user'
                    && $entry->context->metadata === ['custom' => true];
            })
            ->andReturn(true);
        $driver->shouldReceive('getName')->andReturn('mock');

        $logger = new AuditLogger($driver);
        $logger->logCreated('Items', 'A001', ['test' => true], $customContext);
    });

    it('returns driver instance', function () {
        $driver = new NullDriver;
        $logger = new AuditLogger($driver);

        expect($logger->getDriver())->toBe($driver);
    });

    it('handles driver exceptions gracefully', function () {
        $driver = Mockery::mock(AuditDriverInterface::class);
        $driver->shouldReceive('store')
            ->once()
            ->andThrow(new RuntimeException('Storage failure'));
        $driver->shouldReceive('getName')->andReturn('mock');

        $logger = new AuditLogger($driver);
        $logger->withoutEvents();
        $entry = AuditEntry::created('Items', 'A001', ['test' => true]);

        $result = $logger->log($entry);

        expect($result)->toBeFalse();
    });

    it('can disable event dispatching', function () {
        $driver = new NullDriver;
        $logger = new AuditLogger($driver);

        $result = $logger->withoutEvents();

        expect($result)->toBe($logger);
    });
});
