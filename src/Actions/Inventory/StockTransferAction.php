<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\Inventory;

use SapB1\Toolkit\Actions\Base\DocumentAction;
use SapB1\Toolkit\Builders\Inventory\StockTransferBuilder;
use SapB1\Toolkit\DTOs\Inventory\StockTransferDto;

/**
 * Stock Transfer actions.
 */
final class StockTransferAction extends DocumentAction
{
    protected string $entity = 'StockTransfers';

    /**
     * @param  int|StockTransferBuilder|array<string, mixed>  ...$args
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
     * Find a stock transfer by DocEntry.
     */
    public function find(int $docEntry): StockTransferDto
    {
        $data = $this->getDocument($docEntry);

        return StockTransferDto::fromResponse($data);
    }

    /**
     * Create a new stock transfer.
     *
     * @param  StockTransferBuilder|array<string, mixed>  $data
     */
    public function create(StockTransferBuilder|array $data): StockTransferDto
    {
        $payload = $data instanceof StockTransferBuilder ? $data->build() : $data;
        $response = $this->createDocument($payload);

        return StockTransferDto::fromResponse($response);
    }

    /**
     * Update an existing stock transfer.
     *
     * @param  StockTransferBuilder|array<string, mixed>  $data
     */
    public function update(int $docEntry, StockTransferBuilder|array $data): StockTransferDto
    {
        $payload = $data instanceof StockTransferBuilder ? $data->build() : $data;
        $response = $this->updateDocument($docEntry, $payload);

        return StockTransferDto::fromResponse($response);
    }

    /**
     * Cancel a stock transfer.
     */
    public function cancel(int $docEntry): bool
    {
        return $this->cancelDocument($docEntry);
    }

    /**
     * Get transfers by warehouse.
     *
     * @return array<StockTransferDto>
     */
    public function getByWarehouse(string $warehouseCode): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("FromWarehouse eq '{$warehouseCode}' or ToWarehouse eq '{$warehouseCode}'")
            ->get();

        return array_map(
            fn (array $item) => StockTransferDto::fromResponse($item),
            $response['value'] ?? []
        );
    }
}
