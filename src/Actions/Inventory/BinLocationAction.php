<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\Inventory;

use SapB1\Toolkit\Actions\Base\BaseAction;
use SapB1\Toolkit\Builders\Inventory\BinLocationBuilder;
use SapB1\Toolkit\DTOs\Inventory\BinLocationDto;

/**
 * Bin Location actions.
 */
final class BinLocationAction extends BaseAction
{
    protected string $entity = 'BinLocations';

    /**
     * @param  int|BinLocationBuilder|array<string, mixed>  ...$args
     */
    public function execute(mixed ...$args): mixed
    {
        if (empty($args)) {
            return $this;
        }

        $first = $args[0];

        if (is_int($first)) {
            return $this->find($first);
        }

        return $this->create($first);
    }

    /**
     * Find a bin location by AbsEntry.
     */
    public function find(int $absEntry): BinLocationDto
    {
        $data = $this->client()
            ->service($this->entity)
            ->find($absEntry);

        return BinLocationDto::fromResponse($data);
    }

    /**
     * Find a bin location by BinCode.
     */
    public function findByCode(string $binCode): ?BinLocationDto
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("BinCode eq '{$binCode}'")
            ->get();

        $items = $response['value'] ?? [];

        return ! empty($items) ? BinLocationDto::fromResponse($items[0]) : null;
    }

    /**
     * Create a new bin location.
     *
     * @param  BinLocationBuilder|array<string, mixed>  $data
     */
    public function create(BinLocationBuilder|array $data): BinLocationDto
    {
        $payload = $data instanceof BinLocationBuilder ? $data->build() : $data;
        $response = $this->client()
            ->service($this->entity)
            ->create($payload);

        return BinLocationDto::fromResponse($response);
    }

    /**
     * Update an existing bin location.
     *
     * @param  BinLocationBuilder|array<string, mixed>  $data
     */
    public function update(int $absEntry, BinLocationBuilder|array $data): BinLocationDto
    {
        $payload = $data instanceof BinLocationBuilder ? $data->build() : $data;
        $response = $this->client()
            ->service($this->entity)
            ->update($absEntry, $payload);

        return BinLocationDto::fromResponse($response);
    }

    /**
     * Delete a bin location.
     */
    public function delete(int $absEntry): bool
    {
        $this->client()
            ->service($this->entity)
            ->delete($absEntry);

        return true;
    }

    /**
     * Get all active bin locations.
     *
     * @return array<BinLocationDto>
     */
    public function getActive(): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("Inactive eq 'tNO'")
            ->get();

        return array_map(
            fn (array $item) => BinLocationDto::fromResponse($item),
            $response['value'] ?? []
        );
    }

    /**
     * Get bin locations by warehouse.
     *
     * @return array<BinLocationDto>
     */
    public function getByWarehouse(string $warehouseCode): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("Warehouse eq '{$warehouseCode}'")
            ->get();

        return array_map(
            fn (array $item) => BinLocationDto::fromResponse($item),
            $response['value'] ?? []
        );
    }

    /**
     * Get all bin locations.
     *
     * @return array<BinLocationDto>
     */
    public function all(): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->get();

        return array_map(
            fn (array $item) => BinLocationDto::fromResponse($item),
            $response['value'] ?? []
        );
    }

    /**
     * Search bin locations by code or description.
     *
     * @return array<BinLocationDto>
     */
    public function search(string $query): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("contains(BinCode, '{$query}') or contains(Description, '{$query}')")
            ->get();

        return array_map(
            fn (array $item) => BinLocationDto::fromResponse($item),
            $response['value'] ?? []
        );
    }
}
