<?php

declare(strict_types=1);

use SapB1\Toolkit\Audit\AuditContext;

beforeEach(function () {
    config(['laravel-toolkit.multi_tenant.enabled' => false]);
});

describe('AuditContext', function () {
    it('creates empty context', function () {
        $context = new AuditContext;

        expect($context->userId)->toBeNull();
        expect($context->userType)->toBeNull();
        expect($context->ipAddress)->toBeNull();
        expect($context->userAgent)->toBeNull();
        expect($context->tenantId)->toBeNull();
        expect($context->metadata)->toBe([]);
    });

    it('creates context with all parameters', function () {
        $context = new AuditContext(
            userId: '1',
            userType: 'App\Models\User',
            ipAddress: '192.168.1.1',
            userAgent: 'Mozilla/5.0',
            tenantId: 'tenant-1',
            metadata: ['source' => 'web', 'action' => 'bulk_update'],
        );

        expect($context->userId)->toBe('1');
        expect($context->userType)->toBe('App\Models\User');
        expect($context->ipAddress)->toBe('192.168.1.1');
        expect($context->userAgent)->toBe('Mozilla/5.0');
        expect($context->tenantId)->toBe('tenant-1');
        expect($context->metadata)->toBe(['source' => 'web', 'action' => 'bulk_update']);
    });

    it('creates system context', function () {
        $context = AuditContext::system(['job' => 'sync']);

        expect($context->userId)->toBe('system');
        expect($context->userType)->toBe('system');
        expect($context->ipAddress)->toBeNull();
        expect($context->userAgent)->toBe('System Process');
        expect($context->metadata)->toHaveKey('automated', true);
        expect($context->metadata)->toHaveKey('job', 'sync');
    });

    it('converts to array', function () {
        $context = new AuditContext(
            userId: '1',
            userType: 'App\Models\User',
            ipAddress: '127.0.0.1',
            userAgent: 'Test Agent',
            tenantId: 'tenant-1',
            metadata: ['key' => 'value'],
        );

        $array = $context->toArray();

        expect($array)->toBe([
            'user_id' => '1',
            'user_type' => 'App\Models\User',
            'ip_address' => '127.0.0.1',
            'user_agent' => 'Test Agent',
            'tenant_id' => 'tenant-1',
            'metadata' => ['key' => 'value'],
        ]);
    });

    it('creates from array', function () {
        $data = [
            'user_id' => '42',
            'user_type' => 'App\Models\Admin',
            'ip_address' => '10.0.0.1',
            'user_agent' => 'API Client',
            'tenant_id' => 'tenant-2',
            'metadata' => ['api_version' => 'v2'],
        ];

        $context = AuditContext::fromArray($data);

        expect($context->userId)->toBe('42');
        expect($context->userType)->toBe('App\Models\Admin');
        expect($context->ipAddress)->toBe('10.0.0.1');
        expect($context->userAgent)->toBe('API Client');
        expect($context->tenantId)->toBe('tenant-2');
        expect($context->metadata)->toBe(['api_version' => 'v2']);
    });

    it('creates from array with missing keys', function () {
        $data = [
            'user_id' => '1',
        ];

        $context = AuditContext::fromArray($data);

        expect($context->userId)->toBe('1');
        expect($context->userType)->toBeNull();
        expect($context->ipAddress)->toBeNull();
        expect($context->metadata)->toBe([]);
    });

    it('is immutable', function () {
        $context = new AuditContext(userId: '1');

        // Verify it's a readonly class (this test passes if no error is thrown)
        $reflection = new ReflectionClass($context);
        expect($reflection->isReadOnly())->toBeTrue();
    });
});
