<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Audit;

use SapB1\Toolkit\Audit\Contracts\AuditDriverInterface;
use SapB1\Toolkit\Audit\Drivers\DatabaseDriver;
use SapB1\Toolkit\Audit\Drivers\LogDriver;
use SapB1\Toolkit\Audit\Drivers\NullDriver;
use SapB1\Toolkit\Audit\Events\AuditFailed;
use SapB1\Toolkit\Audit\Events\AuditRecorded;
use SapB1\Toolkit\Audit\Exceptions\AuditException;
use Throwable;

/**
 * Core audit logger that handles storing audit entries.
 */
final class AuditLogger
{
    private AuditDriverInterface $driver;

    private bool $dispatchEvents;

    /** @var array<int, string> */
    private array $globalExclude;

    public function __construct(?AuditDriverInterface $driver = null)
    {
        $this->driver = $driver ?? $this->resolveDriver();
        $this->dispatchEvents = (bool) config('laravel-toolkit.audit.dispatch_events', true);
        $this->globalExclude = (array) config('laravel-toolkit.audit.exclude', []);
    }

    /**
     * Log an audit entry.
     */
    public function log(AuditEntry $entry): bool
    {
        // Filter out excluded fields
        $entry = $this->filterExcludedFields($entry);

        try {
            $result = $this->driver->store($entry);

            if ($result && $this->dispatchEvents) {
                AuditRecorded::dispatch($entry, $this->driver->getName());
            }

            return $result;
        } catch (Throwable $e) {
            if ($this->dispatchEvents) {
                AuditFailed::dispatch($entry, $this->driver->getName(), $e);
            }

            // Log to Laravel log as fallback
            \Illuminate\Support\Facades\Log::error('[SAP Audit] Failed to store audit log', [
                'entity_type' => $entry->entityType,
                'entity_id' => $entry->entityId,
                'event' => $entry->event,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
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
        return $this->log(AuditEntry::created(
            entityType: $entityType,
            entityId: $entityId,
            values: $values,
            context: $context,
        ));
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
        return $this->log(AuditEntry::updated(
            entityType: $entityType,
            entityId: $entityId,
            oldValues: $oldValues,
            newValues: $newValues,
            context: $context,
        ));
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
        return $this->log(AuditEntry::deleted(
            entityType: $entityType,
            entityId: $entityId,
            values: $values,
            context: $context,
        ));
    }

    /**
     * Log a custom event.
     *
     * @param  array<string, mixed>|null  $oldValues
     * @param  array<string, mixed>|null  $newValues
     */
    public function logCustom(
        string $entityType,
        string|int $entityId,
        string $event,
        ?array $oldValues = null,
        ?array $newValues = null,
        ?AuditContext $context = null,
    ): bool {
        return $this->log(AuditEntry::custom(
            entityType: $entityType,
            entityId: $entityId,
            event: $event,
            oldValues: $oldValues,
            newValues: $newValues,
            context: $context,
        ));
    }

    /**
     * Get the driver instance.
     */
    public function getDriver(): AuditDriverInterface
    {
        return $this->driver;
    }

    /**
     * Set the driver instance.
     */
    public function setDriver(AuditDriverInterface $driver): self
    {
        $this->driver = $driver;

        return $this;
    }

    /**
     * Disable event dispatching.
     */
    public function withoutEvents(): self
    {
        $this->dispatchEvents = false;

        return $this;
    }

    /**
     * Enable event dispatching.
     */
    public function withEvents(): self
    {
        $this->dispatchEvents = true;

        return $this;
    }

    /**
     * Add fields to global exclude list.
     *
     * @param  array<int, string>  $fields
     */
    public function exclude(array $fields): self
    {
        $this->globalExclude = array_merge($this->globalExclude, $fields);

        return $this;
    }

    /**
     * Filter out excluded fields from an audit entry.
     */
    private function filterExcludedFields(AuditEntry $entry): AuditEntry
    {
        if (empty($this->globalExclude)) {
            return $entry;
        }

        $oldValues = $entry->oldValues;
        $newValues = $entry->newValues;
        $changedFields = $entry->changedFields;

        if ($oldValues !== null) {
            $oldValues = array_diff_key($oldValues, array_flip($this->globalExclude));
        }

        if ($newValues !== null) {
            $newValues = array_diff_key($newValues, array_flip($this->globalExclude));
        }

        if ($changedFields !== null) {
            $changedFields = array_diff($changedFields, $this->globalExclude);
        }

        return new AuditEntry(
            entityType: $entry->entityType,
            entityId: $entry->entityId,
            event: $entry->event,
            oldValues: $oldValues,
            newValues: $newValues,
            changedFields: $changedFields !== null ? array_values($changedFields) : null,
            context: $entry->context,
            createdAt: $entry->createdAt,
            id: $entry->id,
        );
    }

    /**
     * Resolve the configured driver.
     */
    private function resolveDriver(): AuditDriverInterface
    {
        $driverName = config('laravel-toolkit.audit.driver', 'database');

        return match ($driverName) {
            'database' => new DatabaseDriver,
            'log' => new LogDriver,
            'null' => new NullDriver,
            default => $this->resolveCustomDriver($driverName),
        };
    }

    /**
     * Resolve a custom driver class.
     */
    private function resolveCustomDriver(string $driverClass): AuditDriverInterface
    {
        if (! class_exists($driverClass)) {
            throw AuditException::invalidDriver($driverClass);
        }

        $driver = app($driverClass);

        if (! $driver instanceof AuditDriverInterface) {
            throw AuditException::invalidDriver($driverClass);
        }

        return $driver;
    }
}
