<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Services;

use DateTimeInterface;
use Illuminate\Support\Collection;
use SapB1\Client\SemanticLayerClient;
use SapB1\Exceptions\ServiceLayerException;

/**
 * Service for executing Semantic Layer queries in SAP B1.
 *
 * Provides a fluent interface for executing analytics queries through
 * the Service Layer's Semantic Layer (sml.svc) endpoint. The Semantic
 * Layer allows cross-join queries with dimensions, measures, and filters.
 *
 * Uses the SDK's SemanticLayerClient which interacts with the
 * sml.svc endpoint in Service Layer.
 *
 * @example
 * ```php
 * $service = app(SemanticQueryService::class);
 *
 * // Sales analysis query
 * $results = $service->query('SalesAnalysis')
 *     ->dimensions('CardCode', 'ItemCode')
 *     ->measures('DocTotal', 'Quantity')
 *     ->filter('DocDate', 'ge', '2024-01-01')
 *     ->orderBy('DocTotal', 'desc')
 *     ->limit(100)
 *     ->get();
 *
 * // With date range
 * $results = $service->query('SalesAnalysis')
 *     ->dimensions('CardCode')
 *     ->measures('DocTotal')
 *     ->whereBetween('DocDate', '2024-01-01', '2024-12-31')
 *     ->get();
 *
 * // Get first result
 * $result = $service->query('CustomerAnalysis')
 *     ->where('CardCode', 'C001')
 *     ->first();
 * ```
 */
final class SemanticQueryService extends BaseService
{
    /**
     * Start building a semantic layer query.
     *
     * @param  string  $queryName  The name of the semantic layer query
     */
    public function query(string $queryName): SemanticQueryServiceBuilder
    {
        $semanticClient = $this->client()->semantic($queryName);

        return new SemanticQueryServiceBuilder($semanticClient);
    }

    /**
     * Get available semantic layer queries.
     *
     * @return array<int, array<string, mixed>>
     *
     * @throws ServiceLayerException
     */
    public function getAvailableQueries(): array
    {
        return SemanticLayerClient::getAvailableQueries($this->client());
    }

    /**
     * Execute a simple query with dimensions and measures.
     *
     * @param  string  $queryName  The query name
     * @param  array<int, string>  $dimensions  Dimensions to select
     * @param  array<int, string>  $measures  Measures to select
     * @param  array<string, mixed>  $params  Additional parameters
     * @return array<int, array<string, mixed>>
     *
     * @throws ServiceLayerException
     */
    public function execute(
        string $queryName,
        array $dimensions = [],
        array $measures = [],
        array $params = []
    ): array {
        $builder = $this->query($queryName);

        if (! empty($dimensions)) {
            $builder->dimensions($dimensions);
        }

        if (! empty($measures)) {
            $builder->measures($measures);
        }

        foreach ($params as $name => $value) {
            $builder->param($name, $value);
        }

        return $builder->get();
    }

    /**
     * Execute a query with date range filter.
     *
     * @param  string  $queryName  The query name
     * @param  string  $dateField  The date field to filter
     * @param  string|DateTimeInterface  $fromDate  Start date
     * @param  string|DateTimeInterface  $toDate  End date
     * @param  array<int, string>  $dimensions  Dimensions to select
     * @param  array<int, string>  $measures  Measures to select
     * @return array<int, array<string, mixed>>
     *
     * @throws ServiceLayerException
     */
    public function executeWithDateRange(
        string $queryName,
        string $dateField,
        string|DateTimeInterface $fromDate,
        string|DateTimeInterface $toDate,
        array $dimensions = [],
        array $measures = []
    ): array {
        $builder = $this->query($queryName);

        if (! empty($dimensions)) {
            $builder->dimensions($dimensions);
        }

        if (! empty($measures)) {
            $builder->measures($measures);
        }

        $builder->whereBetween($dateField, $fromDate, $toDate);

        return $builder->get();
    }

