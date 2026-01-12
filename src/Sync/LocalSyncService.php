<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Sync;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use SapB1\Facades\SapB1;
use SapB1\Toolkit\Sync\Exceptions\SyncException;

/**
 * Service for syncing SAP B1 data to local database.
 */
final class LocalSyncService
{
    /**
     * The SAP B1 connection name.
     */
    private string $connection = 'default';

    /**
     * Create a new LocalSyncService instance.
     */
    public function __construct(
        private readonly SyncRegistry $registry,
    ) {}

    /**
     * Set the SAP B1 connection.
     */
    public function connection(string $name): self
    {
        $this->connection = $name;

        return $this;
    }

    /**
     * Sync an entity to local database.
     */
    public function sync(string $entity): SyncResult
    {
        $config = $this->registry->get($entity);
        $this->validateTable($config);

        $metadata = SyncMetadata::findOrCreateFor($entity);

        // Check if already running
        if ($metadata->status === SyncMetadata::STATUS_RUNNING) {
            return SyncResult::failed($entity, 'Sync is already running for this entity');
        }

        $metadata->markAsRunning();
        $startTime = microtime(true);

        try {
            // Determine sync type based on last sync
            if ($metadata->hasBeenSynced()) {
                $result = $this->incrementalSync($config, $metadata);
            } else {
                $result = $this->fullSync($config);
            }

            $duration = microtime(true) - $startTime;
            $result = new SyncResult(
                entity: $entity,
                created: $result['created'],
                updated: $result['updated'],
                deleted: 0,
                duration: $duration,
            );

            $metadata->markAsCompleted($result);

            return $result;
        } catch (\Throwable $e) {
            $metadata->markAsFailed($e->getMessage());

            return SyncResult::failed($entity, $e->getMessage(), microtime(true) - $startTime);
        }
    }

    /**
     * Perform a full sync with delete detection.
     */
    public function fullSyncWithDeletes(string $entity): SyncResult
    {
        $config = $this->registry->get($entity);
        $this->validateTable($config);

        $metadata = SyncMetadata::findOrCreateFor($entity);

        if ($metadata->status === SyncMetadata::STATUS_RUNNING) {
            return SyncResult::failed($entity, 'Sync is already running for this entity');
        }

        $metadata->markAsRunning();
        $startTime = microtime(true);

        try {
            // Full sync
            $result = $this->fullSync($config);

            // Delete detection
            $deleted = 0;
            if ($config->trackDeletes) {
                $deleted = $this->detectAndMarkDeleted($config);
            }

            $duration = microtime(true) - $startTime;
            $syncResult = new SyncResult(
                entity: $entity,
                created: $result['created'],
                updated: $result['updated'],
                deleted: $deleted,
                duration: $duration,
            );

            $metadata->markAsCompleted($syncResult);

            return $syncResult;
        } catch (\Throwable $e) {
            $metadata->markAsFailed($e->getMessage());

            return SyncResult::failed($entity, $e->getMessage(), microtime(true) - $startTime);
        }
    }

    /**
     * Sync multiple entities.
     *
     * @param  array<string>  $entities
     * @return Collection<int, SyncResult>
     */
    public function syncMany(array $entities): Collection
    {
        $results = collect();

        foreach ($entities as $entity) {
            $results->push($this->sync($entity));
        }

        return $results;
    }

    /**
     * Sync all registered entities.
     *
     * @return Collection<int, SyncResult>
     */
    public function syncAll(): Collection
    {
        return $this->syncMany($this->registry->entitiesWithTables());
    }

    /**
     * Sync since a specific date.
     */
    public function syncSince(string $entity, string $sinceDate): SyncResult
    {
        $config = $this->registry->get($entity);
        $this->validateTable($config);

        $metadata = SyncMetadata::findOrCreateFor($entity);
        $metadata->markAsRunning();
        $startTime = microtime(true);

        try {
            $result = $this->doIncrementalSync($config, $sinceDate);

            $duration = microtime(true) - $startTime;
            $syncResult = new SyncResult(
                entity: $entity,
                created: $result['created'],
                updated: $result['updated'],
                duration: $duration,
            );

            $metadata->markAsCompleted($syncResult);

            return $syncResult;
        } catch (\Throwable $e) {
            $metadata->markAsFailed($e->getMessage());

            return SyncResult::failed($entity, $e->getMessage(), microtime(true) - $startTime);
        }
    }

