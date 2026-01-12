<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Sync;

use Illuminate\Support\Facades\Schema;
use SapB1\Toolkit\Sync\Exceptions\SyncException;

/**
 * Registry for managing sync entity configurations.
 */
final class SyncRegistry
{
    /**
     * Registered sync configurations.
     *
     * @var array<string, SyncConfig>
     */
    private array $configs = [];

    /**
     * Register a sync configuration.
     */
    public function register(string $entity, SyncConfig $config): self
    {
        $this->configs[$entity] = $config;

        return $this;
    }

    /**
     * Register multiple configurations.
     *
     * @param  array<string, SyncConfig>  $configs
     */
    public function registerMany(array $configs): self
    {
        foreach ($configs as $entity => $config) {
            $this->register($entity, $config);
        }

        return $this;
    }

    /**
     * Register from config array.
     *
     * @param  array<string, array<string, mixed>>  $configArray
     */
    public function registerFromConfig(array $configArray): self
    {
        foreach ($configArray as $entity => $settings) {
            if (! isset($settings['enabled']) || $settings['enabled'] !== true) {
                continue;
            }

            // Get base config from predefined or create new
            $baseConfig = SyncConfig::for($entity);

            if ($baseConfig === null) {
                continue;
            }

            // Override with config settings
            $config = $baseConfig->with($settings);
            $this->register($entity, $config);
        }

        return $this;
    }

    /**
     * Get configuration for an entity.
     *
     * @throws SyncException
     */
    public function get(string $entity): SyncConfig
    {
        // Check registered configs first
        if (isset($this->configs[$entity])) {
            return $this->configs[$entity];
        }

        // Fall back to predefined config
        $config = SyncConfig::for($entity);

        if ($config === null) {
            throw SyncException::entityNotConfigured($entity);
        }

        return $config;
    }

    /**
     * Try to get configuration without throwing.
     */
    public function find(string $entity): ?SyncConfig
    {
        if (isset($this->configs[$entity])) {
            return $this->configs[$entity];
        }

        return SyncConfig::for($entity);
    }

    /**
     * Check if an entity is registered.
     */
    public function has(string $entity): bool
    {
        return isset($this->configs[$entity]) || SyncConfig::isAvailable($entity);
    }

    /**
     * Get all registered configurations.
     *
     * @return array<string, SyncConfig>
     */
    public function all(): array
    {
        return $this->configs;
    }

    /**
     * Get all registered entity names.
     *
     * @return array<string>
     */
    public function entities(): array
    {
        return array_keys($this->configs);
    }

    /**
     * Remove a configuration.
     */
    public function forget(string $entity): self
    {
        unset($this->configs[$entity]);

        return $this;
    }

    /**
     * Clear all configurations.
     */
    public function clear(): self
    {
        $this->configs = [];

        return $this;
    }

    /**
     * Check if entity's table exists in database.
     */
    public function tableExists(string $entity): bool
    {
        $config = $this->find($entity);

        if ($config === null) {
            return false;
        }

        return Schema::hasTable($config->table);
    }

    /**
     * Get entities with existing tables.
     *
     * @return array<string>
     */
    public function entitiesWithTables(): array
    {
        $entities = [];

        foreach (SyncConfig::availableEntities() as $entity) {
            if ($this->tableExists($entity)) {
                $entities[] = $entity;
            }
        }

        return $entities;
    }

    /**
     * Get entities without tables.
     *
     * @return array<string>
     */
    public function entitiesWithoutTables(): array
    {
        $entities = [];

        foreach (SyncConfig::availableEntities() as $entity) {
            if (! $this->tableExists($entity)) {
                $entities[] = $entity;
            }
        }

        return $entities;
    }

    /**
     * Auto-register entities that have tables in the database.
     */
    public function autoRegister(): self
    {
        foreach ($this->entitiesWithTables() as $entity) {
            $config = SyncConfig::for($entity);

            if ($config !== null && ! isset($this->configs[$entity])) {
                $this->register($entity, $config);
            }
        }

        return $this;
    }

    /**
     * Get count of registered entities.
     */
    public function count(): int
    {
        return count($this->configs);
    }

    /**
     * Check if registry is empty.
     */
    public function isEmpty(): bool
    {
        return $this->count() === 0;
    }

    /**
     * Get summary of registered entities.
     *
     * @return array<string, array<string, mixed>>
     */
    public function summary(): array
    {
        $summary = [];

        foreach ($this->configs as $entity => $config) {
            $summary[$entity] = [
                'table' => $config->table,
                'primaryKey' => $config->primaryKey,
                'columns' => count($config->columns),
                'syncLines' => $config->syncLines,
                'tableExists' => Schema::hasTable($config->table),
            ];
        }

        return $summary;
    }
}
