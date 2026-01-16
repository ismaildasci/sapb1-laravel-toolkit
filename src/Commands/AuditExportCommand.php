<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Commands;

use Illuminate\Console\Command;
use SapB1\Toolkit\Audit\AuditEntry;
use SapB1\Toolkit\Audit\Contracts\AuditDriverInterface;
use SapB1\Toolkit\Audit\Drivers\DatabaseDriver;

class AuditExportCommand extends Command
{
    protected $signature = 'sapb1:audit-export
                            {--entity= : Filter by entity type}
                            {--since= : Export entries since date (Y-m-d)}
                            {--limit=10000 : Maximum records to export}
                            {--format=csv : Export format (csv or json)}
                            {--output= : Output file path (default: storage/app/audit-export-{timestamp}.{format})}';

    protected $description = 'Export audit log entries to CSV or JSON';

    public function handle(AuditDriverInterface $driver): int
    {
        $entity = $this->option('entity');
        $since = $this->option('since');
        /** @var int $limit */
        $limit = (int) $this->option('limit');
        /** @var string $format */
        $format = $this->option('format');
        /** @var string|null $outputPath */
        $outputPath = $this->option('output');

        if (! in_array($format, ['csv', 'json'], true)) {
            $this->error('Invalid format. Use "csv" or "json".');

            return self::FAILURE;
        }

        $this->info('Fetching audit log entries...');

        $entries = $this->fetchEntries($driver, $entity, $since, $limit);

        if (empty($entries)) {
            $this->warn('No entries found matching the criteria.');

            return self::SUCCESS;
        }

        $this->info('Found '.count($entries).' entries.');

        $outputPath = $outputPath ?? $this->getDefaultOutputPath($format);

        if ($format === 'csv') {
            $this->exportToCsv($entries, $outputPath);
        } else {
            $this->exportToJson($entries, $outputPath);
        }

        $this->info("Exported to: {$outputPath}");

        return self::SUCCESS;
    }

    /**
     * @return array<int, AuditEntry>
     */
    private function fetchEntries(
        AuditDriverInterface $driver,
        ?string $entity,
        ?string $since,
        int $limit
    ): array {
        if ($entity !== null) {
            return $driver->getByEntityType($entity, $since, $limit);
        }

        // For all entities, we need to query without entity filter
        if ($driver instanceof DatabaseDriver) {
            return $this->fetchAllEntries($driver, $since, $limit);
        }

        return [];
    }

    /**
     * @return array<int, AuditEntry>
     */
    private function fetchAllEntries(DatabaseDriver $driver, ?string $since, int $limit): array
    {
        // Use reflection to access the private query method
        $reflection = new \ReflectionClass($driver);

        if ($reflection->hasMethod('getByEntityType')) {
            // Get entries for common entities
            $entities = ['Orders', 'Items', 'BusinessPartners', 'Invoices', 'Payments'];
            $allEntries = [];

            foreach ($entities as $entity) {
                $entries = $driver->getByEntityType($entity, $since, $limit);
                $allEntries = array_merge($allEntries, $entries);
            }

            // Sort by created_at descending
            usort($allEntries, function (AuditEntry $a, AuditEntry $b) {
                return $b->createdAt <=> $a->createdAt;
            });

            return array_slice($allEntries, 0, $limit);
        }

        return [];
    }

    /**
     * @param  array<int, AuditEntry>  $entries
     */
    private function exportToCsv(array $entries, string $path): void
    {
        $handle = fopen($path, 'w');

        if ($handle === false) {
            $this->error("Cannot open file for writing: {$path}");

            return;
        }

        // CSV Header
        fputcsv($handle, [
            'ID',
            'Entity Type',
            'Entity ID',
            'Event',
            'User ID',
            'User Type',
            'IP Address',
            'User Agent',
            'Changed Fields',
            'Old Values',
            'New Values',
            'Created At',
        ]);

        foreach ($entries as $entry) {
            $context = $entry->context;
            $row = [
                (string) ($entry->id ?? ''),
                $entry->entityType,
                (string) $entry->entityId,
                $entry->event,
                $context !== null ? (string) ($context->userId ?? '') : '',
                $context !== null ? (string) ($context->userType ?? '') : '',
                $context !== null ? (string) ($context->ipAddress ?? '') : '',
                $context !== null ? (string) ($context->userAgent ?? '') : '',
                json_encode($entry->changedFields ?? []) ?: '[]',
                json_encode($entry->oldValues ?? []) ?: '[]',
                json_encode($entry->newValues ?? []) ?: '[]',
                $entry->createdAt?->format('Y-m-d H:i:s') ?? '',
            ];

            fputcsv($handle, $row);
        }

        fclose($handle);
    }

    /**
     * @param  array<int, AuditEntry>  $entries
     */
    private function exportToJson(array $entries, string $path): void
    {
        $data = array_map(fn (AuditEntry $entry) => $entry->toArray(), $entries);

        file_put_contents($path, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    private function getDefaultOutputPath(string $format): string
    {
        $timestamp = now()->format('Y-m-d-His');

        return storage_path("app/audit-export-{$timestamp}.{$format}");
    }
}
