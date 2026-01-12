<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Schema;
use SapB1\Toolkit\Sync\SyncConfig;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\multiselect;

class SyncSetupCommand extends Command
{
    protected $signature = 'sapb1:sync-setup
                            {entities?* : The entities to create migrations for}
                            {--list : List available entities}
                            {--all : Create migrations for all available entities}
                            {--force : Overwrite existing migrations}';

    protected $description = 'Create database migrations for SAP B1 sync tables';

    /**
     * Entity to stub file mapping.
     *
     * @var array<string, string>
     */
    private array $stubMapping = [
        'Items' => 'items',
        'BusinessPartners' => 'business_partners',
        'Orders' => 'orders',
        'Invoices' => 'invoices',
        'DeliveryNotes' => 'delivery_notes',
        'Quotations' => 'quotations',
        'CreditNotes' => 'credit_notes',
        'PurchaseOrders' => 'purchase_orders',
        'PurchaseInvoices' => 'purchase_invoices',
        'GoodsReceiptPO' => 'goods_receipt_po',
    ];

    public function __construct(
        private readonly Filesystem $files,
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        if ($this->option('list')) {
            return $this->listEntities();
        }

        $entities = $this->getEntitiesToSetup();

        if (empty($entities)) {
            $this->warn('No entities selected.');

            return self::SUCCESS;
        }

        $this->info('Setting up sync migrations...');
        $this->newLine();

        // Always create metadata table first
        $metadataCreated = $this->createMetadataMigration();

        // Create migrations for selected entities
        $created = 0;
        $skipped = 0;

        foreach ($entities as $entity) {
            $result = $this->createEntityMigration($entity);

            if ($result === true) {
                $created++;
            } elseif ($result === false) {
                $skipped++;
            }
        }

        $this->newLine();
        $this->info('Setup complete!');

        if ($metadataCreated) {
            $this->line('  - Metadata table migration created');
        }

        $this->line("  - {$created} entity migration(s) created");

        if ($skipped > 0) {
            $this->line("  - {$skipped} migration(s) skipped (already exist)");
        }

        $this->newLine();
        $this->info("Run 'php artisan migrate' to create the tables.");

        return self::SUCCESS;
    }

    /**
     * List available entities.
     */
    private function listEntities(): int
    {
        $this->info('Available entities for sync:');
        $this->newLine();

        $this->line('<fg=cyan>Master Data:</>');
        $this->line('  - Items');
        $this->line('  - BusinessPartners');
        $this->newLine();

        $this->line('<fg=cyan>Sales Documents:</>');
        $this->line('  - Orders');
        $this->line('  - Invoices');
        $this->line('  - DeliveryNotes');
        $this->line('  - Quotations');
        $this->line('  - CreditNotes');
        $this->newLine();

        $this->line('<fg=cyan>Purchase Documents:</>');
        $this->line('  - PurchaseOrders');
        $this->line('  - PurchaseInvoices');
        $this->line('  - GoodsReceiptPO');
        $this->newLine();

        // Show which ones already have migrations
        $existing = $this->getExistingMigrations();

        if (! empty($existing)) {
            $this->line('<fg=yellow>Already configured:</>');
            foreach ($existing as $entity) {
                $this->line("  - {$entity}");
            }
        }

        return self::SUCCESS;
    }

    /**
     * Get entities to set up.
     *
     * @return array<string>
     */
    private function getEntitiesToSetup(): array
    {
        // From argument
        /** @var array<string> $entities */
        $entities = $this->argument('entities');

        if (! empty($entities)) {
            return $this->validateEntities($entities);
        }

        // All flag
        if ($this->option('all')) {
            return SyncConfig::availableEntities();
        }

        // Interactive selection
        $available = SyncConfig::availableEntities();
        $existing = $this->getExistingMigrations();
        $notSetup = array_diff($available, $existing);

        if (empty($notSetup)) {
            $this->info('All entities are already set up.');

            if (confirm('Do you want to regenerate migrations?', false)) {
                return $available;
            }

            return [];
        }

        $options = [];
        foreach ($notSetup as $entity) {
            $config = SyncConfig::for($entity);
            $options[$entity] = "{$entity} ({$config?->table})";
        }

        /** @var array<string> $selected */
        $selected = multiselect(
            label: 'Which entities do you want to sync to local database?',
            options: $options,
            required: true,
        );

        return $selected;
    }

