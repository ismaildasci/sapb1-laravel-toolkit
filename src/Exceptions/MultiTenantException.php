<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Exceptions;

use RuntimeException;

/**
 * Exception for multi-tenant related errors.
 */
class MultiTenantException extends RuntimeException
{
    /**
     * Create exception for tenant not found.
     */
    public static function tenantNotFound(string $tenantId): self
    {
        return new self("Tenant '{$tenantId}' not found or not registered.");
    }

    /**
     * Create exception for no tenant set.
     */
    public static function noTenantSet(): self
    {
        return new self('No tenant has been set. Call setTenant() first.');
    }

    /**
     * Create exception for missing tenant configuration.
     */
    public static function missingConfiguration(string $tenantId): self
    {
        return new self("Missing SAP B1 configuration for tenant '{$tenantId}'.");
    }

    /**
     * Create exception for invalid resolver.
     */
    public static function invalidResolver(string $resolver): self
    {
        return new self("Invalid tenant resolver: '{$resolver}'. Must implement TenantResolverInterface.");
    }

    /**
     * Create exception for tenant context violation.
     */
    public static function tenantMismatch(string $expected, ?string $actual): self
    {
        $actualStr = $actual ?? 'null';

        return new self("Tenant context mismatch. Expected '{$expected}', got '{$actualStr}'.");
    }
}
