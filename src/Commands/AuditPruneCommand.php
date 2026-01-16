<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Commands;

use Illuminate\Console\Command;
use SapB1\Toolkit\Audit\AuditService;

class AuditPruneCommand extends Command
{
    protected $signature = 'sapb1:audit-prune
                            {--days= : Number of days to keep (default: from config)}
                            {--dry-run : Show what would be deleted without actually deleting}
                            {--force : Skip confirmation prompt}';

    protected $description = 'Prune old audit log entries';

    public function handle(AuditService $auditService): int
    {
        if (! $auditService->isEnabled()) {
            $this->warn('Audit logging is disabled.');

            return self::SUCCESS;
        }

        /** @var int|null $days */
        $days = $this->option('days');
        $dryRun = (bool) $this->option('dry-run');
        $force = (bool) $this->option('force');

        $retentionDays = $days ?? (int) config('laravel-toolkit.audit.retention.days', 365);

        $this->info("Pruning audit logs older than {$retentionDays} days...");

        if ($dryRun) {
            $count = $this->countOldEntries($auditService, $retentionDays);
            $this->info("Dry run: Would delete {$count} entries.");

            return self::SUCCESS;
        }

        if (! $force && ! $this->confirm("This will permanently delete audit logs older than {$retentionDays} days. Continue?")) {
            $this->info('Cancelled.');

            return self::SUCCESS;
        }

        $deleted = $auditService->prune($retentionDays);

        $this->info("Successfully pruned {$deleted} audit log entries.");

        return self::SUCCESS;
    }

    private function countOldEntries(AuditService $auditService, int $days): int
    {
        $driver = $auditService->getDriver();

        // Use reflection or direct count if available
        if (method_exists($driver, 'countOlderThan')) {
            return $driver->countOlderThan($days);
        }

        // Fallback: just run prune and report
        return 0;
    }
}