    /**
     * Get sync status for an entity.
     *
     * @return array<string, mixed>
     */
    public function status(string $entity): array
    {
        $metadata = SyncMetadata::findFor($entity);

        if ($metadata === null) {
            return [
                'entity' => $entity,
                'status' => 'not_configured',
                'last_synced_at' => null,
                'synced_count' => 0,
            ];
        }

        return [
            'entity' => $entity,
            'status' => $metadata->status,
            'last_synced_at' => $metadata->last_synced_at?->toIso8601String(),
            'synced_count' => $metadata->synced_count,
            'last_cursor' => $metadata->last_cursor,
        ];
    }

    /**
     * Get sync status for all entities.
     *
     * @return Collection<int, array{entity: string, status: string, last_synced_at: string|null, synced_count: int}>
     */
    public function statusAll(): Collection
    {
        /** @var Collection<int, array{entity: string, status: string, last_synced_at: string|null, synced_count: int}> */
        return SyncMetadata::all()->map(fn (SyncMetadata $m) => [
            'entity' => $m->entity,
            'status' => $m->status,
            'last_synced_at' => $m->last_synced_at?->toIso8601String(),
            'synced_count' => $m->synced_count,
        ]);
    }

    /**
     * Reset sync metadata for an entity.
     */
    public function reset(string $entity): void
    {
        $metadata = SyncMetadata::findFor($entity);

        if ($metadata !== null) {
            $metadata->delete();
        }
    }

    /**
     * Reset all sync metadata.
     */
    public function resetAll(): void
    {
        SyncMetadata::truncate();
    }

    /**
     * Perform full sync.
     *
     * @return array{created: int, updated: int}
     */
    private function fullSync(SyncConfig $config): array
    {
        $created = 0;
        $updated = 0;
        $skip = 0;

        do {
            $query = $this->client()
                ->service($config->entity)
                ->queryBuilder()
                ->select(implode(',', $config->columns))
                ->top($config->batchSize)
                ->skip($skip);

            if ($config->filter !== null) {
                $query->filter($config->filter);
            }

            $response = $query->get();
            $records = $response['value'] ?? [];
            $count = count($records);

            if ($count > 0) {
                $result = $this->upsertRecords($config, $records);
                $created += $result['created'];
                $updated += $result['updated'];

                // Sync lines if configured
                if ($config->syncLines && $config->linesTable !== null) {
                    $this->syncLines($config, $records);
                }
            }

            $skip += $config->batchSize;
        } while ($count === $config->batchSize);

        return ['created' => $created, 'updated' => $updated];
    }

    /**
     * Perform incremental sync.
     *
     * @return array{created: int, updated: int}
     */
    private function incrementalSync(SyncConfig $config, SyncMetadata $metadata): array
    {
        $sinceDate = $metadata->last_synced_at?->format('Y-m-d H:i:s') ?? '';

        return $this->doIncrementalSync($config, $sinceDate);
    }

    /**
     * Do incremental sync from a specific date.
     *
     * @return array{created: int, updated: int}
     */
    private function doIncrementalSync(SyncConfig $config, string $sinceDate): array
    {
        $created = 0;
        $updated = 0;

        $filter = "{$config->updateDateField} ge '{$sinceDate}'";

        if ($config->filter !== null) {
            $filter = "({$filter}) and ({$config->filter})";
        }

        $skip = 0;

        do {
            $response = $this->client()
                ->service($config->entity)
                ->queryBuilder()
                ->select(implode(',', $config->columns))
                ->filter($filter)
                ->top($config->batchSize)
                ->skip($skip)
                ->get();

            $records = $response['value'] ?? [];
            $count = count($records);

            if ($count > 0) {
                $result = $this->upsertRecords($config, $records);
                $created += $result['created'];
                $updated += $result['updated'];

                if ($config->syncLines && $config->linesTable !== null) {
                    $this->syncLines($config, $records);
                }
            }

            $skip += $config->batchSize;
        } while ($count === $config->batchSize);

        return ['created' => $created, 'updated' => $updated];
    }