    /**
     * Validate entity names.
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
     * Get entities that already have migrations.
     *
     * @return array<string>
     */
    private function getExistingMigrations(): array
    {
        $existing = [];

        foreach (array_keys($this->stubMapping) as $entity) {
            $config = SyncConfig::for($entity);

            if ($config !== null && Schema::hasTable($config->table)) {
                $existing[] = $entity;
            }
        }

        return $existing;
    }

    /**
     * Create the metadata migration.
     */
    private function createMetadataMigration(): bool
    {
        if (Schema::hasTable('sapb1_sync_metadata') && ! $this->option('force')) {
            return false;
        }

        $migrationPath = $this->findExistingMigration('sapb1_sync_metadata');

        if ($migrationPath !== null && ! $this->option('force')) {
            return false;
        }

        $stub = $this->getStubContent('metadata');
        $filename = $this->generateMigrationFilename('create_sapb1_sync_metadata_table');

        $this->files->put(
            database_path("migrations/{$filename}"),
            $stub
        );

        $this->components->twoColumnDetail(
            'Migration',
            "<fg=green>database/migrations/{$filename}</>"
        );

        return true;
    }

    /**
     * Create migration for an entity.
     */
    private function createEntityMigration(string $entity): ?bool
    {
        $config = SyncConfig::for($entity);

        if ($config === null) {
            $this->error("No configuration found for entity: {$entity}");

            return null;
        }

        // Check if table already exists
        if (Schema::hasTable($config->table) && ! $this->option('force')) {
            $this->components->twoColumnDetail(
                $entity,
                '<fg=yellow>Table already exists (skipped)</>'
            );

            return false;
        }

        // Check if migration file exists
        $existingMigration = $this->findExistingMigration($config->table);

        if ($existingMigration !== null && ! $this->option('force')) {
            $this->components->twoColumnDetail(
                $entity,
                '<fg=yellow>Migration already exists (skipped)</>'
            );

            return false;
        }

        // Get stub content
        $stubName = $this->stubMapping[$entity] ?? null;

        if ($stubName === null) {
            $this->error("No stub found for entity: {$entity}");

            return null;
        }

        $stub = $this->getStubContent($stubName);
        $filename = $this->generateMigrationFilename("create_{$config->table}_table");

        $this->files->put(
            database_path("migrations/{$filename}"),
            $stub
        );

        $this->components->twoColumnDetail(
            $entity,
            "<fg=green>database/migrations/{$filename}</>"
        );

        return true;
    }

    /**
     * Get stub content.
     */
    private function getStubContent(string $name): string
    {
        $stubPath = __DIR__."/../../stubs/sync/{$name}.stub";

        if (! $this->files->exists($stubPath)) {
            throw new \RuntimeException("Stub not found: {$stubPath}");
        }

        return $this->files->get($stubPath);
    }

    /**
     * Generate migration filename with timestamp.
     */
    private function generateMigrationFilename(string $name): string
    {
        static $counter = 0;
        $timestamp = date('Y_m_d_His');

        // Add counter to ensure unique timestamps
        if ($counter > 0) {
            $timestamp = date('Y_m_d_H').str_pad((string) ((int) date('i') + $counter), 2, '0', STR_PAD_LEFT).date('s');
        }

        $counter++;

        return "{$timestamp}_{$name}.php";
    }

    /**
     * Find existing migration for a table.
     */
    private function findExistingMigration(string $table): ?string
    {
        $migrationPath = database_path('migrations');
        $pattern = "*_create_{$table}_table.php";

        $files = $this->files->glob("{$migrationPath}/{$pattern}");

        return ! empty($files) ? $files[0] : null;
    }
}
