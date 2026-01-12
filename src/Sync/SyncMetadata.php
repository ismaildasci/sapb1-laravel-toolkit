<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Sync;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Eloquent model for tracking sync metadata.
 *
 * @property int $id
 * @property string $entity
 * @property Carbon|null $last_synced_at
 * @property string|null $last_cursor
 * @property int $synced_count
 * @property string $status
 * @property array<string, mixed>|null $options
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class SyncMetadata extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sapb1_sync_metadata';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'entity',
        'last_synced_at',
        'last_cursor',
        'synced_count',
        'status',
        'options',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'last_synced_at' => 'datetime',
            'synced_count' => 'integer',
            'options' => 'array',
        ];
    }

    /**
     * Status constants.
     */
    public const STATUS_IDLE = 'idle';

    public const STATUS_RUNNING = 'running';

    public const STATUS_FAILED = 'failed';

    public const STATUS_COMPLETED = 'completed';

    /**
     * Find or create metadata for an entity.
     */
    public static function findOrCreateFor(string $entity): self
    {
        return self::firstOrCreate(
            ['entity' => $entity],
            [
                'synced_count' => 0,
                'status' => self::STATUS_IDLE,
            ]
        );
    }

    /**
     * Get metadata for an entity.
     */
    public static function findFor(string $entity): ?self
    {
        return self::where('entity', $entity)->first();
    }

    /**
     * Check if sync is currently running for an entity.
     */
    public static function isRunning(string $entity): bool
    {
        $metadata = self::findFor($entity);

        return $metadata !== null && $metadata->status === self::STATUS_RUNNING;
    }

    /**
     * Mark sync as started.
     */
    public function markAsRunning(): self
    {
        $this->status = self::STATUS_RUNNING;
        $this->save();

        return $this;
    }

    /**
     * Mark sync as completed.
     */
    public function markAsCompleted(SyncResult $result): self
    {
        $this->status = self::STATUS_COMPLETED;
        $this->last_synced_at = now();
        $this->synced_count += $result->synced();
        $this->save();

        return $this;
    }

    /**
     * Mark sync as failed.
     */
    public function markAsFailed(?string $error = null): self
    {
        $this->status = self::STATUS_FAILED;

        if ($error !== null) {
            $options = $this->options ?? [];
            $options['last_error'] = $error;
            $options['last_error_at'] = now()->toIso8601String();
            $this->options = $options;
        }

        $this->save();

        return $this;
    }

    /**
     * Reset sync status to idle.
     */
    public function reset(): self
    {
        $this->status = self::STATUS_IDLE;
        $this->save();

        return $this;
    }

    /**
     * Update the cursor value.
     */
    public function updateCursor(string $cursor): self
    {
        $this->last_cursor = $cursor;
        $this->save();

        return $this;
    }

    /**
     * Get option value.
     */
    public function getOption(string $key, mixed $default = null): mixed
    {
        return $this->options[$key] ?? $default;
    }

    /**
     * Set option value.
     */
    public function setOption(string $key, mixed $value): self
    {
        $options = $this->options ?? [];
        $options[$key] = $value;
        $this->options = $options;
        $this->save();

        return $this;
    }

    /**
     * Check if entity has ever been synced.
     */
    public function hasBeenSynced(): bool
    {
        return $this->last_synced_at !== null;
    }

    /**
     * Get time since last sync.
     */
    public function timeSinceLastSync(): ?string
    {
        if ($this->last_synced_at === null) {
            return null;
        }

        return $this->last_synced_at->diffForHumans();
    }

    /**
     * Scope to get only idle entities.
     *
     * @param  \Illuminate\Database\Eloquent\Builder<self>  $query
     * @return \Illuminate\Database\Eloquent\Builder<self>
     */
    public function scopeIdle($query)
    {
        return $query->where('status', self::STATUS_IDLE);
    }

    /**
     * Scope to get only running entities.
     *
     * @param  \Illuminate\Database\Eloquent\Builder<self>  $query
     * @return \Illuminate\Database\Eloquent\Builder<self>
     */
    public function scopeRunning($query)
    {
        return $query->where('status', self::STATUS_RUNNING);
    }

    /**
     * Scope to get only failed entities.
     *
     * @param  \Illuminate\Database\Eloquent\Builder<self>  $query
     * @return \Illuminate\Database\Eloquent\Builder<self>
     */
    public function scopeFailed($query)
    {
        return $query->where('status', self::STATUS_FAILED);
    }
}
