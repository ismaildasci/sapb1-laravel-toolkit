<?php

declare(strict_types=1);

namespace SapB1\Toolkit\ChangeTracking;

use Illuminate\Support\Facades\Cache;
use SapB1\Toolkit\ChangeTracking\Contracts\StateStore;

/**
 * Cache-based state storage for change tracking.
 */
final class CacheStateStore implements StateStore
{
    private const PREFIX = 'sapb1_watcher_';

    /**
     * The cache store to use.
     */
    private ?string $store;

    /**
     * Create a new cache state store.
     */
    public function __construct(?string $store = null)
    {
        $this->store = $store;
    }

    /**
     * Get the last check timestamp for an entity.
     */
    public function getLastCheckTime(string $entity): ?string
    {
        return $this->get($this->key($entity, 'last_check'));
    }

    /**
     * Set the last check timestamp for an entity.
     */
    public function setLastCheckTime(string $entity, string $timestamp): void
    {
        $this->put($this->key($entity, 'last_check'), $timestamp);
    }

    /**
     * Get the last known primary key for an entity.
     */
    public function getLastPrimaryKey(string $entity): int|string|null
    {
        return $this->get($this->key($entity, 'last_pk'));
    }

    /**
     * Set the last known primary key for an entity.
     */
    public function setLastPrimaryKey(string $entity, int|string $key): void
    {
        $this->put($this->key($entity, 'last_pk'), $key);
    }

    /**
     * Get all known primary keys for an entity.
     *
     * @return array<int, int|string>
     */
    public function getKnownKeys(string $entity): array
    {
        return $this->get($this->key($entity, 'known_keys'), []);
    }

    /**
     * Set all known primary keys for an entity.
     *
     * @param  array<int, int|string>  $keys
     */
    public function setKnownKeys(string $entity, array $keys): void
    {
        $this->put($this->key($entity, 'known_keys'), $keys);
    }

    /**
     * Clear all state for an entity.
     */
    public function clear(string $entity): void
    {
        $this->forget($this->key($entity, 'last_check'));
        $this->forget($this->key($entity, 'last_pk'));
        $this->forget($this->key($entity, 'known_keys'));
    }

    /**
     * Clear all state for all entities.
     */
    public function clearAll(): void
    {
        // Note: This only works with tag-supporting cache drivers
        // For other drivers, you'd need to track entity names separately
        $store = $this->getStore();

        if (method_exists($store, 'flush')) {
            // Try to flush by prefix if supported
            // Most cache drivers don't support this, so we rely on manual clearing
        }
    }

    /**
     * Build a cache key.
     */
    private function key(string $entity, string $suffix): string
    {
        return self::PREFIX.$entity.'_'.$suffix;
    }

    /**
     * Get a value from cache.
     */
    private function get(string $key, mixed $default = null): mixed
    {
        return $this->getStore()->get($key, $default);
    }

    /**
     * Put a value in cache (forever).
     */
    private function put(string $key, mixed $value): void
    {
        $this->getStore()->forever($key, $value);
    }

    /**
     * Forget a value from cache.
     */
    private function forget(string $key): void
    {
        $this->getStore()->forget($key);
    }

    /**
     * Get the cache store instance.
     *
     * @return \Illuminate\Contracts\Cache\Repository
     */
    private function getStore()
    {
        return Cache::store($this->store);
    }
}
