<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Models;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use JsonSerializable;
use Traversable;

/**
 * Collection of models with helper methods.
 *
 * @template TModel of SapB1Model
 *
 * @implements IteratorAggregate<int, TModel>
 *
 * @phpstan-consistent-constructor
 */
class ModelCollection implements Countable, IteratorAggregate, JsonSerializable
{
    /**
     * The items in the collection.
     *
     * @var array<int, TModel>
     */
    protected array $items;

    /**
     * @param  array<int, TModel>  $items
     */
    public function __construct(array $items = [])
    {
        $this->items = array_values($items);
    }

    /**
     * Get all items.
     *
     * @return array<int, TModel>
     */
    public function all(): array
    {
        return $this->items;
    }

    /**
     * Get the first item.
     *
     * @return TModel|null
     */
    public function first(): ?SapB1Model
    {
        return $this->items[0] ?? null;
    }

    /**
     * Get the last item.
     *
     * @return TModel|null
     */
    public function last(): ?SapB1Model
    {
        $count = count($this->items);

        return $count > 0 ? $this->items[$count - 1] : null;
    }

    /**
     * Get an item by index.
     *
     * @return TModel|null
     */
    public function get(int $index): ?SapB1Model
    {
        return $this->items[$index] ?? null;
    }

    /**
     * Check if collection is empty.
     */
    public function isEmpty(): bool
    {
        return empty($this->items);
    }

    /**
     * Check if collection is not empty.
     */
    public function isNotEmpty(): bool
    {
        return ! $this->isEmpty();
    }

    /**
     * Count items.
     */
    public function count(): int
    {
        return count($this->items);
    }

    /**
     * Filter items by a callback.
     *
     * @param  callable(TModel, int): bool  $callback
     * @return static
     */
    public function filter(callable $callback): static
    {
        $filtered = array_filter($this->items, $callback, ARRAY_FILTER_USE_BOTH);

        return new static(array_values($filtered));
    }

    /**
     * Filter items by a field value.
     *
     * @return static
     */
    public function where(string $field, mixed $value): static
    {
        return $this->filter(fn (SapB1Model $item) => $item->{$field} === $value);
    }

    /**
     * Filter items where field is in array.
     *
     * @param  array<int, mixed>  $values
     * @return static
     */
    public function whereIn(string $field, array $values): static
    {
        return $this->filter(fn (SapB1Model $item) => in_array($item->{$field}, $values, true));
    }

    /**
     * Filter items where field is not null.
     *
     * @return static
     */
    public function whereNotNull(string $field): static
    {
        return $this->filter(fn (SapB1Model $item) => $item->{$field} !== null);
    }

    /**
     * Map items to a new collection.
     *
     * @template TResult
     *
     * @param  callable(TModel, int): TResult  $callback
     * @return array<int, TResult>
     */
    public function map(callable $callback): array
    {
        return array_map($callback, $this->items, array_keys($this->items));
    }

    /**
     * Pluck a field from all items.
     *
     * @return array<int, mixed>
     */
    public function pluck(string $field): array
    {
        return $this->map(fn (SapB1Model $item) => $item->{$field});
    }

    /**
     * Key by a field.
     *
     * @return array<string|int, TModel>
     */
    public function keyBy(string $field): array
    {
        $result = [];

        foreach ($this->items as $item) {
            $key = $item->{$field};
            $result[$key] = $item;
        }

        return $result;
    }

    /**
     * Group by a field.
     *
     * @return array<string|int, array<int, TModel>>
     */
    public function groupBy(string $field): array
    {
        $result = [];

        foreach ($this->items as $item) {
            $key = $item->{$field};
            $result[$key][] = $item;
        }

        return $result;
    }

    /**
     * Sort by a field.
     *
     * @return static
     */
    public function sortBy(string $field, bool $descending = false): static
    {
        $items = $this->items;

        usort($items, function (SapB1Model $a, SapB1Model $b) use ($field, $descending) {
            $aValue = $a->{$field};
            $bValue = $b->{$field};

            $result = $aValue <=> $bValue;

            return $descending ? -$result : $result;
        });

        return new static($items);
    }

    /**
     * Sort by descending.
     *
     * @return static
     */
    public function sortByDesc(string $field): static
    {
        return $this->sortBy($field, true);
    }

    /**
     * Take first n items.
     *
     * @return static
     */
    public function take(int $count): static
    {
        return new static(array_slice($this->items, 0, $count));
    }

    /**
     * Skip first n items.
     *
     * @return static
     */
    public function skip(int $count): static
    {
        return new static(array_slice($this->items, $count));
    }

    /**
     * Check if any item matches.
     *
     * @param  callable(TModel): bool  $callback
     */
    public function contains(callable $callback): bool
    {
        foreach ($this->items as $item) {
            if ($callback($item)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Sum a field.
     */
    public function sum(string $field): float|int
    {
        $sum = 0;

        foreach ($this->items as $item) {
            $sum += $item->{$field} ?? 0;
        }

        return $sum;
    }

    /**
     * Get average of a field.
     */
    public function avg(string $field): float
    {
        $count = $this->count();

        if ($count === 0) {
            return 0.0;
        }

        return $this->sum($field) / $count;
    }

    /**
     * Get max value of a field.
     */
    public function max(string $field): mixed
    {
        if ($this->isEmpty()) {
            return null;
        }

        $values = $this->pluck($field);

        return $values !== [] ? max($values) : null;
    }

    /**
     * Get min value of a field.
     */
    public function min(string $field): mixed
    {
        if ($this->isEmpty()) {
            return null;
        }

        $values = $this->pluck($field);

        return $values !== [] ? min($values) : null;
    }

    /**
     * Each - iterate over items.
     *
     * @param  callable(TModel, int): void  $callback
     */
    public function each(callable $callback): static
    {
        foreach ($this->items as $index => $item) {
            $callback($item, $index);
        }

        return $this;
    }

    /**
     * Convert to array.
     *
     * @return array<int, array<string, mixed>>
     */
    public function toArray(): array
    {
        return array_map(fn (SapB1Model $item) => $item->toArray(), $this->items);
    }

    /**
     * Convert to JSON.
     */
    public function toJson(int $options = 0): string
    {
        return json_encode($this->toArray(), $options) ?: '[]';
    }

    /**
     * Get iterator.
     *
     * @return Traversable<int, TModel>
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->items);
    }

    /**
     * JSON serialize.
     *
     * @return array<int, array<string, mixed>>
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /**
     * Add item to collection.
     *
     * @param  TModel  $item
     * @return static
     */
    public function push(SapB1Model $item): static
    {
        $this->items[] = $item;

        return $this;
    }

    /**
     * Merge another collection.
     *
     * @param  ModelCollection<TModel>  $collection
     * @return static
     */
    public function merge(ModelCollection $collection): static
    {
        return new static(array_merge($this->items, $collection->all()));
    }

    /**
     * Load a relationship on all models.
     *
     * @param  string|array<int, string>  $relations
     * @return static
     */
    public function load(string|array $relations): static
    {
        $relations = is_array($relations) ? $relations : [$relations];

        if ($this->isNotEmpty()) {
            SapB1Model::eagerLoadRelations($this, $relations);
        }

        return $this;
    }
}
