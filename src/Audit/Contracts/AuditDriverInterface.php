<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Audit\Contracts;

use SapB1\Toolkit\Audit\AuditEntry;

/**
 * Interface for audit log storage drivers.
 */
interface AuditDriverInterface
{
    /**
     * Store an audit entry.
     */
    public function store(AuditEntry $entry): bool;

    /**
     * Retrieve audit entries for an entity.
     *
     * @return array<int, AuditEntry>
     */
    public function getForEntity(string $entityType, string|int $entityId): array;

    /**
     * Retrieve audit entries by user.
     *
     * @return array<int, AuditEntry>
     */
    public function getByUser(string|int $userId, ?string $userType = null): array;

    /**
     * Retrieve audit entries for an entity type.
     *
     * @return array<int, AuditEntry>
     */
    public function getByEntityType(string $entityType, ?string $since = null, ?int $limit = null): array;

    /**
     * Delete old audit entries based on retention policy.
     */
    public function prune(int $days): int;

    /**
     * Get the driver name.
     */
    public function getName(): string;
}
