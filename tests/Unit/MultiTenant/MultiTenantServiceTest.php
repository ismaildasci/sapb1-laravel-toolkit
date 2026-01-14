<?php

declare(strict_types=1);

use SapB1\MultiTenant\TenantManager;
use SapB1\Toolkit\MultiTenant\MultiTenantService;

beforeEach(function () {
    $this->manager = new TenantManager;
    $this->service = new MultiTenantService($this->manager);
});

describe('MultiTenantService', function () {
    it('sets and gets current tenant', function () {
        $this->service->setTenant('tenant-1');

        expect($this->service->getTenantId())->toBe('tenant-1');
        expect($this->service->hasTenant())->toBeTrue();
    });

    it('clears current tenant', function () {
        $this->service->setTenant('tenant-1');
        $this->service->clearTenant();

        expect($this->service->getTenantId())->toBeNull();
        expect($this->service->hasTenant())->toBeFalse();
    });

    it('registers tenant with configuration', function () {
        $config = [
            'sap_url' => 'https://sap.example.com/b1s/v1',
            'sap_database' => 'SBO_TEST',
            'sap_username' => 'manager',
            'sap_password' => 'secret',
        ];

        $this->service->registerTenant('tenant-1', $config);

        expect($this->service->isRegistered('tenant-1'))->toBeTrue();
        expect($this->service->getTenantConfig('tenant-1'))->toBe($config);
    });

    it('gets registered tenant list', function () {
        $this->service->registerTenant('tenant-1', ['key' => 'value']);
        $this->service->registerTenant('tenant-2', ['key' => 'value']);

        expect($this->service->getRegisteredTenants())->toBe(['tenant-1', 'tenant-2']);
    });

    it('executes callback in tenant context with runAs', function () {
        $this->service->registerTenant('tenant-1', ['company' => 'Company 1']);
        $this->service->registerTenant('tenant-2', ['company' => 'Company 2']);

        $this->service->setTenant('tenant-1');
        expect($this->service->getTenantId())->toBe('tenant-1');

        $result = $this->service->runAs('tenant-2', function () {
            return $this->service->getTenantId();
        });

        expect($result)->toBe('tenant-2');
        // After runAs, original tenant should be restored
        expect($this->service->getTenantId())->toBe('tenant-1');
    });

    it('returns connection config for current tenant', function () {
        $this->service->registerTenant('tenant-1', [
            'sap_url' => 'https://sap.example.com/b1s/v1',
            'sap_database' => 'SBO_TEST',
            'sap_username' => 'manager',
            'sap_password' => 'secret',
        ]);

        $this->service->setTenant('tenant-1');

        $connectionConfig = $this->service->getConnectionConfig();

        expect($connectionConfig)->toHaveKey('base_url');
        expect($connectionConfig['company_db'])->toBe('SBO_TEST');
        expect($connectionConfig['username'])->toBe('manager');
    });

    it('returns null when no tenant is set', function () {
        expect($this->service->getTenantId())->toBeNull();
        expect($this->service->hasTenant())->toBeFalse();
        expect($this->service->getConnectionConfig())->toBeNull();
    });

    it('provides access to underlying TenantManager', function () {
        expect($this->service->getManager())->toBeInstanceOf(TenantManager::class);
        expect($this->service->getManager())->toBe($this->manager);
    });

    it('allows custom resolver via closure', function () {
        $this->service->setResolver(fn () => 'resolved-tenant');

        expect($this->service->getTenantId())->toBe('resolved-tenant');
    });
});
