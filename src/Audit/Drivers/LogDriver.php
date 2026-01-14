<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Audit\Drivers;

use Illuminate\Support\Facades\Log;
use SapB1\Toolkit\Audit\AuditEntry;
use SapB1\Toolkit\Audit\Contracts\AuditDriverInterface;

/**
 * Log driver for storing audit logs to Laravel log.
 *
 * Useful for debugging or when database storage is not needed.
 * Note: Retrieval methods return empty arrays as logs are write-only.
 */
final class LogDriver implements AuditDriverInterface
{
    private string $channel;

    private string $level;

    public function __construct(?string $channel = null, ?string $level = null)
    {
        $this->channel = $channel ?? config('laravel-toolkit.audit.log_channel', 'stack');
        $this->level = $level ?? config('laravel-toolkit.audit.log_level', 'info');
    }

    /**
     * Store an audit entry by logging it.
     */
    public function store(AuditEntry $entry): bool
    {
        $message = sprintf(
            '[SAP Audit] %s %s #%s',
            strtoupper($entry->event),
            $entry->entityType,
            $entry->entityId
        );

        $context = [
            'entity_type' => $entry->entityType,
            'entity_id' => $entry->entityId,
            'event' => $entry->event,
            'changed_fields' => $entry->changedFields,
            'user_id' => $entry->context?->userId,
            'tenant_id' => $entry->context?->tenantId,
            'ip_address' => $entry->context?->ipAddress,
        ];

        // Add old/new values if not too large
        if ($entry->oldValues !== null && count($entry->oldValues) <= 20) {
            $context['old_values'] = $entry->oldValues;
        }

        if ($entry->newValues !== null && count($entry->newValues) <= 20) {
            $context['new_values'] = $entry->newValues;
        }

        Log::channel($this->channel)->log($this->level, $message, $context);

        return true;
    }

    /**
     * Retrieve audit entries for an entity.
     * Note: Log driver does not support retrieval.
     *
     * @return array<int, AuditEntry>
     */
    public function getForEntity(string $entityType, string|int $entityId): array
    {
        return [];
    }

    /**
     * Retrieve audit entries by user.
     * Note: Log driver does not support retrieval.
     *
     * @return array<int, AuditEntry>
     */
    public function getByUser(string|int $userId, ?string $userType = null): array
    {
        return [];
    }

    /**
     * Retrieve audit entries for an entity type.
     * Note: Log driver does not support retrieval.
     *
     * @return array<int, AuditEntry>
     */
    public function getByEntityType(string $entityType, ?string $since = null, ?int $limit = null): array
    {
        return [];
    }

    /**
     * Delete old audit entries based on retention policy.
     * Note: Log driver does not support pruning.
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
        return 'log';
    }
}