    /**
     * Get aggregated results from a semantic query.
     *
     * @param  string  $queryName  The query name
     * @param  array<int, string>  $measures  Measures to aggregate
     * @param  string|null  $filterField  Optional filter field
     * @param  mixed  $filterValue  Optional filter value
     * @return array<string, mixed>
     *
     * @throws ServiceLayerException
     */
    public function aggregate(
        string $queryName,
        array $measures,
        ?string $filterField = null,
        mixed $filterValue = null
    ): array {
        $builder = $this->query($queryName)->measures($measures);

        if ($filterField !== null && $filterValue !== null) {
            $builder->where($filterField, $filterValue);
        }

        return $builder->aggregate();
    }
}

/**
 * Fluent builder for Semantic Layer queries.
 *
 * Provides a chainable interface for building and executing semantic layer
 * queries with dimensions, measures, filters, and aggregations.
 */
final class SemanticQueryServiceBuilder
{
    public function __construct(
        private readonly SemanticLayerClient $client
    ) {}

    /**
     * Add dimensions to the query.
     *
     * @param  array<int, string>|string  $dimensions  Dimensions to select
     */
    public function dimensions(array|string ...$dimensions): self
    {
        $dims = [];
        foreach ($dimensions as $dim) {
            if (is_array($dim)) {
                $dims = array_merge($dims, $dim);
            } else {
                $dims[] = $dim;
            }
        }

        $this->client->dimensions($dims);

        return $this;
    }

    /**
     * Add measures to the query.
     *
     * @param  array<int, string>|string  $measures  Measures to select
     */
    public function measures(array|string ...$measures): self
    {
        $meas = [];
        foreach ($measures as $measure) {
            if (is_array($measure)) {
                $meas = array_merge($meas, $measure);
            } else {
                $meas[] = $measure;
            }
        }

        $this->client->measures($meas);

        return $this;
    }

    /**
     * Add select fields (alias for dimensions + measures).
     *
     * @param  array<int, string>|string  $fields  Fields to select
     */
    public function select(array|string ...$fields): self
    {
        $allFields = [];
        foreach ($fields as $field) {
            if (is_array($field)) {
                $allFields = array_merge($allFields, $field);
            } else {
                $allFields[] = $field;
            }
        }

        $this->client->dimensions($allFields);

        return $this;
    }

    /**
     * Add a filter condition.
     *
     * @param  string  $field  Field name
     * @param  string  $operator  Comparison operator (eq, ne, gt, ge, lt, le)
     * @param  mixed  $value  Value to compare
     */
    public function filter(string $field, string $operator, mixed $value): self
    {
        $this->client->filter($field, $operator, $value);

        return $this;
    }

    /**
     * Add a where equals condition.
     *
     * @param  string  $field  Field name
     * @param  mixed  $value  Value to match
     */
    public function where(string $field, mixed $value): self
    {
        $this->client->where($field, $value);

        return $this;
    }

    /**
     * Add a where between condition.
     *
     * @param  string  $field  Field name
     * @param  mixed  $start  Start value
     * @param  mixed  $end  End value
     */
    public function whereBetween(string $field, mixed $start, mixed $end): self
    {
        $this->client->whereBetween($field, $start, $end);

        return $this;
    }

    /**
     * Add a where in condition.
     *
     * @param  string  $field  Field name
     * @param  array<int, mixed>  $values  Values to match
     */
    public function whereIn(string $field, array $values): self
    {
        $this->client->whereIn($field, $values);

        return $this;
    }

    /**
     * Set a query parameter.
     *
     * @param  string  $name  Parameter name
     * @param  mixed  $value  Parameter value
     */
    public function param(string $name, mixed $value): self
    {
        $this->client->param($name, $value);

        return $this;
    }

    /**
     * Set multiple parameters at once.
     *
     * @param  array<string, mixed>  $params  Parameters
     */
    public function params(array $params): self
    {
        $this->client->params($params);

        return $this;
    }

