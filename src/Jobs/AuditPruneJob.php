<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use SapB1\Toolkit\Audit\AuditService;

/**
 * Queueable job for pruning old audit log entries.
 *
 * Can be scheduled in Laravel's console kernel for automatic cleanup.
 *
 * @example
 * ```php
 * // Dispatch prune job (uses config retention days)
 * AuditPruneJob::dispatch();
 *
 * // Dispatch with custom retention days
 * AuditPruneJob::dispatch(days: 90);
 *
 * // Schedule in Console Kernel
 * $schedule->job(new AuditPruneJob)->daily();
 * $schedule->job(new AuditPruneJob(days: 30))->weekly();
 * ```
 */
final class AuditPruneJob implements ShouldQueue
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
     * Create a new job instance.
     */
    public function __construct(
        public readonly ?int $days = null,
    ) {}

    /**
     * Execute the job.
     */
    public function handle(AuditService $auditService): void
    {
        if (! $auditService->isEnabled()) {
            Log::info('[AuditPruneJob] Audit logging is disabled, skipping prune.');

            return;
        }

        $days = $this->days ?? (int) config('laravel-toolkit.audit.retention.days', 365);

        Log::info("[AuditPruneJob] Starting prune for entries older than {$days} days");

        $deleted = $auditService->prune($days);

        Log::info("[AuditPruneJob] Pruned {$deleted} audit log entries");
    }

    /**
     * Handle a job failure.
     */
    public function failed(?\Throwable $exception): void
    {
        Log::error('[AuditPruneJob] Job failed', [
            'days' => $this->days,
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
            'audit',
            'prune',
        ];
    }
}
