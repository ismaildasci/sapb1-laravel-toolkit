<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Audit;

use DateTimeImmutable;
use DateTimeInterface;

/**
 * Value object representing a single audit log entry.
 */
final readonly class AuditEntry
{
    public function __construct(
        public string $entityType,
        public string|int $entityId,
        public string $event,
        /** @var array<string, mixed>|null */
        public ?array $oldValues = null,
        /** @var array<string, mixed>|null */
        public ?array $newValues = null,
        /** @var array<int, string>|null */
        public ?array $changedFields = null,
        public ?AuditContext $context = null,
        public ?DateTimeInterface $createdAt = null,
        public ?int $id = null,
    ) {}

    /**
     * Create entry for a "created" event.
     *
     * @param  array<string, mixed>  $values
     */
    public static function created(
        string $entityType,
        string|int $entityId,
        array $values,
        ?AuditContext $context = null,
    ): self {
        return new self(
            entityType: $entityType,
            entityId: $entityId,
            event: 'created',
            oldValues: null,
            newValues: $values,
            changedFields: array_keys($values),
            context: $context ?? AuditContext::capture(),
            createdAt: new DateTimeImmutable,
        );
    }

    /**
     * Create entry for an "updated" event.
     *
     * @param  array<string, mixed>  $oldValues
     * @param  array<string, mixed>  $newValues
     */
    public static function updated(
        string $entityType,
        string|int $entityId,
        array $oldValues,
        array $newValues,
        ?AuditContext $context = null,
    ): self {
        $changedFields = array_keys(array_diff_assoc($newValues, $oldValues));

        return new self(
            entityType: $entityType,
            entityId: $entityId,
            event: 'updated',
            oldValues: $oldValues,
            newValues: $newValues,
            changedFields: $changedFields,
            context: $context ?? AuditContext::capture(),
            createdAt: new DateTimeImmutable,
        );
    }

    /**
     * Create entry for a "deleted" event.
     *
     * @param  array<string, mixed>  $values
     */
    public static function deleted(
        string $entityType,
        string|int $entityId,
        array $values,
        ?AuditContext $context = null,
    ): self {
        return new self(
            entityType: $entityType,
            entityId: $entityId,
            event: 'deleted',
            oldValues: $values,
            newValues: null,
            changedFields: array_keys($values),
            context: $context ?? AuditContext::capture(),
            createdAt: new DateTimeImmutable,
        );
    }

    /**
     * Create a custom event entry.
     *
     * @param  array<string, mixed>|null  $oldValues
     * @param  array<string, mixed>|null  $newValues
     */
    public static function custom(
        string $entityType,
        string|int $entityId,
        string $event,
        ?array $oldValues = null,
        ?array $newValues = null,
        ?AuditContext $context = null,
    ): self {
        $changedFields = [];

        if ($oldValues !== null && $newValues !== null) {
            $changedFields = array_keys(array_diff_assoc($newValues, $oldValues));
        } elseif ($newValues !== null) {
            $changedFields = array_keys($newValues);
        } elseif ($oldValues !== null) {
            $changedFields = array_keys($oldValues);
        }

        return new self(
            entityType: $entityType,
            entityId: $entityId,
            event: $event,
            oldValues: $oldValues,
            newValues: $newValues,
            changedFields: $changedFields,
            context: $context ?? AuditContext::capture(),
            createdAt: new DateTimeImmutable,
        );
    }

    /**
     * Check if this is a create event.
     */
    public function isCreated(): bool
    {
        return $this->event === 'created';
    }

    /**
     * Check if this is an update event.
     */
    public function isUpdated(): bool
    {
        return $this->event === 'updated';
    }

    /**
     * Check if this is a delete event.
     */
    public function isDeleted(): bool
    {
        return $this->event === 'deleted';
    }

    /**
     * Get the number of changed fields.
     */
    public function changedFieldsCount(): int
    {
        return count($this->changedFields ?? []);
    }

    /**
     * Check if a specific field was changed.
     */
    public function hasChangedField(string $field): bool
    {
        return in_array($field, $this->changedFields ?? [], true);
    }

    /**
     * Get the old value of a specific field.
     */
    public function getOldValue(string $field): mixed
    {
        return $this->oldValues[$field] ?? null;
    }

    /**
     * Get the new value of a specific field.
     */
    public function getNewValue(string $field): mixed
    {
        return $this->newValues[$field] ?? null;
    }

    /**
     * Convert to array for storage.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'auditable_type' => $this->entityType,
            'auditable_id' => (string) $this->entityId,
            'event' => $this->event,
            'old_values' => $this->oldValues,
            'new_values' => $this->newValues,
            'changed_fields' => $this->changedFields,
            'user_id' => $this->context?->userId,
            'user_type' => $this->context?->userType,
            'ip_address' => $this->context?->ipAddress,
            'user_agent' => $this->context?->userAgent,
            'tenant_id' => $this->context?->tenantId,
            'metadata' => $this->context !== null ? $this->context->metadata : [],
            'created_at' => $this->createdAt?->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * Create from array (database record).
     *
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        $context = new AuditContext(
            userId: $data['user_id'] ?? null,
            userType: $data['user_type'] ?? null,
            ipAddress: $data['ip_address'] ?? null,
            userAgent: $data['user_agent'] ?? null,
            tenantId: $data['tenant_id'] ?? null,
            metadata: $data['metadata'] ?? [],
        );

        $createdAt = null;
        if (isset($data['created_at'])) {
            $createdAt = $data['created_at'] instanceof DateTimeInterface
                ? $data['created_at']
                : new DateTimeImmutable($data['created_at']);
        }

        return new self(
            entityType: $data['auditable_type'],
            entityId: $data['auditable_id'],
            event: $data['event'],
            oldValues: $data['old_values'] ?? null,
            newValues: $data['new_values'] ?? null,
            changedFields: $data['changed_fields'] ?? null,
            context: $context,
            createdAt: $createdAt,
            id: $data['id'] ?? null,
        );
    }
}
