<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Models;

use InvalidArgumentException;
use SapB1\Facades\SapB1;
use SapB1\Toolkit\Cache\CacheManager;
use SapB1\Toolkit\Cache\CacheResolver;
use SapB1\Toolkit\Exceptions\ModelNotFoundException;

/**
 * OData query builder with Eloquent-like syntax.
 */
class QueryBuilder
{
    /**
     * The model instance.
     */
    protected SapB1Model $model;

    /**
     * Where clauses.
     *
     * @var array<int, array{type: string, field: string, operator: string, value: mixed, boolean: string}>
     */
    protected array $wheres = [];

    /**
     * Raw OData filter.
     */
    protected ?string $rawFilter = null;

    /**
     * Order by clauses.
     *
     * @var array<string, string>
     */
    protected array $orderBy = [];

    /**
     * Limit (top).
     */
    protected ?int $limit = null;

    /**
     * Offset (skip).
     */
    protected int $offset = 0;

    /**
     * Select fields.
     *
     * @var array<int, string>
     */
    protected array $select = [];

    /**
     * Eager load relations.
     *
     * @var array<int, string>
     */
    protected array $with = [];

    /**
     * Cache resolver for this query.
     */
    protected CacheResolver $cacheResolver;

    /**
     * Valid field name pattern for OData queries.
     * Allows alphanumeric characters, underscores, and dots (for nested properties).
     */
    protected const VALID_FIELD_PATTERN = '/^[a-zA-Z_][a-zA-Z0-9_]*(\.[a-zA-Z_][a-zA-Z0-9_]*)*$/';

    /**
     * OData operator mapping.
     *
     * @var array<string, string>
     */
    protected array $operatorMap = [
        '=' => 'eq',
        '==' => 'eq',
        '!=' => 'ne',
        '<>' => 'ne',
        '>' => 'gt',
        '>=' => 'ge',
        '<' => 'lt',
        '<=' => 'le',
        'eq' => 'eq',
        'ne' => 'ne',
        'gt' => 'gt',
        'ge' => 'ge',
        'lt' => 'lt',
        'le' => 'le',
    ];

    public function __construct(SapB1Model $model)
    {
        $this->model = $model;
        $this->initializeCacheResolver();
    }

    /**
     * Initialize the cache resolver with model settings.
     */
    protected function initializeCacheResolver(): void
    {
        $this->cacheResolver = new CacheResolver($this->model::getEntity());

        // Check if model has HasCache trait and get model-level setting
        $modelClass = get_class($this->model);
        if (method_exists($modelClass, 'isCacheEnabled')) {
            /** @var callable(): ?bool $cacheEnabledCallback */
            $cacheEnabledCallback = [$modelClass, 'isCacheEnabled'];
            $this->cacheResolver->setModelEnabled($cacheEnabledCallback());
        }
    }

    /**
     * Enable caching for this query with optional TTL.
     *
     * @param  int|null  $ttl  Cache TTL in seconds (null = use default)
     */
    public function cache(?int $ttl = null): static
    {
        $this->cacheResolver->enableForQuery($ttl);

        return $this;
    }

    /**
     * Disable caching for this query.
     */
    public function noCache(): static
    {
        $this->cacheResolver->disableForQuery();

        return $this;
    }

    /**
     * Get the cache resolver.
     */
    public function getCacheResolver(): CacheResolver
    {
        return $this->cacheResolver;
    }

    /**
     * Add a basic where clause.
     *
     * @throws InvalidArgumentException
     */
    public function where(string $field, mixed $operator = null, mixed $value = null): static
    {
        $this->validateFieldName($field);

        // Handle where('field', 'value') syntax
        if ($value === null && $operator !== null && ! isset($this->operatorMap[$operator])) {
            $value = $operator;
            $operator = '=';
        }

        $operator = $this->operatorMap[$operator] ?? 'eq';

        $this->wheres[] = [
            'type' => 'basic',
            'field' => $field,
            'operator' => $operator,
            'value' => $value,
            'boolean' => 'and',
        ];

        return $this;
    }

