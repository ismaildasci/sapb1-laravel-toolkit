<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Services;

/**
 * Service for data synchronization operations.
 */
final class SyncService extends BaseService
{
    /**
     * Get changes since a specific date.
     *
     * @return array<array<string, mixed>>
     */
    public function getChangesSince(string $entity, string $sinceDate): array
    {
        $response = $this->client()
            ->service($entity)
            ->queryBuilder()
            ->filter("UpdateDate ge '{$sinceDate}'")
            ->get();

        return $response['value'] ?? [];
    }

    /**
     * Get new records since a specific entry.
     *
     * @return array<array<string, mixed>>
     */
    public function getNewRecordsSince(string $entity, int $sinceEntry, string $primaryKey = 'DocEntry'): array
    {
        $response = $this->client()
            ->service($entity)
            ->queryBuilder()
            ->filter("{$primaryKey} gt {$sinceEntry}")
            ->orderBy("{$primaryKey} asc")
            ->get();

        return $response['value'] ?? [];
    }

    /**
     * Sync items.
     *
     * @return array<array<string, mixed>>
     */
    public function syncItems(string $sinceDate): array
    {
        return $this->getChangesSince('Items', $sinceDate);
    }

    /**
     * Sync business partners.
     *
     * @return array<array<string, mixed>>
     */
    public function syncBusinessPartners(string $sinceDate): array
    {
        return $this->getChangesSince('BusinessPartners', $sinceDate);
    }

    /**
     * Sync invoices.
     *
     * @return array<array<string, mixed>>
     */
    public function syncInvoices(string $sinceDate): array
    {
        return $this->getChangesSince('Invoices', $sinceDate);
    }

    /**
     * Sync orders.
     *
     * @return array<array<string, mixed>>
     */
    public function syncOrders(string $sinceDate): array
    {
        return $this->getChangesSince('Orders', $sinceDate);
    }

    /**
     * Get last sync timestamp from metadata.
     */
    public function getLastSyncTime(string $entity): ?string
    {
        $cacheKey = "sapb1_sync_{$entity}_last";

        return cache()->get($cacheKey);
    }

    /**
     * Update last sync timestamp.
     */
    public function updateLastSyncTime(string $entity, ?string $timestamp = null): void
    {
        $cacheKey = "sapb1_sync_{$entity}_last";
        $timestamp = $timestamp ?? date('Y-m-d H:i:s');

        cache()->forever($cacheKey, $timestamp);
    }

    /**
     * Full sync with callback.
     *
     * @param  callable(array<string, mixed>): void  $callback
     */
    public function fullSync(string $entity, callable $callback, int $batchSize = 100): int
    {
        $skip = 0;
        $total = 0;

        do {
            $response = $this->client()
                ->service($entity)
                ->queryBuilder()
                ->top($batchSize)
                ->skip($skip)
                ->get();

            $records = $response['value'] ?? [];
            $count = count($records);

            foreach ($records as $record) {
                $callback($record);
            }

            $total += $count;
            $skip += $batchSize;
        } while ($count === $batchSize);

        $this->updateLastSyncTime($entity);

        return $total;
    }

    /**
     * Incremental sync with callback.
     *
     * @param  callable(array<string, mixed>): void  $callback
     */
    public function incrementalSync(string $entity, callable $callback): int
    {
        $lastSync = $this->getLastSyncTime($entity);

        if ($lastSync === null) {
            return $this->fullSync($entity, $callback);
        }

        $records = $this->getChangesSince($entity, $lastSync);
        $count = count($records);

        foreach ($records as $record) {
            $callback($record);
        }

        if ($count > 0) {
            $this->updateLastSyncTime($entity);
        }

        return $count;
    }
}
