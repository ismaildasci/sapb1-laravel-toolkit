<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Models;

use JsonSerializable;

/**
 * Simple paginator for model results.
 *
 * @template TModel of SapB1Model
 */
class Paginator implements JsonSerializable
{
    /**
     * @param  ModelCollection<TModel>  $items
     */
    public function __construct(
        protected ModelCollection $items,
        protected int $total,
        protected int $perPage,
        protected int $currentPage = 1
    ) {}

    /**
     * Get the items.
     *
     * @return ModelCollection<TModel>
     */
    public function items(): ModelCollection
    {
        return $this->items;
    }

    /**
     * Get total count.
     */
    public function total(): int
    {
        return $this->total;
    }

    /**
     * Get per page count.
     */
    public function perPage(): int
    {
        return $this->perPage;
    }

    /**
     * Get current page.
     */
    public function currentPage(): int
    {
        return $this->currentPage;
    }

    /**
     * Get last page number.
     */
    public function lastPage(): int
    {
        return max(1, (int) ceil($this->total / $this->perPage));
    }

    /**
     * Check if there are more pages.
     */
    public function hasMorePages(): bool
    {
        return $this->currentPage < $this->lastPage();
    }

    /**
     * Check if on first page.
     */
    public function onFirstPage(): bool
    {
        return $this->currentPage <= 1;
    }

    /**
     * Check if on last page.
     */
    public function onLastPage(): bool
    {
        return $this->currentPage >= $this->lastPage();
    }

    /**
     * Get next page number.
     */
    public function nextPage(): ?int
    {
        if ($this->hasMorePages()) {
            return $this->currentPage + 1;
        }

        return null;
    }

    /**
     * Get previous page number.
     */
    public function previousPage(): ?int
    {
        if ($this->currentPage > 1) {
            return $this->currentPage - 1;
        }

        return null;
    }

    /**
     * Get first item number.
     */
    public function firstItem(): ?int
    {
        if ($this->items->isEmpty()) {
            return null;
        }

        return ($this->currentPage - 1) * $this->perPage + 1;
    }

    /**
     * Get last item number.
     */
    public function lastItem(): ?int
    {
        if ($this->items->isEmpty()) {
            return null;
        }

        $first = $this->firstItem();

        return $first !== null ? $first + $this->items->count() - 1 : null;
    }

    /**
     * Check if collection is empty.
     */
    public function isEmpty(): bool
    {
        return $this->items->isEmpty();
    }

    /**
     * Check if collection is not empty.
     */
    public function isNotEmpty(): bool
    {
        return $this->items->isNotEmpty();
    }

    /**
     * Get count of items on current page.
     */
    public function count(): int
    {
        return $this->items->count();
    }

    /**
     * Convert to array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'data' => $this->items->toArray(),
            'current_page' => $this->currentPage,
            'per_page' => $this->perPage,
            'total' => $this->total,
            'last_page' => $this->lastPage(),
            'from' => $this->firstItem(),
            'to' => $this->lastItem(),
            'has_more_pages' => $this->hasMorePages(),
        ];
    }

    /**
     * Convert to JSON.
     */
    public function toJson(int $options = 0): string
    {
        return json_encode($this->toArray(), $options) ?: '{}';
    }

    /**
     * JSON serialize.
     *
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
