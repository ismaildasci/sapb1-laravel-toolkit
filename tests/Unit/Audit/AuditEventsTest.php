<?php

declare(strict_types=1);

use SapB1\Toolkit\Audit\AuditEntry;
use SapB1\Toolkit\Audit\Events\AuditFailed;
use SapB1\Toolkit\Audit\Events\AuditRecorded;

beforeEach(function () {
    config(['laravel-toolkit.multi_tenant.enabled' => false]);
});

describe('AuditRecorded Event', function () {
    it('contains the audit entry', function () {
        $entry = AuditEntry::created('Items', 'A001', ['ItemCode' => 'A001']);
        $event = new AuditRecorded($entry, 'database');

        expect($event->entry)->toBe($entry);
        expect($event->driver)->toBe('database');
    });

    it('provides entity type accessor', function () {
        $entry = AuditEntry::created('Orders', 123, ['DocNum' => 100]);
        $event = new AuditRecorded($entry, 'null');

        expect($event->getEntityType())->toBe('Orders');
    });

    it('provides entity id accessor', function () {
        $entry = AuditEntry::created('Orders', 123, ['DocNum' => 100]);
        $event = new AuditRecorded($entry, 'null');

        expect($event->getEntityId())->toBe(123);
    });

    it('provides event type accessor', function () {
        $entry = AuditEntry::updated('Items', 'A001', ['old' => 1], ['new' => 2]);
        $event = new AuditRecorded($entry, 'log');

        expect($event->getEvent())->toBe('updated');
    });
});

describe('AuditFailed Event', function () {
    it('contains the audit entry and driver', function () {
        $entry = AuditEntry::created('Items', 'A001', ['ItemCode' => 'A001']);
        $event = new AuditFailed($entry, 'database');

        expect($event->entry)->toBe($entry);
        expect($event->driver)->toBe('database');
    });

    it('can include an exception', function () {
        $entry = AuditEntry::created('Items', 'A001', ['ItemCode' => 'A001']);
        $exception = new RuntimeException('Storage failed');
        $event = new AuditFailed($entry, 'database', $exception);

        expect($event->exception)->toBe($exception);
    });

    it('exception is optional', function () {
        $entry = AuditEntry::created('Items', 'A001', ['ItemCode' => 'A001']);
        $event = new AuditFailed($entry, 'database');

        expect($event->exception)->toBeNull();
    });

    it('can include a reason', function () {
        $entry = AuditEntry::created('Items', 'A001', ['ItemCode' => 'A001']);
        $event = new AuditFailed($entry, 'database', null, 'Table not found');

        expect($event->reason)->toBe('Table not found');
    });

    it('provides error message from exception', function () {
        $entry = AuditEntry::created('Items', 'A001', ['ItemCode' => 'A001']);
        $exception = new RuntimeException('Connection timeout');
        $event = new AuditFailed($entry, 'database', $exception);

        expect($event->getErrorMessage())->toBe('Connection timeout');
    });

    it('provides error message from reason', function () {
        $entry = AuditEntry::created('Items', 'A001', ['ItemCode' => 'A001']);
        $event = new AuditFailed($entry, 'database', null, 'Custom reason');

        expect($event->getErrorMessage())->toBe('Custom reason');
    });

    it('returns unknown error when no exception or reason', function () {
        $entry = AuditEntry::created('Items', 'A001', ['ItemCode' => 'A001']);
        $event = new AuditFailed($entry, 'database');

        expect($event->getErrorMessage())->toBe('Unknown error');
    });

    it('provides entity type accessor', function () {
        $entry = AuditEntry::created('Orders', 123, ['test' => true]);
        $event = new AuditFailed($entry, 'database');

        expect($event->getEntityType())->toBe('Orders');
    });

    it('provides entity id accessor', function () {
        $entry = AuditEntry::created('Orders', 123, ['test' => true]);
        $event = new AuditFailed($entry, 'database');

        expect($event->getEntityId())->toBe(123);
    });
});
