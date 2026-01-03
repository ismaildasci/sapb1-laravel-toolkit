<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Commands;

use Illuminate\Console\Command;
use SapB1\Facades\SapB1;

class CacheCommand extends Command
{
    protected $signature = 'sapb1:cache
                            {action : The action to perform (warm, clear)}
                            {--connection=default : The SAP B1 connection to use}';

    protected $description = 'Manage SAP B1 cache';

    public function handle(): int
    {
        /** @var string $action */
        $action = $this->argument('action');
        /** @var string $connection */
        $connection = $this->option('connection') ?? 'default';

        return match ($action) {
            'warm' => $this->warmCache($connection),
            'clear' => $this->clearCache($connection),
            default => $this->invalidAction($action),
        };
    }

    private function warmCache(string $connection): int
    {
        $this->info('Warming cache...');

        /** @var mixed $client */
        $client = SapB1::connection($connection);

        $entities = [
            'Items' => 'ItemCode',
            'BusinessPartners' => 'CardCode',
            'Warehouses' => 'WarehouseCode',
        ];

        foreach ($entities as $entity => $key) {
            $this->line("  Caching {$entity}...");

            $response = $client->service($entity)
                ->queryBuilder()
                ->select([$key])
                ->top(1000)
                ->get();

            $count = count($response['value'] ?? []);
            $this->line("    Cached {$count} {$entity}");
        }

        $this->info('Cache warming completed.');

        return self::SUCCESS;
    }

    private function clearCache(string $connection): int
    {
        $this->info('Clearing cache...');

        $patterns = [
            'sapb1_sync_*',
            "sapb1_cache_{$connection}_*",
        ];

        foreach ($patterns as $pattern) {
            cache()->forget($pattern);
        }

        $this->info('Cache cleared.');

        return self::SUCCESS;
    }

    private function invalidAction(string $action): int
    {
        $this->error("Invalid action: {$action}. Use 'warm' or 'clear'.");

        return self::FAILURE;
    }
}
