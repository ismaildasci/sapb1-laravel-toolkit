<?php

declare(strict_types=1);

namespace SapB1\Toolkit\MultiTenant\Resolvers;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use SapB1\Contracts\TenantResolverInterface;

/**
 * Tenant resolver that reads tenant ID from authenticated user.
 *
 * This resolver extracts the tenant ID from the authenticated user's
 * tenant_id attribute and loads configuration from Laravel config.
 *
 * @example
 * ```php
 * // User model should have tenant_id attribute
 * class User extends Authenticatable
 * {
 *     protected $fillable = ['name', 'email', 'tenant_id'];
 * }
 *
 * // Configure in service provider
 * $multiTenant->setResolver(new AuthUserTenantResolver());
 * ```
 */
final class AuthUserTenantResolver implements TenantResolverInterface
{
    /**
     * Default tenant ID attribute name on User model.
     */
    private const DEFAULT_ATTRIBUTE = 'tenant_id';

    private string $tenantAttribute;

    private ?string $guardName;

    public function __construct(
        ?string $tenantAttribute = null,
        ?string $guardName = null
    ) {
        $this->tenantAttribute = $tenantAttribute ?? self::DEFAULT_ATTRIBUTE;
        $this->guardName = $guardName;
    }

    /**
     * Resolve the current tenant ID from authenticated user.
     */
    public function resolve(): ?string
    {
        $user = $this->getUser();

        if ($user === null) {
            return null;
        }

        return $this->getTenantIdFromUser($user);
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
     * Set the tenant attribute name.
     */
    public function setTenantAttribute(string $attribute): self
    {
        $this->tenantAttribute = $attribute;

        return $this;
    }

    /**
     * Get the tenant attribute name.
     */
    public function getTenantAttribute(): string
    {
        return $this->tenantAttribute;
    }

    /**
     * Set the auth guard name.
     */
    public function setGuardName(string $guardName): self
    {
        $this->guardName = $guardName;

        return $this;
    }

    /**
     * Get the authenticated user.
     */
    private function getUser(): ?Authenticatable
    {
        if ($this->guardName !== null) {
            return Auth::guard($this->guardName)->user();
        }

        return Auth::user();
    }

    /**
     * Get tenant ID from user instance.
     */
    private function getTenantIdFromUser(Authenticatable $user): ?string
    {
        /** @var mixed $tenantId */
        $tenantId = $user->{$this->tenantAttribute} ?? null;

        if ($tenantId === null || $tenantId === '') {
            return null;
        }

        return (string) $tenantId;
    }
}
