<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Cache;

use Illuminate\Contracts\Cache\Repository;
use Illuminate\Support\Facades\Cache;

/**
 * Manages cache operations for SAP B1 entities.
 *
 * Provides a clean interface for caching with tag support,
 * entity-specific invalidation, and Laravel cache integration.
 */
final class CacheManager
{
    /**
     * The cache resolver instance.
     */
    private CacheResolver $resolver;

    /**
     * Create a new cache manager instance.
     */
    public function __construct(CacheResolver $resolver)
    {
        $this->resolver = $resolver;
    }

    /**
     * Create a new cache manager for an entity.
     */
    public static function for(string $entity): self
    {
        return new self(new CacheResolver($entity));
    }

    /**
     * Get the cache resolver.
     */
    public function getResolver(): CacheResolver
    {
        return $this->resolver;
    }

    /**
     * Get a value from cache or execute callback.
     *
     * @template T
     *
     * @param  string  $key  The cache key
     * @param  \Closure(): T  $callback  The callback to execute if not cached
     * @return T
     */
    public function remember(string $key, \Closure $callback): mixed
    {
        if (! $this->resolver->shouldCache()) {
            return $callback();
        }

        $fullKey = $this->buildKey($key);
        $ttl = $this->resolver->getTtl();

        return $this->getStore()->remember($fullKey, $ttl, $callback);
    }

    /**
     * Get a value from cache.
     */
    public function get(string $key, mixed $default = null): mixed
    {
        if (! $this->resolver->shouldCache()) {
            return $default;
        }

        return $this->getStore()->get($this->buildKey($key), $default);
    }

    /**
     * Put a value in cache.
     */
    public function put(string $key, mixed $value, ?int $ttl = null): bool
    {
        if (! $this->resolver->shouldCache()) {
            return false;
        }

        $fullKey = $this->buildKey($key);
        $seconds = $ttl ?? $this->resolver->getTtl();

        return $this->getStore()->put($fullKey, $value, $seconds);
    }

    /**
     * Check if a key exists in cache.
     */
    public function has(string $key): bool
    {
        if (! $this->resolver->shouldCache()) {
            return false;
        }

        return $this->getStore()->has($this->buildKey($key));
    }

    /**
     * Remove a value from cache.
     */
    public function forget(string $key): bool
    {
        return $this->getStore()->forget($this->buildKey($key));
    }

    /**
     * Flush all cache for the entity.
     */
    public function flush(): bool
    {
        // If store supports tags, use tag-based flushing
        if ($this->supportsTagging()) {
            $tags = $this->resolver->getTags();

            /** @var \Illuminate\Cache\TaggedCache $taggedCache */
            $taggedCache = Cache::store($this->resolver->getStore())->tags($tags);

            return $taggedCache->flush();
        }

        // Fallback: can't flush without tags
        return false;
    }

    /**
     * Flush all toolkit cache.
     */
    public static function flushAll(): bool
    {
        $resolver = new CacheResolver;
        $prefix = $resolver->getPrefix();

        if (self::storeSupportsTagging($resolver->getStore())) {
            /** @var \Illuminate\Cache\TaggedCache $taggedCache */
            $taggedCache = Cache::store($resolver->getStore())->tags([$prefix.'all']);

            return $taggedCache->flush();
        }

        return false;
    }

    /**
     * Invalidate cache for specific entity.
     */
    public static function invalidateEntity(string $entity): bool
    {
        $resolver = new CacheResolver($entity);
        $prefix = $resolver->getPrefix();

        if (self::storeSupportsTagging($resolver->getStore())) {
            /** @var \Illuminate\Cache\TaggedCache $taggedCache */
            $taggedCache = Cache::store($resolver->getStore())->tags([$prefix.$entity]);

            return $taggedCache->flush();
        }

        return false;
    }

    /**
     * Build the full cache key.
     */
    private function buildKey(string $key): string
    {
        $prefix = $this->resolver->getPrefix();
        $entity = $this->resolver->getState()['entity'] ?? '';

        if ($entity !== '') {
            return "{$prefix}{$entity}:{$key}";
        }

        return "{$prefix}{$key}";
    }

    /**
     * Get the cache store instance.
     */
    private function getStore(): Repository
    {
        $storeName = $this->resolver->getStore();

        return Cache::store($storeName);
    }

    /**
     * Check if the current store supports tagging.
     */
    private function supportsTagging(): bool
    {
        return self::storeSupportsTagging($this->resolver->getStore());
    }

    /**
     * Check if a store supports tagging.
     */
    private static function storeSupportsTagging(?string $store): bool
    {
        try {
            $cacheStore = Cache::store($store);
            $driver = $cacheStore->getStore();

            return $driver instanceof \Illuminate\Cache\TaggableStore;
        } catch (\Exception) {
            return false;
        }
    }

    /**
     * Generate a cache key for a query.
     *
     * @param  array<string, mixed>  $params  Query parameters
     */
    public static function generateQueryKey(string $entity, array $params): string
    {
        // Sort parameters for consistent key generation
        ksort($params);

        $hash = md5($entity.':'.serialize($params));

        return "query:{$hash}";
    }

    /**
     * Generate a cache key for a single record.
     */
    public static function generateRecordKey(string $entity, int|string $id): string
    {
        return "{$entity}:record:{$id}";
    }
}
