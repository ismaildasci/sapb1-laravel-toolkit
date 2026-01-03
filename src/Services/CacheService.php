<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Services;

use Illuminate\Support\Facades\Cache;

/**
 * Service for caching frequently accessed SAP B1 data.
 *
 * Provides methods to cache and retrieve commonly used data such as
 * items, business partners, warehouses, and chart of accounts.
 * Reduces API calls and improves performance.
 *
 * @example
 * ```php
 * $cache = app(CacheService::class);
 * $items = $cache->items(); // Cached for 1 hour
 * $cache->forget('items'); // Clear cache
 * ```
 */
final class CacheService extends BaseService
{
    /**
     * Default cache TTL in seconds (1 hour).
     */
    protected int $ttl = 3600;

    /**
     * Cache key prefix.
     */
    protected string $prefix = 'sapb1_toolkit_';

    /**
     * Set the cache TTL.
     *
     * @param  int  $seconds  TTL in seconds
     */
    public function ttl(int $seconds): static
    {
        $this->ttl = $seconds;

        return $this;
    }

    /**
     * Get all items (cached).
     *
     * @param  array<string>|null  $select  Fields to select
     * @return array<array<string, mixed>>
     */
    public function items(?array $select = null): array
    {
        $key = $this->key('items', $select);

        return Cache::remember($key, $this->ttl, function () use ($select) {
            $query = $this->client()
                ->service('Items')
                ->queryBuilder();

            if ($select !== null) {
                $query->select($select);
            }

            return $this->fetchAll($query);
        });
    }

    /**
     * Get all business partners (cached).
     *
     * @param  string|null  $cardType  Filter by card type (C=Customer, S=Supplier, L=Lead)
     * @param  array<string>|null  $select  Fields to select
     * @return array<array<string, mixed>>
     */
    public function businessPartners(?string $cardType = null, ?array $select = null): array
    {
        $key = $this->key('business_partners', [$cardType, $select]);

        return Cache::remember($key, $this->ttl, function () use ($cardType, $select) {
            $query = $this->client()
                ->service('BusinessPartners')
                ->queryBuilder();

            if ($cardType !== null) {
                $query->filter("CardType eq '{$cardType}'");
            }

            if ($select !== null) {
                $query->select($select);
            }

            return $this->fetchAll($query);
        });
    }

    /**
     * Get all customers (cached).
     *
     * @param  array<string>|null  $select  Fields to select
     * @return array<array<string, mixed>>
     */
    public function customers(?array $select = null): array
    {
        return $this->businessPartners('C', $select);
    }

    /**
     * Get all suppliers (cached).
     *
     * @param  array<string>|null  $select  Fields to select
     * @return array<array<string, mixed>>
     */
    public function suppliers(?array $select = null): array
    {
        return $this->businessPartners('S', $select);
    }

    /**
     * Get all warehouses (cached).
     *
     * @param  array<string>|null  $select  Fields to select
     * @return array<array<string, mixed>>
     */
    public function warehouses(?array $select = null): array
    {
        $key = $this->key('warehouses', $select);

        return Cache::remember($key, $this->ttl, function () use ($select) {
            $query = $this->client()
                ->service('Warehouses')
                ->queryBuilder();

            if ($select !== null) {
                $query->select($select);
            }

            return $this->fetchAll($query);
        });
    }

    /**
     * Get chart of accounts (cached).
     *
     * @param  array<string>|null  $select  Fields to select
     * @return array<array<string, mixed>>
     */
    public function chartOfAccounts(?array $select = null): array
    {
        $key = $this->key('chart_of_accounts', $select);

        return Cache::remember($key, $this->ttl, function () use ($select) {
            $query = $this->client()
                ->service('ChartOfAccounts')
                ->queryBuilder();

            if ($select !== null) {
                $query->select($select);
            }

            return $this->fetchAll($query);
        });
    }

    /**
     * Get all sales persons (cached).
     *
     * @return array<array<string, mixed>>
     */
    public function salesPersons(): array
    {
        $key = $this->key('sales_persons');

        return Cache::remember($key, $this->ttl, function () {
            return $this->fetchAll(
                $this->client()
                    ->service('SalesPersons')
                    ->queryBuilder()
            );
        });
    }

    /**
     * Get all price lists (cached).
     *
     * @return array<array<string, mixed>>
     */
    public function priceLists(): array
    {
        $key = $this->key('price_lists');

        return Cache::remember($key, $this->ttl, function () {
            return $this->fetchAll(
                $this->client()
                    ->service('PriceLists')
                    ->queryBuilder()
            );
        });
    }

    /**
     * Get all payment terms (cached).
     *
     * @return array<array<string, mixed>>
     */
    public function paymentTerms(): array
    {
        $key = $this->key('payment_terms');

        return Cache::remember($key, $this->ttl, function () {
            return $this->fetchAll(
                $this->client()
                    ->service('PaymentTermsTypes')
                    ->queryBuilder()
            );
        });
    }

    /**
     * Get all projects (cached).
     *
     * @return array<array<string, mixed>>
     */
    public function projects(): array
    {
        $key = $this->key('projects');

        return Cache::remember($key, $this->ttl, function () {
            return $this->fetchAll(
                $this->client()
                    ->service('Projects')
                    ->queryBuilder()
            );
        });
    }

    /**
     * Forget a specific cache key.
     *
     * @param  string  $name  Cache name (items, warehouses, etc.)
     */
    public function forget(string $name): bool
    {
        return Cache::forget($this->prefix.$name);
    }

    /**
     * Forget all toolkit cache.
     */
    public function flush(): void
    {
        $keys = [
            'items',
            'business_partners',
            'warehouses',
            'chart_of_accounts',
            'sales_persons',
            'price_lists',
            'payment_terms',
            'projects',
        ];

        foreach ($keys as $key) {
            Cache::forget($this->prefix.$key);
        }
    }

    /**
     * Warm up cache with commonly used data.
     *
     * @param  array<string>|null  $only  Only warm these caches
     */
    public function warm(?array $only = null): void
    {
        $methods = [
            'items' => fn () => $this->items(['ItemCode', 'ItemName', 'ItemType']),
            'customers' => fn () => $this->customers(['CardCode', 'CardName']),
            'suppliers' => fn () => $this->suppliers(['CardCode', 'CardName']),
            'warehouses' => fn () => $this->warehouses(['WarehouseCode', 'WarehouseName']),
            'salesPersons' => fn () => $this->salesPersons(),
            'priceLists' => fn () => $this->priceLists(),
        ];

        foreach ($methods as $name => $callback) {
            if ($only === null || in_array($name, $only, true)) {
                $callback();
            }
        }
    }

    /**
     * Generate a cache key.
     *
     * @param  mixed  $params  Additional parameters for key uniqueness
     */
    protected function key(string $name, mixed $params = null): string
    {
        $key = $this->prefix.$this->connection.'_'.$name;

        if ($params !== null) {
            $key .= '_'.md5(serialize($params));
        }

        return $key;
    }

    /**
     * Fetch all records with pagination.
     *
     * @return array<array<string, mixed>>
     */
    protected function fetchAll(mixed $query): array
    {
        $results = [];
        $skip = 0;
        $top = 1000;

        do {
            $response = $query->skip($skip)->top($top)->get();
            $items = $response['value'] ?? [];
            $results = array_merge($results, $items);
            $skip += $top;
        } while (count($items) === $top);

        return $results;
    }
}
