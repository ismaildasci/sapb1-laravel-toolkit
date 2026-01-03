<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\Inventory;

use SapB1\Toolkit\Actions\Base\BaseAction;
use SapB1\Toolkit\Builders\Inventory\WarehouseBuilder;
use SapB1\Toolkit\DTOs\Inventory\WarehouseDto;

/**
 * Warehouse actions.
 */
final class WarehouseAction extends BaseAction
{
    protected string $entity = 'Warehouses';

    /**
     * @param  string|WarehouseBuilder|array<string, mixed>  ...$args
     */
    public function execute(mixed ...$args): mixed
    {
        if (empty($args)) {
            return $this;
        }

        $first = $args[0];

        if (is_string($first)) {
            return $this->find($first);
        }

        return $this->create($first);
    }

    /**
     * Find a warehouse by WarehouseCode.
     */
    public function find(string $warehouseCode): WarehouseDto
    {
        $data = $this->client()
            ->service($this->entity)
            ->find($warehouseCode);

        return WarehouseDto::fromResponse($data);
    }

    /**
     * Create a new warehouse.
     *
     * @param  WarehouseBuilder|array<string, mixed>  $data
     */
    public function create(WarehouseBuilder|array $data): WarehouseDto
    {
        $payload = $data instanceof WarehouseBuilder ? $data->build() : $data;
        $response = $this->client()
            ->service($this->entity)
            ->create($payload);

        return WarehouseDto::fromResponse($response);
    }

    /**
     * Update an existing warehouse.
     *
     * @param  WarehouseBuilder|array<string, mixed>  $data
     */
    public function update(string $warehouseCode, WarehouseBuilder|array $data): WarehouseDto
    {
        $payload = $data instanceof WarehouseBuilder ? $data->build() : $data;
        $response = $this->client()
            ->service($this->entity)
            ->update($warehouseCode, $payload);

        return WarehouseDto::fromResponse($response);
    }

    /**
     * Get all active warehouses.
     *
     * @return array<WarehouseDto>
     */
    public function getActive(): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("Inactive eq 'tNO'")
            ->get();

        return array_map(
            fn (array $item) => WarehouseDto::fromResponse($item),
            $response['value'] ?? []
        );
    }

    /**
     * Get all warehouses.
     *
     * @return array<WarehouseDto>
     */
    public function all(): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->get();

        return array_map(
            fn (array $item) => WarehouseDto::fromResponse($item),
            $response['value'] ?? []
        );
    }
}
