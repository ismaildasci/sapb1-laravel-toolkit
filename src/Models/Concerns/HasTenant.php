<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Models\Concerns;

use Illuminate\Support\Facades\App;
use SapB1\Toolkit\MultiTenant\MultiTenantService;

/**
 * Provides multi-tenant context awareness for models.
 *
 * This trait allows models to track which tenant they belong to
 * and automatically use the correct SAP B1 connection.
 *
 * Usage:
 * ```php
 * class Order extends SapB1Model
 * {
 *     use HasTenant;
 *
 *     // Optionally auto-scope queries to current tenant
 *     protected static bool $autoScopeTenant = true;
 * }
 * ```
 *
 * @phpstan-require-extends \SapB1\Toolkit\Models\SapB1Model
 */
trait HasTenant
{
    /**
     * The tenant ID this model instance belongs to.
     */
    protected ?string $tenantId = null;

    /**
     * Whether to automatically scope queries to the current tenant.
     * Override in subclass to enable auto-scoping.
     */
    // protected static bool $autoScopeTenant = false;

    /**
     * Boot the trait.
     */
    public static function bootHasTenant(): void
    {
        static::creating(function ($model): void {
            if ($model->tenantId === null && static::shouldAutoScopeTenant()) {
                $model->setTenantId(static::getCurrentTenantId());
            }
        });
    }

    /**
     * Set the tenant ID for this model instance.
     */
    public function setTenantId(?string $tenantId): static
    {
        $this->tenantId = $tenantId;

        return $this;
    }

    /**
     * Get the tenant ID for this model instance.
     */
    public function getTenantId(): ?string
    {
        return $this->tenantId;
    }

    /**
     * Check if this model belongs to a specific tenant.
     */
    public function belongsToTenant(string $tenantId): bool
    {
        return $this->tenantId === $tenantId;
    }

    /**
     * Check if this model belongs to the current tenant.
     */
    public function belongsToCurrentTenant(): bool
    {
        $currentTenant = static::getCurrentTenantId();

        if ($currentTenant === null) {
            return true; // No tenant set, allow access
        }

        return $this->tenantId === $currentTenant;
    }

    /**
     * Scope query to a specific tenant.
     *
     * This changes the SAP B1 connection context for the query.
     *
     * @return static
     */
    public static function forTenant(string $tenantId): static
    {
        $model = new static();
        $model->setTenantId($tenantId);

        // Set the connection context
        $multiTenant = App::make(MultiTenantService::class);
        $multiTenant->setTenant($tenantId);

        return $model;
    }

    /**
     * Check if auto-scoping is enabled.
     */
    public static function shouldAutoScopeTenant(): bool
    {
        if (property_exists(static::class, 'autoScopeTenant')) {
            return static::$autoScopeTenant;
        }

        return false;
    }

    /**
     * Get the current tenant ID from the service.
     */
    protected static function getCurrentTenantId(): ?string
    {
        if (! App::has(MultiTenantService::class)) {
            return null;
        }

        /** @var MultiTenantService $multiTenant */
        $multiTenant = App::make(MultiTenantService::class);

        return $multiTenant->getTenantId();
    }

    /**
     * Get the MultiTenantService instance.
     */
    protected static function getMultiTenantService(): ?MultiTenantService
    {
        if (! App::has(MultiTenantService::class)) {
            return null;
        }

        return App::make(MultiTenantService::class);
    }
}
