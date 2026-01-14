<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Models\Concerns;

use Illuminate\Support\Facades\App;
use SapB1\Toolkit\Audit\AuditService;
use SapB1\Toolkit\Models\AuditLog;

/**
 * Trait for SAP B1 models that should be audited.
 *
 * Automatically logs create, update, and delete operations
 * when enabled.
 *
 * @example
 * ```php
 * class Order extends SapB1Model
 * {
 *     use HasAudit;
 *
 *     protected static bool $auditEnabled = true;
 *     protected array $auditExclude = ['Password', 'ApiKey'];
 * }
 *
 * // Get audit logs for a model
 * $order = Order::find(123);
 * $order->audits(); // All audit logs
 * $order->latestAudit(); // Most recent audit
 * ```
 *
 * @phpstan-require-extends \SapB1\Toolkit\Models\SapB1Model
 */
trait HasAudit
{
    /**
     * Audit exclude fields specific to this instance.
     *
     * @var array<int, string>
     */
    protected array $instanceAuditExclude = [];

    /**
     * Boot the trait.
     */
    public static function bootHasAudit(): void
    {
        if (! static::isAuditEnabled()) {
            return;
        }

        // After create
        static::created(function ($model): void {
            $model->auditCreated();
        });

        // After update
        static::updated(function ($model): void {
            $model->auditUpdated();
        });

        // Before delete (capture values before they're gone)
        static::deleting(function ($model): void {
            $model->auditDeleting();
        });
    }

    /**
     * Check if auditing is enabled for this model.
     */
    public static function isAuditEnabled(): bool
    {
        // Check model-level setting
        if (property_exists(static::class, 'auditEnabled')) {
            if (! static::$auditEnabled) {
                return false;
            }
        }

        // Check global config
        if (! config('laravel-toolkit.audit.enabled', true)) {
            return false;
        }

        // Check entity-specific config
        $entityConfig = config('laravel-toolkit.audit.entities.'.static::getEntity(), []);
        if (! empty($entityConfig) && isset($entityConfig['enabled'])) {
            return $entityConfig['enabled'];
        }

        return true;
    }

    /**
     * Get fields to exclude from audit logging.
     *
     * @return array<int, string>
     */
    public function getAuditExclude(): array
    {
        $exclude = [];

        // Model-level excludes
        if (property_exists($this, 'auditExclude')) {
            $exclude = array_merge($exclude, $this->auditExclude);
        }

        // Instance-level excludes
        $exclude = array_merge($exclude, $this->instanceAuditExclude);

        // Global excludes
        $exclude = array_merge($exclude, (array) config('laravel-toolkit.audit.exclude', []));

        // Entity-specific excludes
        $entityConfig = config('laravel-toolkit.audit.entities.'.static::getEntity(), []);
        if (! empty($entityConfig['exclude'])) {
            $exclude = array_merge($exclude, $entityConfig['exclude']);
        }

        return array_unique($exclude);
    }

    /**
     * Get fields to always include in audit logging.
     *
     * @return array<int, string>
     */
    public function getAuditInclude(): array
    {
        if (property_exists($this, 'auditInclude')) {
            return $this->auditInclude;
        }

        return [];
    }

    /**
     * Get the events that should trigger auditing.
     *
     * @return array<int, string>
     */
    public function getAuditEvents(): array
    {
        // Model-level events
        if (property_exists($this, 'auditEvents')) {
            return $this->auditEvents;
        }

        // Entity-specific events
        $entityConfig = config('laravel-toolkit.audit.entities.'.static::getEntity(), []);
        if (! empty($entityConfig['events'])) {
            return $entityConfig['events'];
        }

        return ['created', 'updated', 'deleted'];
    }

    /**
     * Get the entity identifier for auditing.
     */
    public function getAuditIdentifier(): string|int|null
    {
        return $this->getKey();
    }

    /**
     * Get the entity type name for auditing.
     */
    public function getAuditTypeName(): string
    {
        return static::getEntity();
    }

    /**
     * Add fields to exclude from audit for this instance.
     *
     * @param  array<int, string>  $fields
     */
    public function excludeFromAudit(array $fields): static
    {
        $this->instanceAuditExclude = array_merge($this->instanceAuditExclude, $fields);

        return $this;
    }

    /**
     * Get all audit logs for this model.
     *
     * @return \Illuminate\Database\Eloquent\Collection<int, AuditLog>
     */
    public function audits(): \Illuminate\Database\Eloquent\Collection
    {
        return AuditLog::forEntity($this->getAuditTypeName(), (string) $this->getAuditIdentifier())
            ->orderByDesc('created_at')
            ->get();
    }

