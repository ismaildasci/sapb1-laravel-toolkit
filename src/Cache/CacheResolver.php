<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Cache;

/**
 * Resolves cache configuration based on priority.
 *
 * Priority Order (Highest to Lowest):
 * 1. Query-level cache(ttl) → Explicitly enables cache
 * 2. Query-level noCache() → Explicitly disables cache
 * 3. Model-level $cacheEnabled property
 * 4. Entity config → config('laravel-toolkit.cache.entities.{Entity}.enabled')
 * 5. Global config → config('laravel-toolkit.cache.enabled') (default: false)
 */
final class CacheResolver
{
    /**
     * Query-level cache state: true = cache(), false = noCache(), null = not set.
     */
    private ?bool $queryOverride = null;

    /**
     * Query-level TTL override.
     */
    private ?int $queryTtl = null;

    /**
     * The entity name for resolution.
     */
    private string $entity = '';

    /**
     * Model-level cache state.
     */
    private ?bool $modelEnabled = null;

    /**
     * Create a new cache resolver instance.
     */
    public function __construct(string $entity = '')
    {
        $this->entity = $entity;
    }

    /**
     * Set query-level cache enable with optional TTL.
     */
    public function enableForQuery(?int $ttl = null): self
    {
        $this->queryOverride = true;
        $this->queryTtl = $ttl;

        return $this;
    }

    /**
     * Set query-level cache disable.
     */
    public function disableForQuery(): self
    {
        $this->queryOverride = false;
        $this->queryTtl = null;

        return $this;
    }

    /**
     * Set model-level cache state.
     */
    public function setModelEnabled(?bool $enabled): self
    {
        $this->modelEnabled = $enabled;

        return $this;
    }

    /**
     * Set the entity name.
     */
    public function setEntity(string $entity): self
    {
        $this->entity = $entity;

        return $this;
    }

    /**
     * Reset query-level overrides.
     */
    public function reset(): self
    {
        $this->queryOverride = null;
        $this->queryTtl = null;

        return $this;
    }

    /**
     * Determine if caching should be used.
     */
    public function shouldCache(): bool
    {
        // Priority 1 & 2: Query-level override
        if ($this->queryOverride !== null) {
            return $this->queryOverride;
        }

        // Priority 3: Model-level setting
        if ($this->modelEnabled !== null) {
            return $this->modelEnabled;
        }

        // Priority 4: Entity-specific config
        if ($this->entity !== '') {
            $entityConfig = $this->getEntityConfig($this->entity);
            if ($entityConfig !== null) {
                return $entityConfig;
            }
        }

        // Priority 5: Global config (default: false)
        return $this->getGlobalEnabled();
    }

    /**
     * Get the TTL to use for caching.
     */
    public function getTtl(): int
    {
        // Query-level TTL has highest priority
        if ($this->queryTtl !== null) {
            return $this->queryTtl;
        }

        // Entity-specific TTL
        if ($this->entity !== '') {
            $entityTtl = $this->getEntityTtl($this->entity);
            if ($entityTtl !== null) {
                return $entityTtl;
            }
        }

        // Global TTL (default: 3600)
        return $this->getGlobalTtl();
    }

    /**
     * Get the cache prefix.
     */
    public function getPrefix(): string
    {
        return config('laravel-toolkit.cache.prefix', 'sapb1_toolkit_');
    }

    /**
     * Get the cache store name.
     */
    public function getStore(): ?string
    {
        return config('laravel-toolkit.cache.store');
    }

    /**
     * Check if entity-specific config exists.
     */
    public function hasEntityConfig(string $entity): bool
    {
        return config("laravel-toolkit.cache.entities.{$entity}") !== null;
    }

    /**
     * Get entity-specific enabled setting.
     */
    private function getEntityConfig(string $entity): ?bool
    {
        $value = config("laravel-toolkit.cache.entities.{$entity}.enabled");

        return $value !== null ? (bool) $value : null;
    }

    /**
     * Get entity-specific TTL.
     */
    private function getEntityTtl(string $entity): ?int
    {
        $value = config("laravel-toolkit.cache.entities.{$entity}.ttl");

        return $value !== null ? (int) $value : null;
    }

    /**
     * Get global cache enabled setting.
     */
    private function getGlobalEnabled(): bool
    {
        return (bool) config('laravel-toolkit.cache.enabled', false);
    }

    /**
     * Get global TTL setting.
     */
    private function getGlobalTtl(): int
    {
        return (int) config('laravel-toolkit.cache.ttl', 3600);
    }

    /**
     * Get cache tags for the entity.
     *
     * @return array<int, string>
     */
    public function getTags(): array
    {
        $tags = [$this->getPrefix().'all'];

        if ($this->entity !== '') {
            $tags[] = $this->getPrefix().$this->entity;
        }

        return $tags;
    }

    /**
     * Get the current state for debugging.
     *
     * @return array<string, mixed>
     */
    public function getState(): array
    {
        return [
            'entity' => $this->entity,
            'query_override' => $this->queryOverride,
            'query_ttl' => $this->queryTtl,
            'model_enabled' => $this->modelEnabled,
            'should_cache' => $this->shouldCache(),
            'ttl' => $this->getTtl(),
            'tags' => $this->getTags(),
        ];
    }
}
