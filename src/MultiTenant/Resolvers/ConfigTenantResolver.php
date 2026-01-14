<?php

declare(strict_types=1);

namespace SapB1\Toolkit\MultiTenant\Resolvers;

use SapB1\Contracts\TenantResolverInterface;

/**
 * Tenant resolver that loads tenant configurations from Laravel config.
 *
 * This resolver reads tenant configurations from the 'laravel-toolkit.multi_tenant.tenants'
 * configuration array. It requires explicit tenant setting and does not auto-resolve.
 *
 * @example
 * ```php
 * // config/laravel-toolkit.php
 * 'multi_tenant' => [
 *     'tenants' => [
 *         'tenant-1' => [
 *             'sap_url' => 'https://sap1.example.com:50000/b1s/v1',
 *             'sap_database' => 'SBO_TENANT1',
 *             'sap_username' => 'manager',
 *             'sap_password' => 'secret',
 *         ],
 *         'tenant-2' => [
 *             'sap_url' => 'https://sap2.example.com:50000/b1s/v1',
 *             'sap_database' => 'SBO_TENANT2',
 *             'sap_username' => 'manager',
 *             'sap_password' => 'secret',
 *         ],
 *     ],
 * ],
 * ```
 */
final class ConfigTenantResolver implements TenantResolverInterface
{
    /**
     * Current tenant ID (set manually).
     */
    private ?string $currentTenantId = null;

    /**
     * Resolve the current tenant ID.
     *
     * This resolver requires explicit setting via setTenant().
     */
    public function resolve(): ?string
    {
        return $this->currentTenantId;
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
     * Set the current tenant ID.
     */
    public function setTenant(string $tenantId): self
    {
        $this->currentTenantId = $tenantId;

        return $this;
    }

    /**
     * Clear the current tenant ID.
     */
    public function clearTenant(): self
    {
        $this->currentTenantId = null;

        return $this;
    }

    /**
     * Get all available tenant IDs from config.
     *
     * @return array<int, string>
     */
    public function getAvailableTenants(): array
    {
        /** @var array<string, array<string, mixed>> $tenants */
        $tenants = config('laravel-toolkit.multi_tenant.tenants', []);

        return array_keys($tenants);
    }

    /**
     * Check if a tenant exists in config.
     */
    public function hasTenant(string $tenantId): bool
    {
        return $this->getConfig($tenantId) !== null;
    }
}
