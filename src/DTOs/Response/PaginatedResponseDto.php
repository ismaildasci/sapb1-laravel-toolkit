<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs\Response;

use SapB1\Toolkit\DTOs\Base\BaseDto;

/**
 * @template T
 *
 * @phpstan-consistent-constructor
 */
final class PaginatedResponseDto extends BaseDto
{
    /**
     * @param  array<T>  $items
     */
    public function __construct(
        public readonly array $items = [],
        public readonly int $total = 0,
        public readonly int $skip = 0,
        public readonly int $top = 20,
        public readonly ?string $nextLink = null,
    ) {}

    protected static function mapFromArray(array $data): array
    {
        return [
            'items' => $data['value'] ?? $data['items'] ?? [],
            'total' => isset($data['odata.count']) ? (int) $data['odata.count'] : count($data['value'] ?? $data['items'] ?? []),
            'skip' => (int) ($data['skip'] ?? 0),
            'top' => (int) ($data['top'] ?? 20),
            'nextLink' => $data['odata.nextLink'] ?? $data['nextLink'] ?? null,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $result = [
            'items' => $this->items,
            'total' => $this->total,
            'skip' => $this->skip,
            'top' => $this->top,
        ];

        if ($this->nextLink !== null) {
            $result['nextLink'] = $this->nextLink;
        }

        return $result;
    }

    public function hasNextPage(): bool
    {
        return $this->nextLink !== null;
    }

    public function getCurrentPage(): int
    {
        if ($this->top === 0) {
            return 1;
        }

        return (int) floor($this->skip / $this->top) + 1;
    }

    public function getTotalPages(): int
    {
        if ($this->top === 0) {
            return 1;
        }

        return (int) ceil($this->total / $this->top);
    }

    public function isEmpty(): bool
    {
        return empty($this->items);
    }

    public function count(): int
    {
        return count($this->items);
    }
}
