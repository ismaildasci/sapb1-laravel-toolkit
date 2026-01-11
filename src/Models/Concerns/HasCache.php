<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Models\Concerns;

use SapB1\Toolkit\Cache\CacheManager;
use SapB1\Toolkit\Cache\CacheResolver;

/**
 * Provides cache configuration for models.
 *
 * This trait allows models to define their own cache settings
 * that take precedence over entity-level and global config.
 *
 * Usage:
 * ```php
 * class Item extends SapB1Model
 * {
 *     use HasCache;
 *
 *     protected static bool $cacheEnabled = true;
 *     protected static int $cacheTtl = 7200; // 2 hours
 * }
 * ```
 *
 * @phpstan-require-extends \SapB1\Toolkit\Models\SapB1Model
 */
trait HasCache
{
    /**
     * Whether caching is enabled for this model.
     * Override in subclass to enable/disable cache at model level.
     */
    // protected static bool $cacheEnabled = false;

    /**
     * Cache TTL for this model in seconds.
     * Override in subclass to customize TTL.
     */
    // protected static int $cacheTtl = 3600;

    /**
     * Check if caching is enabled for this model.
     */
    public static function isCacheEnabled(): ?bool
    {
        if (property_exists(static::class, 'cacheEnabled')) {
            return static::$cacheEnabled;
        }

        return null;
    }

    /**
     * Get the cache TTL for this model.
     */
    public static function getCacheTtl(): ?int
    {
        if (property_exists(static::class, 'cacheTtl')) {
            return static::$cacheTtl;
        }

        return null;
    }

    /**
     * Get a cache resolver configured for this model.
     */
    public static function getCacheResolver(): CacheResolver
    {
        $resolver = new CacheResolver(static::getEntity());
        $resolver->setModelEnabled(static::isCacheEnabled());

        return $resolver;
    }

    /**
     * Get a cache manager for this model.
     */
    public static function getCacheManager(): CacheManager
    {
        return new CacheManager(static::getCacheResolver());
    }

    /**
     * Invalidate all cached data for this model.
     */
    public static function flushCache(): bool
    {
        return CacheManager::invalidateEntity(static::getEntity());
    }

    /**
     * Invalidate cached data for a specific record.
     */
    public static function forgetCached(int|string $id): bool
    {
        $manager = static::getCacheManager();
        $key = CacheManager::generateRecordKey(static::getEntity(), $id);

        return $manager->forget($key);
    }
}
