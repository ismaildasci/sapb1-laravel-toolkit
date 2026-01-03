<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\Inventory;

use SapB1\Toolkit\Actions\Base\BaseAction;
use SapB1\Toolkit\Builders\Inventory\SerialNumberDetailBuilder;
use SapB1\Toolkit\DTOs\Inventory\SerialNumberDetailDto;

/**
 * Serial Number Detail actions.
 */
final class SerialNumberDetailAction extends BaseAction
{
    protected string $entity = 'SerialNumberDetails';

    /**
     * @param  int|SerialNumberDetailBuilder|array<string, mixed>  ...$args
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
     * Find a serial number detail by DocEntry.
     */
    public function find(int $docEntry): SerialNumberDetailDto
    {
        $data = $this->client()
            ->service($this->entity)
            ->find($docEntry);

        return SerialNumberDetailDto::fromResponse($data);
    }

    /**
     * Create a new serial number detail.
     *
     * @param  SerialNumberDetailBuilder|array<string, mixed>  $data
     */
    public function create(SerialNumberDetailBuilder|array $data): SerialNumberDetailDto
    {
        $payload = $data instanceof SerialNumberDetailBuilder ? $data->build() : $data;
        $response = $this->client()
            ->service($this->entity)
            ->create($payload);

        return SerialNumberDetailDto::fromResponse($response);
    }

    /**
     * Update an existing serial number detail.
     *
     * @param  SerialNumberDetailBuilder|array<string, mixed>  $data
     */
    public function update(int $docEntry, SerialNumberDetailBuilder|array $data): SerialNumberDetailDto
    {
        $payload = $data instanceof SerialNumberDetailBuilder ? $data->build() : $data;
        $response = $this->client()
            ->service($this->entity)
            ->update($docEntry, $payload);

        return SerialNumberDetailDto::fromResponse($response);
    }

    /**
     * Delete a serial number detail.
     */
    public function delete(int $docEntry): bool
    {
        $this->client()
            ->service($this->entity)
            ->delete($docEntry);

        return true;
    }

    /**
     * Get serial numbers by item code.
     *
     * @return array<SerialNumberDetailDto>
     */
    public function getByItem(string $itemCode): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("ItemCode eq '{$itemCode}'")
            ->get();

        return array_map(
            fn (array $item) => SerialNumberDetailDto::fromResponse($item),
            $response['value'] ?? []
        );
    }

    /**
     * Get serial number by serial.
     */
    public function getBySerial(string $serialNumber): ?SerialNumberDetailDto
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("SerialNumber eq '{$serialNumber}'")
            ->get();

        $items = $response['value'] ?? [];

        return ! empty($items) ? SerialNumberDetailDto::fromResponse($items[0]) : null;
    }

    /**
     * Get available serial numbers for an item.
     *
     * @return array<SerialNumberDetailDto>
     */
    public function getAvailable(string $itemCode): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("ItemCode eq '{$itemCode}' and Status eq 'bdsStatus_Available'")
            ->get();

        return array_map(
            fn (array $item) => SerialNumberDetailDto::fromResponse($item),
            $response['value'] ?? []
        );
    }

    /**
     * Get serial numbers by warehouse.
     *
     * @return array<SerialNumberDetailDto>
     */
    public function getByWarehouse(string $warehouseCode): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("WarehouseCode eq '{$warehouseCode}'")
            ->get();

        return array_map(
            fn (array $item) => SerialNumberDetailDto::fromResponse($item),
            $response['value'] ?? []
        );
    }

    /**
     * Get all serial number details.
     *
     * @return array<SerialNumberDetailDto>
     */
    public function all(): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->get();

        return array_map(
            fn (array $item) => SerialNumberDetailDto::fromResponse($item),
            $response['value'] ?? []
        );
    }
}
