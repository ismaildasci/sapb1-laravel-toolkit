<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Commands;

use Illuminate\Console\Command;
use SapB1\Toolkit\ChangeTracking\Change;
use SapB1\Toolkit\ChangeTracking\ChangeTracker;
use SapB1\Toolkit\ChangeTracking\WatcherConfig;

class WatchCommand extends Command
{
    protected $signature = 'sapb1:watch
                            {entity : The entity to watch (Orders, Invoices, Items, BusinessPartners, etc.)}
                            {--created : Only detect created records}
                            {--updated : Only detect updated records}
                            {--deleted : Also detect deleted records (slower)}
                            {--once : Poll once and exit (default: continuous)}
                            {--interval=30 : Polling interval in seconds}
                            {--filter= : OData filter to apply}
                            {--primary-key=DocEntry : The primary key field}
                            {--connection=default : The SAP B1 connection to use}
                            {--reset : Reset watcher state before starting}
                            {--quiet-poll : Suppress output during polling}';

    protected $description = 'Watch SAP B1 entity for changes';

    public function handle(): int
    {
        /** @var string $entity */
        $entity = $this->argument('entity');
        /** @var string $connection */
        $connection = $this->option('connection') ?? 'default';
        /** @var string $primaryKey */
        $primaryKey = $this->option('primary-key') ?? 'DocEntry';
        /** @var string|null $filter */
        $filter = $this->option('filter');
        $interval = (int) ($this->option('interval') ?? 30);
        $once = (bool) $this->option('once');
        $reset = (bool) $this->option('reset');
        $quietPoll = (bool) $this->option('quiet-poll');

        // Build watcher config
        $config = WatcherConfig::for($entity)
            ->primaryKey($primaryKey);

        // Handle detection type options
        if ($this->option('created') || $this->option('updated')) {
            $config->detectCreated((bool) $this->option('created'));
            $config->detectUpdated((bool) $this->option('updated'));
        }

        if ($this->option('deleted')) {
            $config->detectDeleted(true);
        }

        if ($filter) {
            $config->filter($filter);
        }

        // Create tracker
        $tracker = ChangeTracker::fromConfig($config)
            ->connection($connection);

        // Reset state if requested
        if ($reset) {
            $tracker->reset();
            $this->info('Watcher state reset.');
        }

        $this->info("Watching {$entity} for changes...");
        $this->line("  Primary Key: {$primaryKey}");
        $this->line('  Detect Created: '.($config->detectCreated ? 'Yes' : 'No'));
        $this->line('  Detect Updated: '.($config->detectUpdated ? 'Yes' : 'No'));
        $this->line('  Detect Deleted: '.($config->detectDeleted ? 'Yes' : 'No'));

        if ($filter) {
            $this->line("  Filter: {$filter}");
        }

        if (! $once) {
            $this->line("  Interval: {$interval}s");
            $this->newLine();
            $this->info('Press Ctrl+C to stop watching.');
        }

        $this->newLine();

        // Polling loop
        do {
            if (! $quietPoll) {
                $this->line('['.date('Y-m-d H:i:s').'] Polling...');
            }

            $changes = $tracker->poll();

            if ($changes->isEmpty()) {
                if (! $quietPoll) {
                    $this->line('  No changes detected.');
                }
            } else {
                $this->displayChanges($changes);
            }

            if (! $once) {
                sleep($interval);
            }
        } while (! $once);

        return self::SUCCESS;
    }

    /**
     * Display detected changes.
     *
     * @param  \Illuminate\Support\Collection<int, Change>  $changes
     */
    private function displayChanges($changes): void
    {
        $this->info("  Found {$changes->count()} change(s):");

        foreach ($changes as $change) {
            $icon = match (true) {
                $change->isCreated() => '<fg=green>+</>',
                $change->isUpdated() => '<fg=yellow>~</>',
                $change->isDeleted() => '<fg=red>-</>',
                default => '?',
            };

            $type = strtoupper($change->type->value);
            $key = $change->primaryKey;

            $this->line("    {$icon} [{$type}] {$change->entity} #{$key}");

            // Show some data for created/updated
            if (! $change->isDeleted() && ! empty($change->data)) {
                $preview = $this->getDataPreview($change->data);
                if ($preview) {
                    $this->line("      {$preview}");
                }
            }
        }

        $this->newLine();
    }

    /**
     * Get a short preview of the data.
     *
     * @param  array<string, mixed>  $data
     */
    private function getDataPreview(array $data): string
    {
        $fields = ['CardCode', 'CardName', 'ItemCode', 'ItemName', 'DocNum', 'DocTotal'];
        $preview = [];

        foreach ($fields as $field) {
            if (isset($data[$field])) {
                $value = $data[$field];
                if (is_string($value) && strlen($value) > 30) {
                    $value = substr($value, 0, 27).'...';
                }
                $preview[] = "{$field}: {$value}";

                if (count($preview) >= 3) {
                    break;
                }
            }
        }

        return implode(' | ', $preview);
    }
}
