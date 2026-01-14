<?php

declare(strict_types=1);

use SapB1\Toolkit\Audit\AuditContext;
use SapB1\Toolkit\Audit\AuditEntry;

beforeEach(function () {
    // Set up test context
    config(['laravel-toolkit.multi_tenant.enabled' => false]);
});

describe('AuditEntry', function () {
    it('creates a created event entry', function () {
        $context = new AuditContext(userId: '1', userType: 'App\Models\User');
        $values = ['ItemCode' => 'A001', 'ItemName' => 'Test Item'];

        $entry = AuditEntry::created('Items', 'A001', $values, $context);

        expect($entry->entityType)->toBe('Items');
        expect($entry->entityId)->toBe('A001');
        expect($entry->event)->toBe('created');
        expect($entry->oldValues)->toBeNull();
        expect($entry->newValues)->toBe($values);
        expect($entry->changedFields)->toBe(['ItemCode', 'ItemName']);
        expect($entry->context)->toBe($context);
        expect($entry->createdAt)->not->toBeNull();
    });

    it('creates an updated event entry', function () {
        $context = new AuditContext(userId: '1');
        $oldValues = ['ItemName' => 'Old Name', 'Price' => 100];
        $newValues = ['ItemName' => 'New Name', 'Price' => 150];

        $entry = AuditEntry::updated('Items', 'A001', $oldValues, $newValues, $context);

        expect($entry->entityType)->toBe('Items');
        expect($entry->entityId)->toBe('A001');
        expect($entry->event)->toBe('updated');
        expect($entry->oldValues)->toBe($oldValues);
        expect($entry->newValues)->toBe($newValues);
        expect($entry->changedFields)->toContain('ItemName', 'Price');
        expect($entry->isUpdated())->toBeTrue();
    });

    it('creates a deleted event entry', function () {
        $values = ['ItemCode' => 'A001', 'ItemName' => 'Deleted Item'];

        $entry = AuditEntry::deleted('Items', 'A001', $values);

        expect($entry->entityType)->toBe('Items');
        expect($entry->entityId)->toBe('A001');
        expect($entry->event)->toBe('deleted');
        expect($entry->oldValues)->toBe($values);
        expect($entry->newValues)->toBeNull();
        expect($entry->isDeleted())->toBeTrue();
    });

    it('creates a custom event entry', function () {
        $entry = AuditEntry::custom(
            entityType: 'Orders',
            entityId: 123,
            event: 'approved',
            oldValues: ['Status' => 'pending'],
            newValues: ['Status' => 'approved'],
        );

        expect($entry->event)->toBe('approved');
        expect($entry->isCreated())->toBeFalse();
        expect($entry->isUpdated())->toBeFalse();
        expect($entry->isDeleted())->toBeFalse();
    });

    it('checks event types correctly', function () {
        $createdEntry = AuditEntry::created('Items', 'A001', ['test' => true]);
        $updatedEntry = AuditEntry::updated('Items', 'A001', ['old' => 1], ['new' => 2]);
        $deletedEntry = AuditEntry::deleted('Items', 'A001', ['test' => true]);

        expect($createdEntry->isCreated())->toBeTrue();
        expect($createdEntry->isUpdated())->toBeFalse();
        expect($createdEntry->isDeleted())->toBeFalse();

        expect($updatedEntry->isCreated())->toBeFalse();
        expect($updatedEntry->isUpdated())->toBeTrue();
        expect($updatedEntry->isDeleted())->toBeFalse();

        expect($deletedEntry->isCreated())->toBeFalse();
        expect($deletedEntry->isUpdated())->toBeFalse();
        expect($deletedEntry->isDeleted())->toBeTrue();
    });

    it('gets old and new values for fields', function () {
        $entry = AuditEntry::updated(
            entityType: 'Items',
            entityId: 'A001',
            oldValues: ['Price' => 100, 'Stock' => 50],
            newValues: ['Price' => 150, 'Stock' => 75],
        );

        expect($entry->getOldValue('Price'))->toBe(100);
        expect($entry->getNewValue('Price'))->toBe(150);
        expect($entry->getOldValue('NonExistent'))->toBeNull();
    });

    it('checks if field was changed', function () {
        $entry = AuditEntry::updated(
            entityType: 'Items',
            entityId: 'A001',
            oldValues: ['Price' => 100],
            newValues: ['Price' => 150],
        );

        expect($entry->hasChangedField('Price'))->toBeTrue();
        expect($entry->hasChangedField('Stock'))->toBeFalse();
    });

    it('counts changed fields', function () {
        $entry = AuditEntry::updated(
            entityType: 'Items',
            entityId: 'A001',
            oldValues: ['Price' => 100, 'Stock' => 50, 'Name' => 'Old'],
            newValues: ['Price' => 150, 'Stock' => 75, 'Name' => 'New'],
        );

        expect($entry->changedFieldsCount())->toBe(3);
    });

    it('converts to array correctly', function () {
        $context = new AuditContext(
            userId: '1',
            userType: 'App\Models\User',
            ipAddress: '127.0.0.1',
            tenantId: 'tenant-1',
            metadata: ['source' => 'api'],
        );

        $entry = AuditEntry::created(
            entityType: 'Items',
            entityId: 'A001',
            values: ['ItemCode' => 'A001'],
            context: $context,
        );

        $array = $entry->toArray();

        expect($array)->toHaveKey('auditable_type', 'Items');
        expect($array)->toHaveKey('auditable_id', 'A001');
        expect($array)->toHaveKey('event', 'created');
        expect($array)->toHaveKey('user_id', '1');
        expect($array)->toHaveKey('user_type', 'App\Models\User');
        expect($array)->toHaveKey('ip_address', '127.0.0.1');
        expect($array)->toHaveKey('tenant_id', 'tenant-1');
        expect($array['metadata'])->toBe(['source' => 'api']);
    });

    it('creates from array correctly', function () {
        $data = [
            'id' => 1,
            'auditable_type' => 'Orders',
            'auditable_id' => '123',
            'event' => 'created',
            'old_values' => null,
            'new_values' => ['DocNum' => 100],
            'changed_fields' => ['DocNum'],
            'user_id' => '1',
            'user_type' => 'App\Models\User',
            'ip_address' => '127.0.0.1',
            'user_agent' => 'Mozilla/5.0',
            'tenant_id' => 'tenant-1',
            'metadata' => [],
            'created_at' => '2026-01-14 12:00:00',
        ];

        $entry = AuditEntry::fromArray($data);

        expect($entry->id)->toBe(1);
        expect($entry->entityType)->toBe('Orders');
        expect($entry->entityId)->toBe('123');
        expect($entry->event)->toBe('created');
        expect($entry->context->userId)->toBe('1');
        expect($entry->createdAt)->not->toBeNull();
    });

    it('handles numeric entity ids', function () {
        $entry = AuditEntry::created('Orders', 12345, ['DocNum' => 100]);

        expect($entry->entityId)->toBe(12345);
        $array = $entry->toArray();
        expect($array['auditable_id'])->toBe('12345');
    });
});