    /**
     * Add an OR where clause.
     *
     * @throws InvalidArgumentException
     */
    public function orWhere(string $field, mixed $operator = null, mixed $value = null): static
    {
        $this->validateFieldName($field);

        if ($value === null && $operator !== null && ! isset($this->operatorMap[$operator])) {
            $value = $operator;
            $operator = '=';
        }

        $operator = $this->operatorMap[$operator] ?? 'eq';

        $this->wheres[] = [
            'type' => 'basic',
            'field' => $field,
            'operator' => $operator,
            'value' => $value,
            'boolean' => 'or',
        ];

        return $this;
    }

    /**
     * Add a where IN clause.
     *
     * @param  array<int, mixed>  $values
     *
     * @throws InvalidArgumentException
     */
    public function whereIn(string $field, array $values): static
    {
        $this->validateFieldName($field);

        $this->wheres[] = [
            'type' => 'in',
            'field' => $field,
            'operator' => 'in',
            'value' => $values,
            'boolean' => 'and',
        ];

        return $this;
    }

    /**
     * Add a where BETWEEN clause.
     *
     * @param  array{0: mixed, 1: mixed}  $range
     *
     * @throws InvalidArgumentException
     */
    public function whereBetween(string $field, array $range): static
    {
        $this->validateFieldName($field);

        $this->wheres[] = [
            'type' => 'between',
            'field' => $field,
            'operator' => 'between',
            'value' => $range,
            'boolean' => 'and',
        ];

        return $this;
    }

    /**
     * Add a where NULL clause.
     *
     * @throws InvalidArgumentException
     */
    public function whereNull(string $field): static
    {
        $this->validateFieldName($field);

        $this->wheres[] = [
            'type' => 'null',
            'field' => $field,
            'operator' => 'eq',
            'value' => null,
            'boolean' => 'and',
        ];

        return $this;
    }

    /**
     * Add a where NOT NULL clause.
     *
     * @throws InvalidArgumentException
     */
    public function whereNotNull(string $field): static
    {
        $this->validateFieldName($field);

        $this->wheres[] = [
            'type' => 'notNull',
            'field' => $field,
            'operator' => 'ne',
            'value' => null,
            'boolean' => 'and',
        ];

        return $this;
    }

    /**
     * Add a raw OData filter.
     */
    public function filter(string $odataFilter): static
    {
        $this->rawFilter = $odataFilter;

        return $this;
    }

    /**
     * Add an order by clause.
     *
     * @throws InvalidArgumentException
     */
    public function orderBy(string $field, string $direction = 'asc'): static
    {
        $this->validateFieldName($field);

        $this->orderBy[$field] = strtolower($direction) === 'desc' ? 'desc' : 'asc';

        return $this;
    }

    /**
     * Order by descending.
     */
    public function orderByDesc(string $field): static
    {
        return $this->orderBy($field, 'desc');
    }

    /**
     * Limit the number of results.
     */
    public function limit(int $value): static
    {
        $this->limit = $value;

        return $this;
    }

    /**
     * Alias for limit.
     */
    public function take(int $value): static
    {
        return $this->limit($value);
    }

    /**
     * Skip a number of results.
     */
    public function skip(int $value): static
    {
        $this->offset = $value;

        return $this;
    }

    /**
     * Alias for skip.
     */
    public function offset(int $value): static
    {
        return $this->skip($value);
    }

    /**
     * Select specific fields.
     *
     * @param  array<int, string>  $fields
     *
     * @throws InvalidArgumentException
     */
    public function select(array $fields): static
    {
        foreach ($fields as $field) {
            $this->validateFieldName($field);
        }

        $this->select = $fields;

        return $this;
    }

    /**
     * Eager load relationships.
     *
     * @param  string|array<int, string>  $relations
     */
    public function with(string|array $relations): static
    {
        $this->with = is_array($relations) ? $relations : func_get_args();

        return $this;
    }

    /**
     * Execute the query and get results.
     *
     * @return ModelCollection<SapB1Model>
     */
    public function get(): ModelCollection
    {
        $response = $this->execute();
        $items = [];

        foreach ($response['value'] ?? [] as $data) {
            $model = $this->hydrate($data);
            $items[] = $model;
        }

        $collection = new ModelCollection($items);

        // Eager load relationships
        if (! empty($this->with)) {
            SapB1Model::eagerLoadRelations($collection, $this->with);
        }

        return $collection;
    }

