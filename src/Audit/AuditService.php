<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Audit;

use SapB1\Toolkit\Audit\Contracts\AuditDriverInterface;

/**
 * High-level audit service for SAP B1 operations.
 *
 * Provides a fluent interface for logging and querying audit entries.
 *
 * @example
 * ```php
 * use SapB1\Toolkit\Audit\AuditService;
 *
 * $auditService = app(AuditService::class);
 *
 * // Log operations
 * $auditService->log('Orders', 123, 'created', $data);
 * $auditService->logCreated('Orders', 123, $data);
 * $auditService->logUpdated('Items', 'A001', $old, $new);
 *
 * // Query audit logs
 * $logs = $auditService->for('Orders', 123)->get();
 * $logs = $auditService->forEntity('Orders')->since('2026-01-01')->get();
 * $logs = $auditService->byUser($userId)->get();
 *
 * // Statistics
 * $stats = $auditService->stats('Orders');
 * ```
 */
final class AuditService
{
    private AuditLogger $logger;

    private bool $enabled;

    // Query builder state
    private ?string $queryEntityType = null;

    private string|int|null $queryEntityId = null;

    private string|int|null $queryUserId = null;

    private ?string $queryUserType = null;

    private ?string $querySince = null;

    private ?int $queryLimit = null;

    public function __construct(?AuditLogger $logger = null)
    {
        $this->logger = $logger ?? new AuditLogger;
        $this->enabled = (bool) config('laravel-toolkit.audit.enabled', true);
    }

    /**
     * Check if auditing is enabled.
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * Log an audit entry.
     *
     * @param  array<string, mixed>|null  $oldValues
     * @param  array<string, mixed>|null  $newValues
     */
    public function log(
        string $entityType,
        string|int $entityId,
        string $event,
        ?array $oldValues = null,
        ?array $newValues = null,
        ?AuditContext $context = null,
    ): bool {
        if (! $this->enabled) {
            return false;
        }

        if (! $this->isEntityEnabled($entityType)) {
            return false;
        }

        return $this->logger->logCustom(
            entityType: $entityType,
            entityId: $entityId,
            event: $event,
            oldValues: $oldValues,
            newValues: $newValues,
            context: $context,
        );
    }

    /**
     * Log a created event.
     *
     * @param  array<string, mixed>  $values
     */
    public function logCreated(
        string $entityType,
        string|int $entityId,
        array $values,
        ?AuditContext $context = null,
    ): bool {
        if (! $this->enabled || ! $this->isEntityEnabled($entityType)) {
            return false;
        }

        return $this->logger->logCreated($entityType, $entityId, $values, $context);
    }

    /**
     * Log an updated event.
     *
     * @param  array<string, mixed>  $oldValues
     * @param  array<string, mixed>  $newValues
     */
    public function logUpdated(
        string $entityType,
        string|int $entityId,
        array $oldValues,
        array $newValues,
        ?AuditContext $context = null,
    ): bool {
        if (! $this->enabled || ! $this->isEntityEnabled($entityType)) {
            return false;
        }

        return $this->logger->logUpdated($entityType, $entityId, $oldValues, $newValues, $context);
    }

    /**
     * Log a deleted event.
     *
     * @param  array<string, mixed>  $values
     */
    public function logDeleted(
        string $entityType,
        string|int $entityId,
        array $values,
        ?AuditContext $context = null,
    ): bool {
        if (! $this->enabled || ! $this->isEntityEnabled($entityType)) {
            return false;
        }

        return $this->logger->logDeleted($entityType, $entityId, $values, $context);
    }

    /**
     * Start a query for a specific entity.
     */
    public function for(string $entityType, string|int $entityId): self
    {
        $this->resetQuery();
        $this->queryEntityType = $entityType;
        $this->queryEntityId = $entityId;

        return $this;
    }

    /**
     * Start a query for an entity type.
     */
    public function forEntity(string $entityType): self
    {
        $this->resetQuery();
        $this->queryEntityType = $entityType;

        return $this;
    }

    /**
     * Start a query for a specific user.
     */
    public function byUser(string|int $userId, ?string $userType = null): self
    {
        $this->resetQuery();
        $this->queryUserId = $userId;
        $this->queryUserType = $userType;

        return $this;
    }

    /**
     * Filter by date.
     */
    public function since(string $date): self
    {
        $this->querySince = $date;

        return $this;
    }

    /**
     * Limit results.
     */
    public function limit(int $limit): self
    {
        $this->queryLimit = $limit;

        return $this;
    }

    /**
     * Execute the query and get results.
     *
     * @return array<int, AuditEntry>
     */
    public function get(): array
    {
        $driver = $this->logger->getDriver();

        if ($this->queryEntityType !== null && $this->queryEntityId !== null) {
            return $driver->getForEntity($this->queryEntityType, $this->queryEntityId);
        }

        if ($this->queryUserId !== null) {
            return $driver->getByUser($this->queryUserId, $this->queryUserType);
        }

        if ($this->queryEntityType !== null) {
            return $driver->getByEntityType($this->queryEntityType, $this->querySince, $this->queryLimit);
        }

        return [];
    }

    /**
     * Get the first result.
     */
    public function first(): ?AuditEntry
    {
        $this->queryLimit = 1;
        $results = $this->get();

        return $results[0] ?? null;
    }

    /**
     * Get statistics for an entity type.
     *
     * @return array{created: int, updated: int, deleted: int, total: int}
     */
    public function stats(string $entityType): array
    {
        $entries = $this->forEntity($entityType)->get();

        $stats = [
            'created' => 0,
            'updated' => 0,
            'deleted' => 0,
            'total' => count($entries),
        ];

        foreach ($entries as $entry) {
            if ($entry->isCreated()) {
                $stats['created']++;
            } elseif ($entry->isUpdated()) {
                $stats['updated']++;
            } elseif ($entry->isDeleted()) {
                $stats['deleted']++;
            }
        }

        return $stats;
    }

    /**
     * Prune old audit entries.
     */
    public function prune(?int $days = null): int
    {
        $days = $days ?? (int) config('laravel-toolkit.audit.retention.days', 365);

        return $this->logger->getDriver()->prune($days);
    }

    /**
     * Get the logger instance.
     */
    public function getLogger(): AuditLogger
    {
        return $this->logger;
    }

    /**
     * Get the driver instance.
     */
    public function getDriver(): AuditDriverInterface
    {
        return $this->logger->getDriver();
    }

    /**
     * Disable event dispatching for this service.
     */
    public function withoutEvents(): self
    {
        $this->logger->withoutEvents();

        return $this;
    }

    /**
     * Check if auditing is enabled for a specific entity.
     */
    private function isEntityEnabled(string $entityType): bool
    {
        $entityConfig = config("laravel-toolkit.audit.entities.{$entityType}", []);

        if (empty($entityConfig)) {
            return true; // Default to enabled if not configured
        }

        return $entityConfig['enabled'] ?? true;
    }

    /**
     * Reset query builder state.
     */
    private function resetQuery(): void
    {
        $this->queryEntityType = null;
        $this->queryEntityId = null;
        $this->queryUserId = null;
        $this->queryUserType = null;
        $this->querySince = null;
        $this->queryLimit = null;
    }
}
