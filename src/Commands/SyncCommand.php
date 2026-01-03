<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Commands;

use Illuminate\Console\Command;
use SapB1\Toolkit\Services\SyncService;

class SyncCommand extends Command
{
    protected $signature = 'sapb1:sync
                            {entity : The entity to sync (Items, BusinessPartners, Orders, Invoices)}
                            {--full : Perform a full sync instead of incremental}
                            {--connection=default : The SAP B1 connection to use}';

    protected $description = 'Sync data from SAP Business One';

    public function handle(SyncService $syncService): int
    {
        /** @var string $entity */
        $entity = $this->argument('entity');
        /** @var string $connection */
        $connection = $this->option('connection') ?? 'default';
        $full = (bool) $this->option('full');

        $syncService->connection($connection);

        $this->info("Starting sync for {$entity}...");

        $callback = function (array $record) use ($entity): void {
            $id = $record['DocEntry'] ?? $record['ItemCode'] ?? $record['CardCode'] ?? 'unknown';
            $this->line("  Synced: {$entity} #{$id}");
        };

        if ($full) {
            $count = $syncService->fullSync($entity, $callback);
        } else {
            $count = $syncService->incrementalSync($entity, $callback);
        }

        $this->info("Sync completed. {$count} records processed.");

        return self::SUCCESS;
    }
}