    /**
     * Get the first result.
     */
    public function first(): ?SapB1Model
    {
        $this->limit(1);
        $results = $this->get();

        return $results->first();
    }

    /**
     * Get the first result or throw an exception.
     *
     * @throws ModelNotFoundException
     */
    public function firstOrFail(): SapB1Model
    {
        $model = $this->first();

        if ($model === null) {
            throw new ModelNotFoundException(
                sprintf('No results found for model [%s]', get_class($this->model))
            );
        }

        return $model;
    }

    /**
     * Find a model by its primary key.
     */
    public function find(int|string $id): ?SapB1Model
    {
        // Check if caching should be used
        if ($this->cacheResolver->shouldCache()) {
            $cacheManager = new CacheManager($this->cacheResolver);
            $cacheKey = CacheManager::generateRecordKey($this->model::getEntity(), $id);

            return $cacheManager->remember($cacheKey, function () use ($id): ?SapB1Model {
                return $this->findFromApi($id);
            });
        }

        return $this->findFromApi($id);
    }

    /**
     * Find a model from the API without caching.
     */
    protected function findFromApi(int|string $id): ?SapB1Model
    {
        try {
            $client = $this->getClient();
            $response = $client->service($this->model::getEntity())->find($id);

            if (empty($response)) {
                return null;
            }

            return $this->hydrate($response);
        } catch (\Exception) {
            return null;
        }
    }

    /**
     * Find a model by its primary key or throw an exception.
     *
     * @throws ModelNotFoundException
     */
    public function findOrFail(int|string $id): SapB1Model
    {
        $model = $this->find($id);

        if ($model === null) {
            throw new ModelNotFoundException(
                sprintf('No results found for model [%s] with ID [%s]', get_class($this->model), $id)
            );
        }

        return $model;
    }

    /**
     * Get the count of results.
     */
    public function count(): int
    {
        $client = $this->getClient();
        $builder = $client->service($this->model::getEntity())->queryBuilder();

        $filter = $this->buildFilter();
        if ($filter !== '') {
            $builder->filter($filter);
        }

        $response = $builder->count();

        return (int) ($response['@odata.count'] ?? $response['value'] ?? 0);
    }

    /**
     * Check if any results exist.
     */
    public function exists(): bool
    {
        return $this->count() > 0;
    }

    /**
     * Paginate results.
     *
     * @return Paginator<SapB1Model>
     */
    public function paginate(int $perPage = 15, int $page = 1): Paginator
    {
        $total = $this->count();
        $this->offset = ($page - 1) * $perPage;
        $this->limit = $perPage;

        $items = $this->get();

        return new Paginator($items, $total, $perPage, $page);
    }

    /**
     * Execute the query.
     *
     * @return array<string, mixed>
     */
    protected function execute(): array
    {
        // Check if caching should be used
        if ($this->cacheResolver->shouldCache()) {
            $cacheManager = new CacheManager($this->cacheResolver);
            $cacheKey = $this->generateQueryCacheKey();

            return $cacheManager->remember($cacheKey, function (): array {
                return $this->executeFromApi();
            });
        }

        return $this->executeFromApi();
    }

    /**
     * Execute the query directly from API without caching.
     *
     * @return array<string, mixed>
     */
    protected function executeFromApi(): array
    {
        $client = $this->getClient();
        $builder = $client->service($this->model::getEntity())->queryBuilder();

        // Add filter
        $filter = $this->buildFilter();
        if ($filter !== '') {
            $builder->filter($filter);
        }

        // Add order by
        foreach ($this->orderBy as $field => $direction) {
            $builder->orderby($field, $direction);
        }

        // Add pagination
        if ($this->limit !== null) {
            $builder->top($this->limit);
        }

        if ($this->offset > 0) {
            $builder->skip($this->offset);
        }

        // Add select
        if (! empty($this->select)) {
            $builder->select($this->select);
        }

        return $builder->get();
    }