    /**
     * Add order by clause.
     *
     * @param  string  $field  Field to order by
     * @param  string  $direction  Direction (asc or desc)
     */
    public function orderBy(string $field, string $direction = 'asc'): self
    {
        $this->client->orderBy($field, $direction);

        return $this;
    }

    /**
     * Add order by descending clause.
     *
     * @param  string  $field  Field to order by
     */
    public function orderByDesc(string $field): self
    {
        $this->client->orderByDesc($field);

        return $this;
    }

    /**
     * Set the limit (top) for results.
     *
     * @param  int  $value  Maximum number of results
     */
    public function limit(int $value): self
    {
        $this->client->limit($value);

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
     * @param  int  $value  Number of results to skip
     */
    public function offset(int $value): self
    {
        $this->client->offset($value);

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
     * @throws ServiceLayerException
     */
    public function get(): array
    {
        return $this->client->execute();
    }

    /**
     * Execute the query and get only the first result.
     *
     * @return array<string, mixed>|null
     *
     * @throws ServiceLayerException
     */
    public function first(): ?array
    {
        return $this->client->first();
    }

    /**
     * Execute and get aggregated results.
     *
     * @return array<string, mixed>
     *
     * @throws ServiceLayerException
     */
    public function aggregate(): array
    {
        return $this->client->aggregate();
    }

    /**
     * Execute and get results as a Laravel collection.
     *
     * @return Collection<int, array<string, mixed>>
     *
     * @throws ServiceLayerException
     */
    public function collect(): Collection
    {
        return collect($this->get());
    }

    /**
     * Execute and pluck a single column.
     *
     * @param  string  $column  Column to pluck
     * @return Collection<int, mixed>
     *
     * @throws ServiceLayerException
     */
    public function pluck(string $column): Collection
    {
        return $this->collect()->pluck($column);
    }

    /**
     * Execute and sum a measure.
     *
     * @param  string  $column  Column to sum
     *
     * @throws ServiceLayerException
     */
    public function sum(string $column): float|int
    {
        return $this->collect()->sum($column);
    }

    /**
     * Execute and get average of a measure.
     *
     * @param  string  $column  Column to average
     *
     * @throws ServiceLayerException
     */
    public function avg(string $column): float|int|null
    {
        return $this->collect()->avg($column);
    }

    /**
     * Execute and get max value of a measure.
     *
     * @param  string  $column  Column
     *
     * @throws ServiceLayerException
     */
    public function max(string $column): mixed
    {
        return $this->collect()->max($column);
    }

    /**
     * Execute and get min value of a measure.
     *
     * @param  string  $column  Column
     *
     * @throws ServiceLayerException
     */
    public function min(string $column): mixed
    {
        return $this->collect()->min($column);
    }

    /**
     * Execute and group results by a dimension.
     *
     * @param  string  $column  Dimension to group by
     * @return Collection<string|int, Collection<int, array<string, mixed>>>
     *
     * @throws ServiceLayerException
     */
    public function groupBy(string $column): Collection
    {
        return $this->collect()->groupBy($column);
    }

    /**
     * Iterate through results with a callback.
     *
     * @param  callable(array<string, mixed>, int): void  $callback
     *
     * @throws ServiceLayerException
     */
    public function each(callable $callback): void
    {
        $this->collect()->each($callback);
    }

    /**
     * Map results through a callback.
     *
     * @param  callable(array<string, mixed>): mixed  $callback
     * @return Collection<int, mixed>
     *
     * @throws ServiceLayerException
     */
    public function map(callable $callback): Collection
    {
        return $this->collect()->map($callback);
    }

    /**
     * Filter results through a callback.
     *
     * @param  callable(array<string, mixed>): bool  $callback
     * @return Collection<int, array<string, mixed>>
     *
     * @throws ServiceLayerException
     */
    public function filterResults(callable $callback): Collection
    {
        return $this->collect()->filter($callback);
    }

    /**
     * Get the underlying SemanticLayerClient.
     */
    public function getClient(): SemanticLayerClient
    {
        return $this->client;
    }
}
