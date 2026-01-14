<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Audit\Exceptions;

use RuntimeException;

/**
 * Exception for audit-related errors.
 */
class AuditException extends RuntimeException
{
    /**
     * Create exception for failed audit storage.
     */
    public static function storageFailed(string $driver, ?string $reason = null): self
    {
        $message = "Failed to store audit log using driver '{$driver}'";

        if ($reason !== null) {
            $message .= ": {$reason}";
        }

        return new self($message);
    }

    /**
     * Create exception for invalid driver.
     */
    public static function invalidDriver(string $driver): self
    {
        return new self("Invalid audit driver: '{$driver}'. Must implement AuditDriverInterface.");
    }

    /**
     * Create exception for missing configuration.
     */
    public static function missingConfiguration(string $key): self
    {
        return new self("Missing audit configuration: '{$key}'");
    }

    /**
     * Create exception for disabled auditing.
     */
    public static function auditingDisabled(): self
    {
        return new self('Auditing is disabled. Enable it in configuration to use audit features.');
    }

    /**
     * Create exception for invalid entity.
     */
    public static function invalidEntity(string $entity): self
    {
        return new self("Entity '{$entity}' does not implement AuditableInterface.");
    }
}