    /**
     * Generate a unique cache key for the current query.
     */
    protected function generateQueryCacheKey(): string
    {
        $params = [
            'wheres' => $this->wheres,
            'rawFilter' => $this->rawFilter,
            'orderBy' => $this->orderBy,
            'limit' => $this->limit,
            'offset' => $this->offset,
            'select' => $this->select,
        ];

        return CacheManager::generateQueryKey($this->model::getEntity(), $params);
    }

    /**
     * Build the OData filter string.
     */
    protected function buildFilter(): string
    {
        // If raw filter is set, use it
        if ($this->rawFilter !== null) {
            return $this->rawFilter;
        }

        if (empty($this->wheres)) {
            return '';
        }

        $parts = [];

        foreach ($this->wheres as $index => $where) {
            $clause = $this->buildWhereClause($where);

            if ($index === 0) {
                $parts[] = $clause;
            } else {
                $boolean = $where['boolean'] === 'or' ? ' or ' : ' and ';
                $parts[] = $boolean.$clause;
            }
        }

        return implode('', $parts);
    }

    /**
     * Build a single where clause.
     *
     * @param  array{type: string, field: string, operator: string, value: mixed, boolean: string}  $where
     */
    protected function buildWhereClause(array $where): string
    {
        $field = $where['field'];
        $value = $where['value'];

        return match ($where['type']) {
            'basic' => $this->buildBasicClause($field, $where['operator'], $value),
            'in' => $this->buildInClause($field, $value),
            'between' => $this->buildBetweenClause($field, $value),
            'null' => "{$field} eq null",
            'notNull' => "{$field} ne null",
            default => '',
        };
    }

    /**
     * Build a basic where clause.
     */
    protected function buildBasicClause(string $field, string $operator, mixed $value): string
    {
        $formattedValue = $this->formatValue($value);

        return "{$field} {$operator} {$formattedValue}";
    }

    /**
     * Build a where IN clause.
     *
     * @param  array<int, mixed>  $values
     */
    protected function buildInClause(string $field, array $values): string
    {
        $clauses = array_map(
            fn ($value) => "{$field} eq ".$this->formatValue($value),
            $values
        );

        return '('.implode(' or ', $clauses).')';
    }

    /**
     * Build a where BETWEEN clause.
     *
     * @param  array{0: mixed, 1: mixed}  $range
     */
    protected function buildBetweenClause(string $field, array $range): string
    {
        $min = $this->formatValue($range[0]);
        $max = $this->formatValue($range[1]);

        return "({$field} ge {$min} and {$field} le {$max})";
    }

    /**
     * Format a value for OData.
     */
    protected function formatValue(mixed $value): string
    {
        if ($value === null) {
            return 'null';
        }

        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }

        if (is_int($value) || is_float($value)) {
            return (string) $value;
        }

        if ($value instanceof \DateTimeInterface) {
            return "'".$value->format('Y-m-d')."'";
        }

        if ($value instanceof \BackedEnum) {
            $enumValue = $value->value;

            return is_string($enumValue) ? "'{$enumValue}'" : (string) $enumValue;
        }

        // String value - escape single quotes
        $escaped = str_replace("'", "''", (string) $value);

        return "'{$escaped}'";
    }

    /**
     * Validate a field name to prevent OData injection.
     *
     * @throws InvalidArgumentException
     */
    protected function validateFieldName(string $field): void
    {
        if (! preg_match(self::VALID_FIELD_PATTERN, $field)) {
            throw new InvalidArgumentException(
                sprintf('Invalid field name [%s]. Field names must start with a letter or underscore and contain only alphanumeric characters, underscores, and dots.', $field)
            );
        }
    }

    /**
     * Hydrate a model from response data.
     *
     * @param  array<string, mixed>  $data
     */
    protected function hydrate(array $data): SapB1Model
    {
        $modelClass = get_class($this->model);

        /** @var SapB1Model $model */
        $model = new $modelClass;
        $model->setRawAttributes($data, true);
        $model->exists = true;

        // Fire retrieved event
        $model->fireModelEvent('retrieved');

        return $model;
    }

    /**
     * Get the SAP B1 client.
     *
     * @return mixed
     */
    protected function getClient()
    {
        return SapB1::connection($this->model::getConnection());
    }
}
