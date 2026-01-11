<?php

declare(strict_types=1);

namespace SapB1\Toolkit\ChangeTracking;

use Illuminate\Support\Collection;
use SapB1\Facades\SapB1;
use SapB1\Toolkit\ChangeTracking\Contracts\StateStore;
use SapB1\Toolkit\ChangeTracking\Events\ChangesDetected;
use SapB1\Toolkit\ChangeTracking\Events\EntityChangeDetected;

/**
 * Tracks changes in SAP B1 entities via polling.
 */
final class ChangeTracker
{
    /**
     * The watcher configuration.
     */
    private WatcherConfig $config;

    /**
     * The state store.
     */
    private StateStore $stateStore;

    /**
     * The SAP B1 connection name.
     */
    private string $connection = 'default';

    /**
     * Whether to dispatch Laravel events.
     */
    private bool $dispatchEvents = true;

    /**
     * Create a new change tracker.
     */
    public function __construct(WatcherConfig $config, ?StateStore $stateStore = null)
    {
        $this->config = $config;
        $this->stateStore = $stateStore ?? new CacheStateStore;
    }

    /**
     * Create a new tracker for an entity.
     */
    public static function watch(string $entity): WatcherConfig
    {
        return WatcherConfig::for($entity);
    }

    /**
     * Create a tracker from a config.
     */
    public static function fromConfig(WatcherConfig $config): self
    {
        return new self($config);
    }

    /**
     * Set the SAP B1 connection.
     */
    public function connection(string $connection): self
    {
        $this->connection = $connection;

        return $this;
    }

    /**
     * Set the state store.
     */
    public function stateStore(StateStore $store): self
    {
        $this->stateStore = $store;

        return $this;
    }

    /**
     * Enable/disable Laravel event dispatching.
     */
    public function dispatchEvents(bool $dispatch = true): self
    {
        $this->dispatchEvents = $dispatch;

        return $this;
    }

    /**
     * Poll for changes and return detected changes.
     *
     * @return Collection<int, Change>
     */
    public function poll(): Collection
    {
        $changes = new Collection;

        // Detect created records
        if ($this->config->detectCreated) {
            $created = $this->detectCreated();
            $changes = $changes->merge($created);
        }

        // Detect updated records
        if ($this->config->detectUpdated) {
            $updated = $this->detectUpdated();
            $changes = $changes->merge($updated);
        }

        // Detect deleted records (if enabled)
        if ($this->config->detectDeleted) {
            $deleted = $this->detectDeleted();
            $changes = $changes->merge($deleted);
        }

        // Process changes
        $this->processChanges($changes);

        return $changes;
    }

    /**
     * Detect newly created records.
     *
     * @return Collection<int, Change>
     */
    private function detectCreated(): Collection
    {
        $changes = new Collection;
        $lastKey = $this->stateStore->getLastPrimaryKey($this->config->entity);
        $primaryKey = $this->config->primaryKey;

        // Build query
        $query = $this->client()
            ->service($this->config->entity)
            ->queryBuilder()
            ->orderby($primaryKey, 'desc')
            ->top($this->config->batchSize);

        if ($this->config->filter) {
            $query->filter($this->config->filter);
        }

        if (! empty($this->config->select)) {
            $query->select($this->config->select);
        }

        $response = $query->get();
        $records = $response['value'] ?? [];

        if (empty($records)) {
            return $changes;
        }

        // Get the newest primary key
        $newestKey = $records[0][$primaryKey] ?? null;

        // If we have a last key, filter to only new records
        if ($lastKey !== null) {
            foreach ($records as $record) {
                $recordKey = $record[$primaryKey] ?? null;

                if ($recordKey !== null && $this->compareKeys($recordKey, $lastKey) > 0) {
                    $changes->push(Change::created(
                        $this->config->entity,
                        $recordKey,
                        $record
                    ));
                }
            }
        }

        // Update the last known key
        if ($newestKey !== null) {
            $this->stateStore->setLastPrimaryKey($this->config->entity, $newestKey);
        }

        return $changes;
    }

