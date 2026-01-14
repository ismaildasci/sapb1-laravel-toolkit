<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;

/**
 * Eloquent model for SAP audit logs.
 *
 * @property int $id
 * @property string $auditable_type
 * @property string $auditable_id
 * @property string $event
 * @property array<string, mixed>|null $old_values
 * @property array<string, mixed>|null $new_values
 * @property array<int, string>|null $changed_fields
 * @property string|null $user_id
 * @property string|null $user_type
 * @property string|null $ip_address
 * @property string|null $user_agent
 * @property string|null $tenant_id
 * @property array<string, mixed>|null $metadata
 * @property \Illuminate\Support\Carbon $created_at
 *
 * @method static Builder<static> forEntityType(string $entityType)
 * @method static Builder<static> forEntity(string $entityType, string|int $entityId)
 * @method static Builder<static> byUser(string|int $userId)
 * @method static Builder<static> forTenant(string $tenantId)
 * @method static Builder<static> ofEvent(string $event)
 * @method static Builder<static> since(string $date)
 * @method static Builder<static> until(string $date)
 * @method static Builder<static> between(string $start, string $end)
 */
class AuditLog extends Model
{
    use Prunable;

    /**
     * Indicates if the model should be timestamped.
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'auditable_type',
        'auditable_id',
        'event',
        'old_values',
        'new_values',
        'changed_fields',
        'user_id',
        'user_type',
        'ip_address',
        'user_agent',
        'tenant_id',
        'metadata',
        'created_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'changed_fields' => 'array',
        'metadata' => 'array',
        'created_at' => 'datetime',
    ];

    /**
     * Get the table associated with the model.
     */
    public function getTable(): string
    {
        return config('laravel-toolkit.audit.table', 'sap_audit_logs');
    }

    /**
     * Get the prunable model query.
     *
     * @return Builder<static>
     */
    public function prunable(): Builder
    {
        $days = (int) config('laravel-toolkit.audit.retention.days', 365);

        /** @phpstan-ignore argument.type, return.type */
        return static::where('created_at', '<', now()->subDays($days));
    }

    /**
     * Scope to filter by entity type.
     *
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopeForEntityType(Builder $query, string $entityType): Builder
    {
        /** @phpstan-ignore argument.type */
        return $query->where('auditable_type', $entityType);
    }

    /**
     * Scope to filter by entity.
     *
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopeForEntity(Builder $query, string $entityType, string|int $entityId): Builder
    {
        /** @phpstan-ignore argument.type */
        return $query->where('auditable_type', $entityType)
            ->where('auditable_id', (string) $entityId);
    }

    /**
     * Scope to filter by user.
     *
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopeByUser(Builder $query, string|int $userId): Builder
    {
        /** @phpstan-ignore argument.type */
        return $query->where('user_id', (string) $userId);
    }

    /**
     * Scope to filter by tenant.
     *
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopeForTenant(Builder $query, string $tenantId): Builder
    {
        /** @phpstan-ignore argument.type */
        return $query->where('tenant_id', $tenantId);
    }

    /**
     * Scope to filter by event type.
     *
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopeOfEvent(Builder $query, string $event): Builder
    {
        /** @phpstan-ignore argument.type */
        return $query->where('event', $event);
    }

    /**
     * Scope to filter by date range.
     *
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopeSince(Builder $query, string $date): Builder
    {
        /** @phpstan-ignore argument.type */
        return $query->where('created_at', '>=', $date);
    }

    /**
     * Scope to filter by date range.
     *
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopeUntil(Builder $query, string $date): Builder
    {
        /** @phpstan-ignore argument.type */
        return $query->where('created_at', '<=', $date);
    }

    /**
     * Scope to filter by date range.
     *
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopeBetween(Builder $query, string $start, string $end): Builder
    {
        /** @phpstan-ignore argument.type */
        return $query->whereBetween('created_at', [$start, $end]);
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
     * Get the old value of a field.
     */
    public function getOldValue(string $field): mixed
    {
        return $this->old_values[$field] ?? null;
    }

    /**
     * Get the new value of a field.
     */
    public function getNewValue(string $field): mixed
    {
        return $this->new_values[$field] ?? null;
    }

    /**
     * Check if a field was changed.
     */
    public function fieldWasChanged(string $field): bool
    {
        return in_array($field, $this->changed_fields ?? [], true);
    }

    /**
     * Get the number of changed fields.
     */
    public function changedFieldsCount(): int
    {
        return count($this->changed_fields ?? []);
    }
}
