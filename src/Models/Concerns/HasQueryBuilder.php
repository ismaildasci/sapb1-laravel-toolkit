<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Models\Concerns;

use SapB1\Toolkit\Models\ModelCollection;
use SapB1\Toolkit\Models\Paginator;
use SapB1\Toolkit\Models\QueryBuilder;
use SapB1\Toolkit\Models\SapB1Model;

/**
 * Provides query builder integration for models.
 */
trait HasQueryBuilder
{
    /**
     * Create a new query builder instance.
     */
    public static function query(): QueryBuilder
    {
        return (new static)->newQuery();
    }

    /**
     * Create a new query builder for this model instance.
     */
    public function newQuery(): QueryBuilder
    {
        return new QueryBuilder($this);
    }

    /**
     * Get all models.
     *
     * @return ModelCollection<SapB1Model>
     */
    public static function all(): ModelCollection
    {
        return static::query()->get();
    }

    /**
     * Find a model by its primary key.
     *
     * @return static|null
     */
    public static function find(int|string $id): ?SapB1Model
    {
        /** @var static|null */
        return static::query()->find($id);
    }

    /**
     * Find a model by its primary key or throw an exception.
     *
     * @return static
     *
     * @throws \SapB1\Toolkit\Exceptions\ModelNotFoundException
     */
    public static function findOrFail(int|string $id): SapB1Model
    {
        /** @var static */
        return static::query()->findOrFail($id);
    }

    /**
     * Add a basic where clause.
     */
    public static function where(string $field, mixed $operator = null, mixed $value = null): QueryBuilder
    {
        return static::query()->where($field, $operator, $value);
    }

    /**
     * Add an OR where clause.
     */
    public static function orWhere(string $field, mixed $operator = null, mixed $value = null): QueryBuilder
    {
        return static::query()->orWhere($field, $operator, $value);
    }

    /**
     * Add a where IN clause.
     *
     * @param  array<int, mixed>  $values
     */
    public static function whereIn(string $field, array $values): QueryBuilder
    {
        return static::query()->whereIn($field, $values);
    }

    /**
     * Add a where BETWEEN clause.
     *
     * @param  array{0: mixed, 1: mixed}  $range
     */
    public static function whereBetween(string $field, array $range): QueryBuilder
    {
        return static::query()->whereBetween($field, $range);
    }

    /**
     * Add a where NULL clause.
     */
    public static function whereNull(string $field): QueryBuilder
    {
        return static::query()->whereNull($field);
    }

    /**
     * Add a where NOT NULL clause.
     */
    public static function whereNotNull(string $field): QueryBuilder
    {
        return static::query()->whereNotNull($field);
    }

    /**
     * Add a raw OData filter.
     */
    public static function filter(string $odataFilter): QueryBuilder
    {
        return static::query()->filter($odataFilter);
    }

    /**
     * Add an order by clause.
     */
    public static function orderBy(string $field, string $direction = 'asc'): QueryBuilder
    {
        return static::query()->orderBy($field, $direction);
    }

    /**
     * Limit the number of results.
     */
    public static function limit(int $value): QueryBuilder
    {
        return static::query()->limit($value);
    }

    /**
     * Skip a number of results.
     */
    public static function skip(int $value): QueryBuilder
    {
        return static::query()->skip($value);
    }

    /**
     * Eager load relationships.
     *
     * @param  string|array<int, string>  $relations
     */
    public static function with(string|array $relations): QueryBuilder
    {
        return static::query()->with($relations);
    }

    /**
     * Select specific fields.
     *
     * @param  array<int, string>  $fields
     */
    public static function select(array $fields): QueryBuilder
    {
        return static::query()->select($fields);
    }

    /**
     * Paginate results.
     *
     * @return Paginator<SapB1Model>
     */
    public static function paginate(int $perPage = 15, int $page = 1): Paginator
    {
        return static::query()->paginate($perPage, $page);
    }

    /**
     * Get the first result.
     *
     * @return static|null
     */
    public static function first(): ?SapB1Model
    {
        /** @var static|null */
        return static::query()->first();
    }

    /**
     * Get the first result or throw an exception.
     *
     * @return static
     *
     * @throws \SapB1\Toolkit\Exceptions\ModelNotFoundException
     */
    public static function firstOrFail(): SapB1Model
    {
        /** @var static */
        return static::query()->firstOrFail();
    }

    /**
     * Get the count of results.
     */
    public static function count(): int
    {
        return static::query()->count();
    }

    /**
     * Check if any results exist.
     */
    public static function exists(): bool
    {
        return static::query()->exists();
    }

    /**
     * Apply a scope to the query.
     *
     * @param  array<int, mixed>  $arguments
     */
    public function __call(string $method, array $arguments): mixed
    {
        // Check for scope method
        $scopeMethod = 'scope'.ucfirst($method);

        if (method_exists($this, $scopeMethod)) {
            $query = $this->newQuery();

            return $this->{$scopeMethod}($query, ...$arguments);
        }

        throw new \BadMethodCallException(sprintf(
            'Call to undefined method %s::%s()',
            static::class,
            $method
        ));
    }

    /**
     * Handle static calls to apply scopes.
     *
     * @param  array<int, mixed>  $arguments
     */
    public static function __callStatic(string $method, array $arguments): mixed
    {
        $instance = new static;
        $scopeMethod = 'scope'.ucfirst($method);

        if (method_exists($instance, $scopeMethod)) {
            $query = $instance->newQuery();

            return $instance->{$scopeMethod}($query, ...$arguments);
        }

        throw new \BadMethodCallException(sprintf(
            'Call to undefined method %s::%s()',
            static::class,
            $method
        ));
    }
}
