<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Services;

use DateTimeInterface;
use SapB1\Client\SqlQueryBuilder;
use SapB1\Exceptions\SqlQueryException;

/**
 * Service for executing SQL queries defined in SAP B1.
 *
 * Provides a fluent interface for executing predefined SQL queries
 * (stored in SAP B1 via Tools > Queries > Query Manager) through
 * the Service Layer SQLQueries endpoint.
 *
 * Uses the SDK's SqlQueryBuilder which interacts with the
 * SQLQueries endpoint in Service Layer.
 *
 * @example
 * ```php
 * $service = app(SqlQueryService::class);
 *
 * // Execute a query with parameters
 * $results = $service->query('MonthlySalesReport')
 *     ->param('Year', 2024)
 *     ->param('Month', 12)
 *     ->get();
 *
 * // With pagination
 * $results = $service->query('AllCustomers')
 *     ->limit(100)
 *     ->offset(0)
 *     ->get();
 * ```
 */
final class SqlQueryService extends BaseService
{
    /**
     * Start building a SQL query.
     *
     * @param  string  $queryName  The name of the query defined in SAP B1
     */
    public function query(string $queryName): SqlQueryServiceBuilder
    {
        $sqlBuilder = $this->client()->sql($queryName);

        return new SqlQueryServiceBuilder($sqlBuilder);
    }

    /**
     * Execute a query immediately with optional parameters.
     *
     * @param  string  $queryName  The name of the query
     * @param  array<string, mixed>  $params  Query parameters
     * @return array<int, array<string, mixed>>
     *
     * @throws SqlQueryException
     */
    public function execute(string $queryName, array $params = []): array
    {
        $builder = $this->query($queryName);

        foreach ($params as $name => $value) {
            $builder->param($name, $value);
        }

        return $builder->get();
    }

    /**
     * Execute a query and get only the first result.
     *
     * @param  string  $queryName  The name of the query
     * @param  array<string, mixed>  $params  Query parameters
     * @return array<string, mixed>|null
     *
     * @throws SqlQueryException
     */
    public function first(string $queryName, array $params = []): ?array
    {
        $builder = $this->query($queryName);

        foreach ($params as $name => $value) {
            $builder->param($name, $value);
        }

        return $builder->first();
    }

    /**
     * Get the count of results for a query.
     *
     * @param  string  $queryName  The name of the query
     * @param  array<string, mixed>  $params  Query parameters
     *
     * @throws SqlQueryException
     */
    public function count(string $queryName, array $params = []): int
    {
        $builder = $this->query($queryName);

        foreach ($params as $name => $value) {
            $builder->param($name, $value);
        }

        return $builder->count();
    }

    /**
     * Check if a query returns any results.
     *
     * @param  string  $queryName  The name of the query
     * @param  array<string, mixed>  $params  Query parameters
     *
     * @throws SqlQueryException
     */
    public function exists(string $queryName, array $params = []): bool
    {
        return $this->count($queryName, $params) > 0;
    }

    /**
     * Execute a query with date range parameters.
     * Convenience method for common report patterns.
     *
     * @param  string  $queryName  The name of the query
     * @param  string|DateTimeInterface  $fromDate  Start date
     * @param  string|DateTimeInterface  $toDate  End date
     * @param  array<string, mixed>  $additionalParams  Additional parameters
     * @return array<int, array<string, mixed>>
     *
     * @throws SqlQueryException
     */
    public function executeWithDateRange(
        string $queryName,
        string|DateTimeInterface $fromDate,
        string|DateTimeInterface $toDate,
        array $additionalParams = []
    ): array {
        $params = array_merge([
            'FromDate' => $fromDate,
            'ToDate' => $toDate,
        ], $additionalParams);

        return $this->execute($queryName, $params);
    }
}

/**
 * Fluent builder for SQL queries.
 *
 * Provides a chainable interface for building and executing SQL queries.
 */
final class SqlQueryServiceBuilder
{
    public function __construct(
        private readonly SqlQueryBuilder $builder
    ) {}

    /**
     * Set a query parameter.
     *
     * @param  string  $name  Parameter name
     * @param  mixed  $value  Parameter value
     */
    public function param(string $name, mixed $value): self
    {
        $this->builder->param($name, $value);

        return $this;
    }

    /**
     * Set multiple parameters at once.
     *
     * @param  array<string, mixed>  $params  Parameters
     */
    public function params(array $params): self
    {
        $this->builder->params($params);

        return $this;
    }

