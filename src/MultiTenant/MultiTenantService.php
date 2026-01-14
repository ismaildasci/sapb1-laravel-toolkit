<?php

declare(strict_types=1);

namespace SapB1\Toolkit\MultiTenant;

use Closure;
use Illuminate\Support\Facades\App;
use SapB1\Contracts\TenantResolverInterface;
use SapB1\Facades\SapB1;
use SapB1\MultiTenant\TenantManager;

/**
 * High-level multi-tenant service for SAP B1 operations.
 *
 * Wraps the SDK's TenantManager with Laravel-friendly features
 * and provides integration with the toolkit's services.
 *
 * @example
 * ```php
 * use SapB1\Toolkit\MultiTenant\MultiTenantService;
 *
 * $multiTenant = app(MultiTenantService::class);
 *
 * // Set current tenant
 * $multiTenant->setTenant('tenant-1');
 *
 * // Execute operations in tenant context
 * $multiTenant->runAs('tenant-2', function () {
 *     $orders = Order::all();
 * });
 *
 * // Register tenants from config
 * $multiTenant->registerFromConfig();
 * ```
 */
final class MultiTenantService
{
    private TenantManager $manager;

    public function __construct(?TenantManager $manager = null)
    {
        $this->manager = $manager ?? App::make(TenantManager::class);
    }

    /**
     * Set the current tenant.
     */
    public function setTenant(string $tenantId): self
    {
        $this->manager->setTenant($tenantId);

        // Update the SapB1 client connection if tenant has config
        $this->applyTenantConnection();

        return $this;
    }

    /**
     * Get the current tenant ID.
     */
    public function getTenantId(): ?string
    {
        return $this->manager->getTenantId();
    }

    /**
     * Check if a tenant is currently active.
     */
    public function hasTenant(): bool
    {
        return $this->manager->hasTenant();
    }

    /**
     * Clear the current tenant.
     */
    public function clearTenant(): self
    {
        $this->manager->clearTenant();

        return $this;
    }

    /**
     * Get the current tenant's SAP B1 connection configuration.
     *
     * @return array<string, mixed>|null
     */
    public function getConnectionConfig(): ?array
    {
        return $this->manager->getConnectionConfig();
    }

    /**
     * Get configuration for a specific tenant.
     *
     * @return array<string, mixed>|null
     */
    public function getTenantConfig(?string $tenantId = null): ?array
    {
        return $this->manager->getTenantConfig($tenantId);
    }

    /**
     * Register a tenant with configuration.
     *
     * @param  array<string, mixed>  $config  SAP B1 connection config
     */
    public function registerTenant(string $tenantId, array $config): self
    {
        $this->manager->registerTenant($tenantId, $config);

        return $this;
    }

    /**
     * Register tenants from Laravel config.
     *
     * Reads tenant configurations from 'laravel-toolkit.multi_tenant.tenants'.
     */
    public function registerFromConfig(): self
    {
        /** @var array<string, array<string, mixed>> $tenants */
        $tenants = config('laravel-toolkit.multi_tenant.tenants', []);

        foreach ($tenants as $tenantId => $config) {
            $this->registerTenant($tenantId, $config);
        }

        return $this;
    }

    /**
     * Execute a callback within a specific tenant's context.
     *
     * @template T
     *
     * @param  Closure(): T  $callback
     * @return T
     */
    public function runAs(string $tenantId, Closure $callback): mixed
    {
        $previousConfig = $this->getConnectionConfig();

        try {
            return $this->manager->runAs($tenantId, function () use ($callback) {
                $this->applyTenantConnection();

                return $callback();
            });
        } finally {
            // Restore previous connection if any
            if ($previousConfig !== null) {
                $this->applyTenantConnection();
            }
        }
    }

    /**
     * Set the tenant resolver.
     */
    public function setResolver(TenantResolverInterface|Closure $resolver): self
    {
        $this->manager->setResolver($resolver);

        return $this;
    }

    /**
     * Get all registered tenant IDs.
     *
     * @return array<int, string>
     */
    public function getRegisteredTenants(): array
    {
        return $this->manager->getRegisteredTenants();
    }

    /**
     * Check if a tenant is registered.
     */
    public function isRegistered(string $tenantId): bool
    {
        return $this->manager->isRegistered($tenantId);
    }

    /**
     * Get the underlying TenantManager instance.
     */
    public function getManager(): TenantManager
    {
        return $this->manager;
    }

    /**
     * Apply the current tenant's connection configuration to SapB1.
     */
    private function applyTenantConnection(): void
    {
        $config = $this->getConnectionConfig();

        if ($config === null) {
            return;
        }

        // The SDK's SapB1 facade can switch connections dynamically
        // This applies the tenant-specific configuration
        $tenantId = $this->getTenantId();
        if ($tenantId !== null) {
            SapB1::connection($tenantId);
        }
    }
}
