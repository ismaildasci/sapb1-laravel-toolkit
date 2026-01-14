<?php

declare(strict_types=1);

use Illuminate\Http\Request;
use SapB1\Toolkit\MultiTenant\Resolvers\ConfigTenantResolver;
use SapB1\Toolkit\MultiTenant\Resolvers\HeaderTenantResolver;

describe('ConfigTenantResolver', function () {
    beforeEach(function () {
        $this->resolver = new ConfigTenantResolver;
    });

    it('requires explicit tenant setting', function () {
        expect($this->resolver->resolve())->toBeNull();
    });

    it('returns set tenant', function () {
        $this->resolver->setTenant('tenant-1');

        expect($this->resolver->resolve())->toBe('tenant-1');
    });

    it('clears tenant', function () {
        $this->resolver->setTenant('tenant-1');
        $this->resolver->clearTenant();

        expect($this->resolver->resolve())->toBeNull();
    });

    it('checks if tenant exists', function () {
        config(['laravel-toolkit.multi_tenant.tenants' => [
            'tenant-1' => ['sap_database' => 'SBO_1'],
        ]]);

        expect($this->resolver->hasTenant('tenant-1'))->toBeTrue();
        expect($this->resolver->hasTenant('nonexistent'))->toBeFalse();
    });

    it('returns available tenants from config', function () {
        config(['laravel-toolkit.multi_tenant.tenants' => [
            'tenant-1' => ['sap_database' => 'SBO_1'],
            'tenant-2' => ['sap_database' => 'SBO_2'],
        ]]);

        expect($this->resolver->getAvailableTenants())->toBe(['tenant-1', 'tenant-2']);
    });

    it('returns config for tenant', function () {
        config(['laravel-toolkit.multi_tenant.tenants' => [
            'tenant-1' => [
                'sap_database' => 'SBO_1',
                'sap_url' => 'https://sap.example.com/b1s/v1',
            ],
        ]]);

        $config = $this->resolver->getConfig('tenant-1');

        expect($config)->toBe([
            'sap_database' => 'SBO_1',
            'sap_url' => 'https://sap.example.com/b1s/v1',
        ]);
    });

    it('returns null for nonexistent tenant config', function () {
        config(['laravel-toolkit.multi_tenant.tenants' => []]);

        expect($this->resolver->getConfig('nonexistent'))->toBeNull();
    });
});

describe('HeaderTenantResolver', function () {
    it('resolves tenant from request header', function () {
        $request = Request::create('/api/orders', 'GET');
        $request->headers->set('X-Tenant-ID', 'tenant-from-header');

        $resolver = new HeaderTenantResolver($request);

        expect($resolver->resolve())->toBe('tenant-from-header');
    });

    it('returns null when header is missing', function () {
        $request = Request::create('/api/orders', 'GET');

        $resolver = new HeaderTenantResolver($request);

        expect($resolver->resolve())->toBeNull();
    });

    it('returns null when header is empty', function () {
        $request = Request::create('/api/orders', 'GET');
        $request->headers->set('X-Tenant-ID', '');

        $resolver = new HeaderTenantResolver($request);

        expect($resolver->resolve())->toBeNull();
    });

    it('uses custom header name', function () {
        $request = Request::create('/api/orders', 'GET');
        $request->headers->set('Custom-Tenant', 'custom-tenant-value');

        $resolver = new HeaderTenantResolver($request, 'Custom-Tenant');

        expect($resolver->resolve())->toBe('custom-tenant-value');
        expect($resolver->getHeaderName())->toBe('Custom-Tenant');
    });

    it('allows changing header name', function () {
        $request = Request::create('/api/orders', 'GET');
        $request->headers->set('New-Header', 'new-value');

        $resolver = new HeaderTenantResolver($request);
        $resolver->setHeaderName('New-Header');

        expect($resolver->resolve())->toBe('new-value');
    });

    it('allows setting request instance', function () {
        $resolver = new HeaderTenantResolver;

        $request = Request::create('/api/orders', 'GET');
        $request->headers->set('X-Tenant-ID', 'late-binding');

        $resolver->setRequest($request);

        expect($resolver->resolve())->toBe('late-binding');
    });

    it('gets config from laravel config', function () {
        config(['laravel-toolkit.multi_tenant.tenants' => [
            'my-tenant' => ['sap_database' => 'SBO_MY'],
        ]]);

        $request = Request::create('/api/orders', 'GET');
        $resolver = new HeaderTenantResolver($request);

        expect($resolver->getConfig('my-tenant'))->toBe(['sap_database' => 'SBO_MY']);
    });
});