    /**
     * Set the limit (top) for results.
     *
     * @param  int  $limit  Maximum number of results
     */
    public function limit(int $limit): self
    {
        $this->builder->limit($limit);

        return $this;
    }

    /**
     * Alias for limit().
     *
     * @param  int  $value  Maximum number of results
     */
    public function top(int $value): self
    {
        return $this->limit($value);
    }

    /**
     * Set the offset (skip) for results.
     *
     * @param  int  $offset  Number of results to skip
     */
    public function offset(int $offset): self
    {
        $this->builder->offset($offset);

        return $this;
    }

    /**
     * Alias for offset().
     *
     * @param  int  $value  Number of results to skip
     */
    public function skip(int $value): self
    {
        return $this->offset($value);
    }

    /**
     * Set pagination parameters.
     *
     * @param  int  $page  Page number (1-based)
     * @param  int  $perPage  Items per page
     */
    public function paginate(int $page, int $perPage = 20): self
    {
        $this->limit($perPage);
        $this->offset(($page - 1) * $perPage);

        return $this;
    }

    /**
     * Execute the query and get results.
     *
     * @return array<int, array<string, mixed>>
     *
     * @throws SqlQueryException
     */
    public function get(): array
    {
        return $this->builder->execute();
    }

    /**
     * Execute the query and get only the first result.
     *
     * @return array<string, mixed>|null
     *
     * @throws SqlQueryException
     */
    public function first(): ?array
    {
        return $this->builder->first();
    }

    /**
     * Get the count of results.
     *
     * @throws SqlQueryException
     */
    public function count(): int
    {
        return $this->builder->count();
    }

    /**
     * Check if the query returns any results.
     *
     * @throws SqlQueryException
     */
    public function exists(): bool
    {
        return $this->count() > 0;
    }

    /**
     * Execute and get results as a Laravel collection.
     *
     * @return \Illuminate\Support\Collection<int, array<string, mixed>>
     *
     * @throws SqlQueryException
     */
    public function collect(): \Illuminate\Support\Collection
    {
        return collect($this->get());
    }

    /**
     * Execute and pluck a single column.
     *
     * @param  string  $column  Column to pluck
     * @return \Illuminate\Support\Collection<int, mixed>
     *
     * @throws SqlQueryException
     */
    public function pluck(string $column): \Illuminate\Support\Collection
    {
        return $this->collect()->pluck($column);
    }

    /**
     * Execute and sum a column.
     *
     * @param  string  $column  Column to sum
     *
     * @throws SqlQueryException
     */
    public function sum(string $column): float|int
    {
        return $this->collect()->sum($column);
    }

    /**
     * Execute and get average of a column.
     *
     * @param  string  $column  Column to average
     *
     * @throws SqlQueryException
     */
    public function avg(string $column): float|int|null
    {
        return $this->collect()->avg($column);
    }

    /**
     * Execute and get max value of a column.
     *
     * @param  string  $column  Column
     *
     * @throws SqlQueryException
     */
    public function max(string $column): mixed
    {
        return $this->collect()->max($column);
    }

    /**
     * Execute and get min value of a column.
     *
     * @param  string  $column  Column
     *
     * @throws SqlQueryException
     */
    public function min(string $column): mixed
    {
        return $this->collect()->min($column);
    }

    /**
     * Execute and group results by a column.
     *
     * @param  string  $column  Column to group by
     * @return \Illuminate\Support\Collection<string|int, \Illuminate\Support\Collection<int, array<string, mixed>>>
     *
     * @throws SqlQueryException
     */
    public function groupBy(string $column): \Illuminate\Support\Collection
    {
        return $this->collect()->groupBy($column);
    }

    /**
     * Iterate through results with a callback.
     *
     * @param  callable(array<string, mixed>, int): void  $callback
     *
     * @throws SqlQueryException
     */
    public function each(callable $callback): void
    {
        $this->collect()->each($callback);
    }

    /**
     * Map results through a callback.
     *
     * @param  callable(array<string, mixed>): mixed  $callback
     * @return \Illuminate\Support\Collection<int, mixed>
     *
     * @throws SqlQueryException
     */
    public function map(callable $callback): \Illuminate\Support\Collection
    {
        return $this->collect()->map($callback);
    }

    /**
     * Filter results through a callback.
     *
     * @param  callable(array<string, mixed>): bool  $callback
     * @return \Illuminate\Support\Collection<int, array<string, mixed>>
     *
     * @throws SqlQueryException
     */
    public function filter(callable $callback): \Illuminate\Support\Collection
    {
        return $this->collect()->filter($callback);
    }
}