    /**
     * Upsert records to local database.
     *
     * @param  array<int, array<string, mixed>>  $records
     * @return array{created: int, updated: int}
     */
    private function upsertRecords(SyncConfig $config, array $records): array
    {
        if (empty($records)) {
            return ['created' => 0, 'updated' => 0];
        }

        // Get existing keys
        $primaryKey = $config->primaryKey;
        $keys = array_column($records, $primaryKey);
        $existingKeys = DB::table($config->table)
            ->whereIn($primaryKey, $keys)
            ->pluck($primaryKey)
            ->toArray();

        $existingCount = count($existingKeys);
        $totalCount = count($records);

        // Transform records
        $data = array_map(function (array $record) use ($config) {
            $transformed = $this->transformRecord($record, $config);
            $transformed['synced_at'] = Carbon::now();

            // Handle UpdateDate mapping to sap_updated_at
            if (isset($record['UpdateDate'])) {
                $transformed['sap_updated_at'] = $record['UpdateDate'];
            }

            return $transformed;
        }, $records);

        // Upsert with all columns except timestamps
        $updateColumns = array_diff($config->columns, [$primaryKey]);
        $updateColumns = array_merge($updateColumns, ['synced_at', 'sap_updated_at']);

        DB::table($config->table)->upsert($data, [$primaryKey], $updateColumns);

        return [
            'created' => $totalCount - $existingCount,
            'updated' => $existingCount,
        ];
    }

    /**
     * Sync document lines.
     *
     * @param  array<int, array<string, mixed>>  $records
     */
    private function syncLines(SyncConfig $config, array $records): void
    {
        if ($config->linesTable === null || $config->lineColumns === null) {
            return;
        }

        $allLines = [];

        foreach ($records as $record) {
            $docEntry = $record[$config->primaryKey] ?? null;
            $lines = $record['DocumentLines'] ?? [];

            foreach ($lines as $line) {
                $lineData = [];

                foreach ($config->lineColumns as $col) {
                    if ($col === 'DocEntry') {
                        $lineData['DocEntry'] = $docEntry;
                    } else {
                        $lineData[$col] = $line[$col] ?? null;
                    }
                }

                $allLines[] = $lineData;
            }
        }

        if (! empty($allLines)) {
            // Delete existing lines for these documents
            $docEntries = array_unique(array_column($allLines, 'DocEntry'));
            DB::table($config->linesTable)->whereIn('DocEntry', $docEntries)->delete();

            // Insert new lines
            DB::table($config->linesTable)->insert($allLines);
        }
    }

    /**
     * Transform a record for database insertion.
     *
     * @param  array<string, mixed>  $record
     * @return array<string, mixed>
     */
    private function transformRecord(array $record, SyncConfig $config): array
    {
        if ($config->transformer !== null) {
            return ($config->transformer)($record);
        }

        // Only keep configured columns
        $data = [];

        foreach ($config->columns as $column) {
            $data[$column] = $record[$column] ?? null;
        }

        return $data;
    }

    /**
     * Detect and mark deleted records.
     */
    private function detectAndMarkDeleted(SyncConfig $config): int
    {
        // Fetch all primary keys from SAP
        $sapKeys = $this->fetchAllPrimaryKeys($config);

        // Find local records not in SAP and soft delete them
        $deleted = DB::table($config->table)
            ->whereNull('deleted_at')
            ->whereNotIn($config->primaryKey, $sapKeys)
            ->update(['deleted_at' => Carbon::now()]);

        return $deleted;
    }

    /**
     * Fetch all primary keys from SAP.
     *
     * @return array<int|string>
     */
    private function fetchAllPrimaryKeys(SyncConfig $config): array
    {
        $keys = [];
        $skip = 0;
        $batchSize = 10000; // Larger batch for key-only fetch

        do {
            $query = $this->client()
                ->service($config->entity)
                ->queryBuilder()
                ->select($config->primaryKey)
                ->top($batchSize)
                ->skip($skip);

            if ($config->filter !== null) {
                $query->filter($config->filter);
            }

            $response = $query->get();
            $records = $response['value'] ?? [];
            $count = count($records);

            foreach ($records as $record) {
                $keys[] = $record[$config->primaryKey];
            }

            $skip += $batchSize;
        } while ($count === $batchSize);

        return $keys;
    }

    /**
     * Validate that the table exists.
     *
     * @throws SyncException
     */
    private function validateTable(SyncConfig $config): void
    {
        if (! Schema::hasTable($config->table)) {
            throw SyncException::tableNotFound($config->table);
        }
    }

    /**
     * Get the SAP B1 client.
     *
     * @return mixed
     */
    private function client()
    {
        return SapB1::connection($this->connection);
    }
}
