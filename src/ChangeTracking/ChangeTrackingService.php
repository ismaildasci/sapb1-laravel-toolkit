<?php

declare(strict_types=1);

namespace SapB1\Toolkit\ChangeTracking;

use Illuminate\Support\Collection;
use SapB1\Toolkit\ChangeTracking\Contracts\StateStore;

/**
 * High-level service for change tracking across multiple entities.
 */
final class ChangeTrackingService
{
    /**
     * Registered watcher configurations.
     *
     * @var array<string, WatcherConfig>
     */
    private array $watchers = [];

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
     * Create a new change tracking service.
     */
    public function __construct(?StateStore $stateStore = null)
    {
        $this->stateStore = $stateStore ?? new CacheStateStore;
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
     * Register a watcher for an entity.
     */
    public function watch(string $entity): WatcherConfig
    {
        $config = WatcherConfig::for($entity);
        $this->watchers[$entity] = $config;

        return $config;
    }

    /**
     * Register a watcher config.
     */
    public function register(WatcherConfig $config): self
    {
        $this->watchers[$config->entity] = $config;

        return $this;
    }

    /**
     * Unregister a watcher.
     */
    public function unwatch(string $entity): self
    {
        unset($this->watchers[$entity]);

        return $this;
    }

    /**
     * Check if an entity is being watched.
     */
    public function isWatching(string $entity): bool
    {
        return isset($this->watchers[$entity]);
    }

    /**
     * Get all registered watcher configurations.
     *
     * @return array<string, WatcherConfig>
     */
    public function getWatchers(): array
    {
        return $this->watchers;
    }

    /**
     * Poll a specific entity for changes.
     *
     * @return Collection<int, Change>
     */
    public function pollEntity(string $entity): Collection
    {
        if (! isset($this->watchers[$entity])) {
            return new Collection;
        }

        $tracker = $this->createTracker($this->watchers[$entity]);

        return $tracker->poll();
    }

    /**
     * Poll all registered entities for changes.
     *
     * @return array<string, Collection<int, Change>>
     */
    public function pollAll(): array
    {
        $results = [];

        foreach (array_keys($this->watchers) as $entity) {
            $results[$entity] = $this->pollEntity($entity);
        }

        return $results;
    }

    /**
     * Poll entities and return flat collection of all changes.
     *
     * @return Collection<int, Change>
     */
    public function poll(): Collection
    {
        $allChanges = new Collection;

        foreach ($this->pollAll() as $changes) {
            $allChanges = $allChanges->merge($changes);
        }

        return $allChanges;
    }

    /**
     * Reset state for a specific entity.
     */
    public function reset(string $entity): self
    {
        $this->stateStore->clear($entity);

        return $this;
    }

    /**
     * Reset state for all watched entities.
     */
    public function resetAll(): self
    {
        foreach (array_keys($this->watchers) as $entity) {
            $this->stateStore->clear($entity);
        }

        return $this;
    }

    /**
     * Create a tracker for a config.
     */
    private function createTracker(WatcherConfig $config): ChangeTracker
    {
        return (new ChangeTracker($config, $this->stateStore))
            ->connection($this->connection)
            ->dispatchEvents($this->dispatchEvents);
    }

    /**
     * Shortcut: Watch Orders.
     */
    public function watchOrders(): WatcherConfig
    {
        return $this->watch('Orders')->primaryKey('DocEntry');
    }

    /**
     * Shortcut: Watch Invoices.
     */
    public function watchInvoices(): WatcherConfig
    {
        return $this->watch('Invoices')->primaryKey('DocEntry');
    }

    /**
     * Shortcut: Watch Items.
     */
    public function watchItems(): WatcherConfig
    {
        return $this->watch('Items')->primaryKey('ItemCode');
    }

    /**
     * Shortcut: Watch Business Partners.
     */
    public function watchPartners(): WatcherConfig
    {
        return $this->watch('BusinessPartners')->primaryKey('CardCode');
    }

    /**
     * Shortcut: Watch Purchase Orders.
     */
    public function watchPurchaseOrders(): WatcherConfig
    {
        return $this->watch('PurchaseOrders')->primaryKey('DocEntry');
    }

    /**
     * Shortcut: Watch Deliveries.
     */
    public function watchDeliveries(): WatcherConfig
    {
        return $this->watch('DeliveryNotes')->primaryKey('DocEntry');
    }
}
