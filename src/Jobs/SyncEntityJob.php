<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use SapB1\Toolkit\Sync\LocalSyncService;
use SapB1\Toolkit\Sync\SyncResult;

/**
 * Queueable job for syncing SAP B1 entities to local database.
 *
 * @example
 * ```php
 * // Dispatch incremental sync
 * SyncEntityJob::dispatch('Items');
 *
 * // Dispatch full sync with delete detection
 * SyncEntityJob::dispatch('Items', fullSync: true);
 *
 * // Dispatch sync since specific date
 * SyncEntityJob::dispatch('Orders', since: '2026-01-01');
 *
 * // Dispatch to specific queue
 * SyncEntityJob::dispatch('Items')->onQueue('sync');
 *
 * // Dispatch multiple entities
 * foreach (['Items', 'BusinessPartners', 'Orders'] as $entity) {
 *     SyncEntityJob::dispatch($entity);
 * }
 * ```
 */
final class SyncEntityJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * The number of times the job may be attempted.
     */
    public int $tries = 3;

    /**
     * The number of seconds to wait before retrying the job.
     */
    public int $backoff = 60;

    /**
     * The maximum number of unhandled exceptions to allow before failing.
     */
    public int $maxExceptions = 2;

    /**
     * The SAP B1 connection name to use.
     */
    public readonly ?string $sapConnection;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public readonly string $entity,
        public readonly bool $fullSync = false,
        public readonly ?string $since = null,
        ?string $sapConnection = null,
    ) {
        $this->sapConnection = $sapConnection;
    }

    /**
     * Execute the job.
     */
    public function handle(LocalSyncService $syncService): void
    {
        Log::info("[SyncEntityJob] Starting sync for {$this->entity}", [
            'entity' => $this->entity,
            'full_sync' => $this->fullSync,
            'since' => $this->since,
            'sap_connection' => $this->sapConnection,
            'attempt' => $this->attempts(),
        ]);

        if ($this->sapConnection !== null) {
            $syncService->connection($this->sapConnection);
        }

        $result = $this->executeSync($syncService);

        if ($result->failed) {
            Log::error("[SyncEntityJob] Sync failed for {$this->entity}", [
                'entity' => $this->entity,
                'error' => $result->error,
                'duration' => $result->duration,
            ]);

            $this->fail($result->error);

            return;
        }

        Log::info("[SyncEntityJob] Sync completed for {$this->entity}", [
            'entity' => $this->entity,
            'created' => $result->created,
            'updated' => $result->updated,
            'deleted' => $result->deleted,
            'duration' => round($result->duration, 2),
        ]);
    }

    /**
     * Execute the appropriate sync operation.
     */
    private function executeSync(LocalSyncService $syncService): SyncResult
    {
        if ($this->fullSync) {
            return $syncService->fullSyncWithDeletes($this->entity);
        }

        if ($this->since !== null) {
            return $syncService->syncSince($this->entity, $this->since);
        }

        return $syncService->sync($this->entity);
    }

    /**
     * Handle a job failure.
     */
    public function failed(?\Throwable $exception): void
    {
        Log::error("[SyncEntityJob] Job failed for {$this->entity}", [
            'entity' => $this->entity,
            'exception' => $exception?->getMessage(),
            'attempts' => $this->attempts(),
        ]);
    }

    /**
     * Get the tags that should be assigned to the job.
     *
     * @return array<int, string>
     */
    public function tags(): array
    {
        return [
            'sync',
            "entity:{$this->entity}",
            $this->fullSync ? 'full-sync' : 'incremental-sync',
        ];
    }

    /**
     * Determine the time at which the job should timeout.
     */
    public function retryUntil(): \DateTime
    {
        return now()->addHours(1);
    }
}
