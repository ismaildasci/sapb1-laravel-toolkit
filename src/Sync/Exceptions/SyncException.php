<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Sync\Exceptions;

use Exception;

/**
 * Exception thrown when sync operations fail.
 */
final class SyncException extends Exception
{
    /**
     * Create a new entity not configured exception.
     */
    public static function entityNotConfigured(string $entity): self
    {
        return new self(
            "Entity [{$entity}] is not configured for sync. Use SyncRegistry::register() or run 'php artisan sapb1:sync-setup {$entity}'."
        );
    }

    /**
     * Create a new table not found exception.
     */
    public static function tableNotFound(string $table): self
    {
        return new self(
            "Database table [{$table}] does not exist. Run migrations first."
        );
    }

    /**
     * Create a new migration already exists exception.
     */
    public static function migrationAlreadyExists(string $entity, string $path): self
    {
        return new self(
            "Migration for [{$entity}] already exists at [{$path}]. Use --force to overwrite."
        );
    }

    /**
     * Create a new connection failed exception.
     */
    public static function connectionFailed(string $entity, string $reason): self
    {
        return new self(
            "Failed to connect to SAP B1 for syncing [{$entity}]: {$reason}"
        );
    }

    /**
     * Create a new sync failed exception.
     */
    public static function syncFailed(string $entity, string $reason): self
    {
        return new self(
            "Failed to sync [{$entity}]: {$reason}"
        );
    }

    /**
     * Create a new upsert failed exception.
     */
    public static function upsertFailed(string $table, string $reason): self
    {
        return new self(
            "Failed to upsert data into [{$table}]: {$reason}"
        );
    }

    /**
     * Create a new delete detection failed exception.
     */
    public static function deleteDetectionFailed(string $entity, string $reason): self
    {
        return new self(
            "Failed to detect deleted records for [{$entity}]: {$reason}"
        );
    }

    /**
     * Create a new invalid configuration exception.
     */
    public static function invalidConfiguration(string $entity, string $reason): self
    {
        return new self(
            "Invalid sync configuration for [{$entity}]: {$reason}"
        );
    }

    /**
     * Create a new stub not found exception.
     */
    public static function stubNotFound(string $entity): self
    {
        return new self(
            "Migration stub for [{$entity}] not found. This entity may not be supported yet."
        );
    }

    /**
     * Create a new metadata table missing exception.
     */
    public static function metadataTableMissing(): self
    {
        return new self(
            "Sync metadata table [sapb1_sync_metadata] does not exist. Run 'php artisan sapb1:sync-setup' first."
        );
    }
}
