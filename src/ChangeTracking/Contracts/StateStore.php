<?php

declare(strict_types=1);

namespace SapB1\Toolkit\ChangeTracking\Contracts;

/**
 * Interface for storing watcher state.
 */
interface StateStore
{
    /**
     * Get the last check timestamp for an entity.
     */
    public function getLastCheckTime(string $entity): ?string;

    /**
     * Set the last check timestamp for an entity.
     */
    public function setLastCheckTime(string $entity, string $timestamp): void;

    /**
     * Get the last known primary key for an entity (for created detection).
     */
    public function getLastPrimaryKey(string $entity): int|string|null;

    /**
     * Set the last known primary key for an entity.
     */
    public function setLastPrimaryKey(string $entity, int|string $key): void;

    /**
     * Get all known primary keys for an entity (for deleted detection).
     *
     * @return array<int, int|string>
     */
    public function getKnownKeys(string $entity): array;

    /**
     * Set all known primary keys for an entity.
     *
     * @param  array<int, int|string>  $keys
     */
    public function setKnownKeys(string $entity, array $keys): void;

    /**
     * Clear all state for an entity.
     */
    public function clear(string $entity): void;

    /**
     * Clear all state for all entities.
     */
    public function clearAll(): void;
}
