<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Exceptions;

use Exception;

/**
 * Exception thrown when cache operations fail.
 */
final class CacheException extends Exception
{
    /**
     * Create a new cache operation failed exception.
     */
    public static function operationFailed(string $operation, string $reason): self
    {
        return new self(
            "Cache {$operation} failed: {$reason}"
        );
    }

    /**
     * Create a new cache read failed exception.
     */
    public static function readFailed(string $key, string $reason): self
    {
        return new self(
            "Failed to read cache key [{$key}]: {$reason}"
        );
    }

    /**
     * Create a new cache write failed exception.
     */
    public static function writeFailed(string $key, string $reason): self
    {
        return new self(
            "Failed to write cache key [{$key}]: {$reason}"
        );
    }

    /**
     * Create a new cache flush failed exception.
     */
    public static function flushFailed(string $entity, string $reason): self
    {
        return new self(
            "Failed to flush cache for entity [{$entity}]: {$reason}"
        );
    }

    /**
     * Create a new cache store not supported exception.
     */
    public static function storeNotSupported(string $store): self
    {
        return new self(
            "Cache store [{$store}] does not support tagging. Use Redis or another tag-supporting driver."
        );
    }

    /**
     * Create a new invalid TTL exception.
     */
    public static function invalidTtl(int $ttl): self
    {
        return new self(
            "Invalid cache TTL [{$ttl}]. TTL must be a positive integer."
        );
    }
}
