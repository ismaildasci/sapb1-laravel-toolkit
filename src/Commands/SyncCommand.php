<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use SapB1\Toolkit\Services\SyncService;
use SapB1\Toolkit\Sync\LocalSyncService;
use SapB1\Toolkit\Sync\SyncConfig;
use SapB1\Toolkit\Sync\SyncRegistry;

class SyncCommand extends Command
{
    protected $signature = 'sapb1:sync
                            {entities?* : The entities to sync}
                            {--full : Perform a full sync with delete detection}
                            {--since= : Sync records updated since this date (Y-m-d)}
                            {--all : Sync all configured entities}
                            {--local : Sync to local database (default if tables exist)}
                            {--no-local : Skip local database sync}
                            {--connection=default : The SAP B1 connection to use}';

    protected $description = 'Sync data from SAP Business One to local database';

    public function handle(SyncService $sapSyncService, LocalSyncService $localSyncService, SyncRegistry $registry): int
    {
        /** @var string $connection */
        $connection = $this->option('connection') ?? 'default';
        $full = (bool) $this->option('full');
        /** @var string|null $since */
        $since = $this->option('since');

        $localSyncService->connection($connection);
        $sapSyncService->connection($connection);

        // Determine entities to sync
        $entities = $this->getEntitiesToSync($registry);

        if (empty($entities)) {
            $this->warn('No entities to sync.');

            return self::SUCCESS;
        }

        $this->info('Starting SAP B1 sync...');
        $this->newLine();

        $totalCreated = 0;
        $totalUpdated = 0;
        $totalDeleted = 0;

        foreach ($entities as $entity) {
            $this->syncEntity(
                $entity,
                $localSyncService,
                $sapSyncService,
                $full,
                $since,
                $totalCreated,
                $totalUpdated,
                $totalDeleted
            );
        }

        $this->newLine();
        $this->info('Sync completed!');
        $this->components->twoColumnDetail('Created', (string) $totalCreated);
        $this->components->twoColumnDetail('Updated', (string) $totalUpdated);

        if ($totalDeleted > 0) {
            $this->components->twoColumnDetail('Deleted', (string) $totalDeleted);
        }

        return self::SUCCESS;
    }

    /**
     * Get entities to sync.
     *
     * @return array<string>
     */
    private function getEntitiesToSync(SyncRegistry $registry): array
    {
        /** @var array<string> $entities */
        $entities = $this->argument('entities');

        if (! empty($entities)) {
            return $this->validateEntities($entities);
        }

        if ($this->option('all')) {
            // Return all entities with tables
            return $registry->entitiesWithTables();
        }

        // Interactive - show entities with tables
        $available = $registry->entitiesWithTables();

        if (empty($available)) {
            $this->error("No sync tables found. Run 'php artisan sapb1:sync-setup' first.");

            return [];
        }

        return $available;
    }

    /**
     * Validate entities.
     *
     * @param  array<string>  $entities
     * @return array<string>
     */
    private function validateEntities(array $entities): array
    {
        $valid = [];
        $available = SyncConfig::availableEntities();

        foreach ($entities as $entity) {
            if (in_array($entity, $available, true)) {
                $valid[] = $entity;
            } else {
                $this->warn("Unknown entity: {$entity}");
            }
        }

        return $valid;
    }

    /**
     * Sync a single entity.
     */
    private function syncEntity(
        string $entity,
        LocalSyncService $localSyncService,
        SyncService $sapSyncService,
        bool $full,
        ?string $since,
        int &$totalCreated,
        int &$totalUpdated,
        int &$totalDeleted,
    ): void {
        $config = SyncConfig::for($entity);

        if ($config === null) {
            $this->warn("No configuration for: {$entity}");

            return;
        }

        $hasTable = Schema::hasTable($config->table);
        $useLocalSync = $hasTable && ! $this->option('no-local');

        $this->components->task($entity, function () use (
            $entity,
            $localSyncService,
            $sapSyncService,
            $full,
            $since,
            $useLocalSync,
            &$totalCreated,
            &$totalUpdated,
            &$totalDeleted,
        ) {
            if ($useLocalSync) {
                // Use local database sync
                if ($since !== null) {
                    $result = $localSyncService->syncSince($entity, $since);
                } elseif ($full) {
                    $result = $localSyncService->fullSyncWithDeletes($entity);
                } else {
                    $result = $localSyncService->sync($entity);
                }

                if ($result->hasFailed()) {
                    throw new \RuntimeException($result->error ?? 'Unknown error');
                }

                $totalCreated += $result->created;
                $totalUpdated += $result->updated;
                $totalDeleted += $result->deleted;
            } else {
                // Fallback to callback-based sync (no local DB)
                $callback = static function (array $_): void {
                    // Just fetch, no storage
                };

                if ($full) {
                    $count = $sapSyncService->fullSync($entity, $callback);
                } else {
                    $count = $sapSyncService->incrementalSync($entity, $callback);
                }

                $totalUpdated += $count;
            }
        });
    }
}
