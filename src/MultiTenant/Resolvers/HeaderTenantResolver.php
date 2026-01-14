<?php

declare(strict_types=1);

namespace SapB1\Toolkit\MultiTenant\Resolvers;

use Illuminate\Http\Request;
use SapB1\Contracts\TenantResolverInterface;

/**
 * Tenant resolver that reads tenant ID from HTTP headers.
 *
 * This resolver extracts the tenant ID from a configurable HTTP header
 * and loads configuration from Laravel config.
 *
 * @example
 * ```php
 * // Configure in service provider
 * $multiTenant->setResolver(new HeaderTenantResolver(
 *     request: request(),
 *     headerName: 'X-Tenant-ID'
 * ));
 *
 * // Client sends request with header
 * // X-Tenant-ID: tenant-1
 * ```
 */
final class HeaderTenantResolver implements TenantResolverInterface
{
    /**
     * Default header name.
     */
    private const DEFAULT_HEADER = 'X-Tenant-ID';

    private ?Request $request = null;

    private string $headerName;

    public function __construct(
        ?Request $request = null,
        ?string $headerName = null
    ) {
        $this->request = $request;
        $this->headerName = $headerName ?? config('laravel-toolkit.multi_tenant.header', self::DEFAULT_HEADER);
    }

    /**
     * Resolve the current tenant ID from the request header.
     */
    public function resolve(): ?string
    {
        $request = $this->getRequest();

        if ($request === null) {
            return null;
        }

        /** @var string|null $tenantId */
        $tenantId = $request->header($this->headerName);

        if ($tenantId === null || $tenantId === '') {
            return null;
        }

        return $tenantId;
    }

    /**
     * Get configuration for a specific tenant from config.
     *
     * @return array<string, mixed>|null
     */
    public function getConfig(string $tenantId): ?array
    {
        /** @var array<string, array<string, mixed>> $tenants */
        $tenants = config('laravel-toolkit.multi_tenant.tenants', []);

        return $tenants[$tenantId] ?? null;
    }

    /**
     * Set the request instance.
     */
    public function setRequest(Request $request): self
    {
        $this->request = $request;

        return $this;
    }

    /**
     * Set the header name.
     */
    public function setHeaderName(string $headerName): self
    {
        $this->headerName = $headerName;

        return $this;
    }

    /**
     * Get the header name.
     */
    public function getHeaderName(): string
    {
        return $this->headerName;
    }

    /**
     * Get the current request.
     */
    private function getRequest(): ?Request
    {
        if ($this->request !== null) {
            return $this->request;
        }

        // Try to resolve from container
        if (app()->bound('request')) {
            return app('request');
        }

        return null;
    }
}
