<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use SapB1\Toolkit\Sync\SyncConfig;
use SapB1\Toolkit\Sync\SyncMetadata;

class SyncStatusCommand extends Command
{
    protected $signature = 'sapb1:sync-status
                            {entity? : The entity to check status for}
                            {--json : Output as JSON}';

    protected $description = 'Show sync status for SAP B1 entities';

    public function handle(): int
    {
        // Check if metadata table exists
        if (! Schema::hasTable('sapb1_sync_metadata')) {
            $this->error("Sync metadata table does not exist. Run 'php artisan sapb1:sync-setup' first.");

            return self::FAILURE;
        }

        /** @var string|null $entity */
        $entity = $this->argument('entity');

        if ($entity !== null) {
            return $this->showEntityStatus($entity);
        }

        return $this->showAllStatus();
    }

    /**
     * Show status for a specific entity.
     */
    private function showEntityStatus(string $entity): int
    {
        $config = SyncConfig::for($entity);

        if ($config === null) {
            $this->error("Unknown entity: {$entity}");

            return self::FAILURE;
        }

        $metadata = SyncMetadata::findFor($entity);

        if ($this->option('json')) {
            $this->line((string) json_encode($this->buildEntityData($entity, $config, $metadata), JSON_PRETTY_PRINT));

            return self::SUCCESS;
        }

        $this->info("Sync Status: {$entity}");
        $this->newLine();

        $this->components->twoColumnDetail('Table', $config->table);
        $this->components->twoColumnDetail('Table Exists', Schema::hasTable($config->table) ? '<fg=green>Yes</>' : '<fg=red>No</>');
        $this->components->twoColumnDetail('Primary Key', $config->primaryKey);
        $this->components->twoColumnDetail('Columns', (string) count($config->columns));
        $this->components->twoColumnDetail('Sync Lines', $config->syncLines ? 'Yes' : 'No');

        $this->newLine();

        if ($metadata !== null) {
            $this->components->twoColumnDetail('Status', $this->formatStatus($metadata->status));
            $this->components->twoColumnDetail('Last Synced', $metadata->last_synced_at?->diffForHumans() ?? 'Never');
            $this->components->twoColumnDetail('Total Synced', number_format($metadata->synced_count));

            if ($metadata->last_cursor !== null) {
                $this->components->twoColumnDetail('Last Cursor', $metadata->last_cursor);
            }

            $lastError = $metadata->getOption('last_error');
            if ($lastError !== null) {
                $this->newLine();
                $this->warn("Last Error: {$lastError}");
            }
        } else {
            $this->line('<fg=yellow>No sync metadata found. Entity has never been synced.</>');
        }

        return self::SUCCESS;
    }

    /**
     * Show status for all entities.
     */
    private function showAllStatus(): int
    {
        $entities = SyncConfig::availableEntities();
        $data = [];

        foreach ($entities as $entity) {
            $config = SyncConfig::for($entity);
            $metadata = SyncMetadata::findFor($entity);

            $data[] = $this->buildEntityData($entity, $config, $metadata);
        }

        if ($this->option('json')) {
            $this->line((string) json_encode($data, JSON_PRETTY_PRINT));

            return self::SUCCESS;
        }

        $this->info('SAP B1 Sync Status');
        $this->newLine();

        $tableData = [];

        foreach ($data as $item) {
            $tableData[] = [
                $item['entity'],
                $item['table_exists'] ? '<fg=green>Yes</>' : '<fg=red>No</>',
                $this->formatStatus($item['status']),
                $item['last_synced'] ?? 'Never',
                number_format($item['synced_count']),
            ];
        }

        $this->table(
            ['Entity', 'Table', 'Status', 'Last Synced', 'Total'],
            $tableData
        );

        // Summary
        $this->newLine();
        $configured = count(array_filter($data, fn ($item) => $item['table_exists']));
        $this->line("Configured: {$configured}/".count($entities).' entities');

        $running = count(array_filter($data, fn ($item) => $item['status'] === 'running'));
        if ($running > 0) {
            $this->warn("{$running} sync(s) currently running");
        }

        $failed = count(array_filter($data, fn ($item) => $item['status'] === 'failed'));
        if ($failed > 0) {
            $this->error("{$failed} sync(s) in failed state");
        }

        return self::SUCCESS;
    }

    /**
     * Build entity data array.
     *
     * @return array<string, mixed>
     */
    private function buildEntityData(string $entity, ?SyncConfig $config, ?SyncMetadata $metadata): array
    {
        return [
            'entity' => $entity,
            'table' => $config?->table,
            'table_exists' => $config !== null && Schema::hasTable($config->table),
            'primary_key' => $config?->primaryKey,
            'status' => $metadata !== null ? $metadata->status : 'not_configured',
            'last_synced' => $metadata?->last_synced_at?->diffForHumans(),
            'last_synced_at' => $metadata?->last_synced_at?->toIso8601String(),
            'synced_count' => $metadata !== null ? $metadata->synced_count : 0,
            'last_cursor' => $metadata?->last_cursor,
        ];
    }

    /**
     * Format status with color.
     */
    private function formatStatus(string $status): string
    {
        return match ($status) {
            'idle' => '<fg=blue>Idle</>',
            'running' => '<fg=yellow>Running</>',
            'completed' => '<fg=green>Completed</>',
            'failed' => '<fg=red>Failed</>',
            'not_configured' => '<fg=gray>Not Configured</>',
            default => $status,
        };
    }
}
