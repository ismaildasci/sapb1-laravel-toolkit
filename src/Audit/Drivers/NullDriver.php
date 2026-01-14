<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Audit\Drivers;

use SapB1\Toolkit\Audit\AuditEntry;
use SapB1\Toolkit\Audit\Contracts\AuditDriverInterface;

/**
 * Null driver for audit logs (no-op).
 *
 * Useful for testing or when auditing should be completely disabled.
 */
final class NullDriver implements AuditDriverInterface
{
    /**
     * Store an audit entry (no-op).
     */
    public function store(AuditEntry $entry): bool
    {
        return true;
    }

    /**
     * Retrieve audit entries for an entity.
     *
     * @return array<int, AuditEntry>
     */
    public function getForEntity(string $entityType, string|int $entityId): array
    {
        return [];
    }

    /**
     * Retrieve audit entries by user.
     *
     * @return array<int, AuditEntry>
     */
    public function getByUser(string|int $userId, ?string $userType = null): array
    {
        return [];
    }

    /**
     * Retrieve audit entries for an entity type.
     *
     * @return array<int, AuditEntry>
     */
    public function getByEntityType(string $entityType, ?string $since = null, ?int $limit = null): array
    {
        return [];
    }

    /**
     * Delete old audit entries based on retention policy.
     */
    public function prune(int $days): int
    {
        return 0;
    }

    /**
     * Get the driver name.
     */
    public function getName(): string
    {
        return 'null';
    }
}
