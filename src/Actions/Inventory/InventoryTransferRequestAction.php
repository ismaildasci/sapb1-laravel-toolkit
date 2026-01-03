<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\Inventory;

use SapB1\Toolkit\Actions\Base\DocumentAction;
use SapB1\Toolkit\Builders\Inventory\InventoryTransferRequestBuilder;
use SapB1\Toolkit\DTOs\Inventory\InventoryTransferRequestDto;

/**
 * Inventory Transfer Request actions.
 */
final class InventoryTransferRequestAction extends DocumentAction
{
    protected string $entity = 'InventoryTransferRequests';

    /**
     * @param  int|InventoryTransferRequestBuilder|array<string, mixed>  ...$args
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

    public function find(int $docEntry): InventoryTransferRequestDto
    {
        $data = $this->getDocument($docEntry);

        return InventoryTransferRequestDto::fromResponse($data);
    }

    /**
     * @param  InventoryTransferRequestBuilder|array<string, mixed>  $data
     */
    public function create(InventoryTransferRequestBuilder|array $data): InventoryTransferRequestDto
    {
        $payload = $data instanceof InventoryTransferRequestBuilder ? $data->build() : $data;
        $response = $this->createDocument($payload);

        return InventoryTransferRequestDto::fromResponse($response);
    }

    /**
     * @param  InventoryTransferRequestBuilder|array<string, mixed>  $data
     */
    public function update(int $docEntry, InventoryTransferRequestBuilder|array $data): InventoryTransferRequestDto
    {
        $payload = $data instanceof InventoryTransferRequestBuilder ? $data->build() : $data;
        $response = $this->updateDocument($docEntry, $payload);

        return InventoryTransferRequestDto::fromResponse($response);
    }

    public function cancel(int $docEntry): bool
    {
        return $this->cancelDocument($docEntry);
    }

    public function close(int $docEntry): bool
    {
        return $this->closeDocument($docEntry);
    }

    /**
     * @return array<InventoryTransferRequestDto>
     */
    public function getByWarehouse(string $warehouseCode): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("FromWarehouse eq '{$warehouseCode}' or ToWarehouse eq '{$warehouseCode}'")
            ->get();

        return array_map(
            fn (array $item) => InventoryTransferRequestDto::fromResponse($item),
            $response['value'] ?? []
        );
    }

    /**
     * @return array<InventoryTransferRequestDto>
     */
    public function all(): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->get();

        return array_map(
            fn (array $item) => InventoryTransferRequestDto::fromResponse($item),
            $response['value'] ?? []
        );
    }
}