    /**
     * Detect updated records.
     *
     * @return Collection<int, Change>
     */
    private function detectUpdated(): Collection
    {
        $changes = new Collection;
        $lastCheck = $this->stateStore->getLastCheckTime($this->config->entity);
        $now = date('Y-m-d H:i:s');

        // Build query for updated records
        $query = $this->client()
            ->service($this->config->entity)
            ->queryBuilder()
            ->top($this->config->batchSize);

        // Filter by update date if we have a last check time
        if ($lastCheck !== null) {
            $updateFilter = "{$this->config->updateDateField} ge '{$lastCheck}'";

            if ($this->config->filter) {
                $query->filter("({$this->config->filter}) and ({$updateFilter})");
            } else {
                $query->filter($updateFilter);
            }
        } elseif ($this->config->filter) {
            $query->filter($this->config->filter);
        }

        if (! empty($this->config->select)) {
            $query->select($this->config->select);
        }

        $response = $query->get();
        $records = $response['value'] ?? [];

        foreach ($records as $record) {
            $recordKey = $record[$this->config->primaryKey] ?? null;

            if ($recordKey !== null) {
                $changes->push(Change::updated(
                    $this->config->entity,
                    $recordKey,
                    $record
                ));
            }
        }

        // Update the last check time
        $this->stateStore->setLastCheckTime($this->config->entity, $now);

        return $changes;
    }

    /**
     * Detect deleted records.
     *
     * @return Collection<int, Change>
     */
    private function detectDeleted(): Collection
    {
        $changes = new Collection;
        $knownKeys = $this->stateStore->getKnownKeys($this->config->entity);

        if (empty($knownKeys)) {
            // First run - store all current keys
            $this->updateKnownKeys();

            return $changes;
        }

        // Get current keys
        $currentKeys = $this->fetchAllKeys();

        // Find deleted (in known but not in current)
        $deletedKeys = array_diff($knownKeys, $currentKeys);

        foreach ($deletedKeys as $key) {
            $changes->push(Change::deleted(
                $this->config->entity,
                $key
            ));
        }

        // Update known keys
        $this->stateStore->setKnownKeys($this->config->entity, $currentKeys);

        return $changes;
    }

    /**
     * Fetch all primary keys for the entity.
     *
     * @return array<int, int|string>
     */
    private function fetchAllKeys(): array
    {
        $keys = [];
        $skip = 0;
        $primaryKey = $this->config->primaryKey;

        do {
            $query = $this->client()
                ->service($this->config->entity)
                ->queryBuilder()
                ->select([$primaryKey])
                ->top($this->config->batchSize)
                ->skip($skip);

            if ($this->config->filter) {
                $query->filter($this->config->filter);
            }

            $response = $query->get();
            $records = $response['value'] ?? [];
            $count = count($records);

            foreach ($records as $record) {
                if (isset($record[$primaryKey])) {
                    $keys[] = $record[$primaryKey];
                }
            }

            $skip += $this->config->batchSize;
        } while ($count === $this->config->batchSize);

        return $keys;
    }

    /**
     * Update known keys from current state.
     */
    private function updateKnownKeys(): void
    {
        $keys = $this->fetchAllKeys();
        $this->stateStore->setKnownKeys($this->config->entity, $keys);
    }

    /**
     * Process detected changes (callbacks and events).
     *
     * @param  Collection<int, Change>  $changes
     */
    private function processChanges(Collection $changes): void
    {
        if ($changes->isEmpty()) {
            return;
        }

        // Dispatch bulk event
        if ($this->dispatchEvents) {
            event(new ChangesDetected($this->config->entity, $changes));
        }

        // Process individual changes
        foreach ($changes as $change) {
            // Execute type-specific callback
            $callback = $this->config->getCallback($change->type);
            if ($callback !== null) {
                $callback($change);
            }

            // Execute "any" callback
            $anyCallback = $this->config->getAnyCallback();
            if ($anyCallback !== null) {
                $anyCallback($change);
            }

            // Dispatch individual event
            if ($this->dispatchEvents) {
                event(new EntityChangeDetected($change));
            }
        }
    }

    /**
     * Compare two primary keys.
     */
    private function compareKeys(int|string $a, int|string $b): int
    {
        if (is_int($a) && is_int($b)) {
            return $a <=> $b;
        }

        return strcmp((string) $a, (string) $b);
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

    /**
     * Reset all state for the watched entity.
     */
    public function reset(): void
    {
        $this->stateStore->clear($this->config->entity);
    }

    /**
     * Get the watcher configuration.
     */
    public function getConfig(): WatcherConfig
    {
        return $this->config;
    }

    /**
     * Get the state store.
     */
    public function getStateStore(): StateStore
    {
        return $this->stateStore;
    }
}