    /**
     * Get the latest audit log for this model.
     */
    public function latestAudit(): ?AuditLog
    {
        return AuditLog::forEntity($this->getAuditTypeName(), (string) $this->getAuditIdentifier())
            ->orderByDesc('created_at')
            ->first();
    }

    /**
     * Get audit logs by a specific user.
     *
     * @return \Illuminate\Database\Eloquent\Collection<int, AuditLog>
     */
    public function auditedBy(string|int $userId): \Illuminate\Database\Eloquent\Collection
    {
        return AuditLog::forEntity($this->getAuditTypeName(), (string) $this->getAuditIdentifier())
            ->byUser($userId)
            ->orderByDesc('created_at')
            ->get();
    }

    /**
     * Get audit logs of a specific event type.
     *
     * @return \Illuminate\Database\Eloquent\Collection<int, AuditLog>
     */
    public function auditsOfEvent(string $event): \Illuminate\Database\Eloquent\Collection
    {
        return AuditLog::forEntity($this->getAuditTypeName(), (string) $this->getAuditIdentifier())
            ->ofEvent($event)
            ->orderByDesc('created_at')
            ->get();
    }

    /**
     * Manually log a custom audit event.
     *
     * @param  array<string, mixed>|null  $oldValues
     * @param  array<string, mixed>|null  $newValues
     */
    public function audit(string $event, ?array $oldValues = null, ?array $newValues = null): bool
    {
        $service = $this->getAuditService();

        if ($service === null) {
            return false;
        }

        return $service->log(
            entityType: $this->getAuditTypeName(),
            entityId: (string) $this->getAuditIdentifier(),
            event: $event,
            oldValues: $oldValues !== null ? $this->filterAuditValues($oldValues) : null,
            newValues: $newValues !== null ? $this->filterAuditValues($newValues) : null,
        );
    }

    /**
     * Log a created audit event.
     */
    protected function auditCreated(): void
    {
        if (! in_array('created', $this->getAuditEvents(), true)) {
            return;
        }

        $service = $this->getAuditService();

        if ($service === null) {
            return;
        }

        $service->logCreated(
            entityType: $this->getAuditTypeName(),
            entityId: (string) $this->getAuditIdentifier(),
            values: $this->filterAuditValues($this->getAttributes()),
        );
    }

    /**
     * Log an updated audit event.
     */
    protected function auditUpdated(): void
    {
        if (! in_array('updated', $this->getAuditEvents(), true)) {
            return;
        }

        $dirty = $this->getDirty();
        $original = $this->getOriginal();

        if (empty($dirty)) {
            return;
        }

        // Get only the changed fields from original
        $oldValues = [];
        foreach (array_keys($dirty) as $key) {
            $oldValues[$key] = $original[$key] ?? null;
        }

        $service = $this->getAuditService();

        if ($service === null) {
            return;
        }

        $service->logUpdated(
            entityType: $this->getAuditTypeName(),
            entityId: (string) $this->getAuditIdentifier(),
            oldValues: $this->filterAuditValues($oldValues),
            newValues: $this->filterAuditValues($dirty),
        );
    }

    /**
     * Log a deleting audit event.
     */
    protected function auditDeleting(): void
    {
        if (! in_array('deleted', $this->getAuditEvents(), true)) {
            return;
        }

        $service = $this->getAuditService();

        if ($service === null) {
            return;
        }

        $service->logDeleted(
            entityType: $this->getAuditTypeName(),
            entityId: (string) $this->getAuditIdentifier(),
            values: $this->filterAuditValues($this->getAttributes()),
        );
    }

    /**
     * Filter values to exclude sensitive fields.
     *
     * @param  array<string, mixed>  $values
     * @return array<string, mixed>
     */
    protected function filterAuditValues(array $values): array
    {
        $exclude = $this->getAuditExclude();

        if (empty($exclude)) {
            return $values;
        }

        return array_diff_key($values, array_flip($exclude));
    }

    /**
     * Get the audit service.
     */
    protected function getAuditService(): ?AuditService
    {
        if (! App::has(AuditService::class)) {
            return null;
        }

        return App::make(AuditService::class);
    }

    /**
     * Get the entity name.
     * Should be provided by the model class.
     */
    abstract public static function getEntity(): string;

    /**
     * Get the primary key value.
     * Should be provided by the model class.
     */
    abstract public function getKey(): int|string|null;

    /**
     * Get all attributes.
     * Should be provided by the model class.
     *
     * @return array<string, mixed>
     */
    abstract public function getAttributes(): array;

    /**
     * Get dirty attributes.
     * Should be provided by the model class.
     *
     * @return array<string, mixed>
     */
    abstract public function getDirty(): array;

    /**
     * Get original attributes.
     * Should be provided by the model class.
     *
     * @return array<string, mixed>
     */
    abstract public function getOriginal(): array;
}
