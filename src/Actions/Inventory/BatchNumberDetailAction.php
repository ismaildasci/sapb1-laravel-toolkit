<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\Inventory;

use SapB1\Toolkit\Actions\Base\BaseAction;
use SapB1\Toolkit\Builders\Inventory\BatchNumberDetailBuilder;
use SapB1\Toolkit\DTOs\Inventory\BatchNumberDetailDto;

/**
 * Batch Number Detail actions.
 */
final class BatchNumberDetailAction extends BaseAction
{
    protected string $entity = 'BatchNumberDetails';

    /**
     * @param  int|BatchNumberDetailBuilder|array<string, mixed>  ...$args
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
     * Find a batch number detail by DocEntry.
     */
    public function find(int $docEntry): BatchNumberDetailDto
    {
        $data = $this->client()
            ->service($this->entity)
            ->find($docEntry);

        return BatchNumberDetailDto::fromResponse($data);
    }

    /**
     * Create a new batch number detail.
     *
     * @param  BatchNumberDetailBuilder|array<string, mixed>  $data
     */
    public function create(BatchNumberDetailBuilder|array $data): BatchNumberDetailDto
    {
        $payload = $data instanceof BatchNumberDetailBuilder ? $data->build() : $data;
        $response = $this->client()
            ->service($this->entity)
            ->create($payload);

        return BatchNumberDetailDto::fromResponse($response);
    }

    /**
     * Update an existing batch number detail.
     *
     * @param  BatchNumberDetailBuilder|array<string, mixed>  $data
     */
    public function update(int $docEntry, BatchNumberDetailBuilder|array $data): BatchNumberDetailDto
    {
        $payload = $data instanceof BatchNumberDetailBuilder ? $data->build() : $data;
        $response = $this->client()
            ->service($this->entity)
            ->update($docEntry, $payload);

        return BatchNumberDetailDto::fromResponse($response);
    }

    /**
     * Delete a batch number detail.
     */
    public function delete(int $docEntry): bool
    {
        $this->client()
            ->service($this->entity)
            ->delete($docEntry);

        return true;
    }

    /**
     * Get batch numbers by item code.
     *
     * @return array<BatchNumberDetailDto>
     */
    public function getByItem(string $itemCode): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("ItemCode eq '{$itemCode}'")
            ->get();

        return array_map(
            fn (array $item) => BatchNumberDetailDto::fromResponse($item),
            $response['value'] ?? []
        );
    }

    /**
     * Get batch numbers by batch number.
     *
     * @return array<BatchNumberDetailDto>
     */
    public function getByBatch(string $batchNumber): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("Batch eq '{$batchNumber}'")
            ->get();

        return array_map(
            fn (array $item) => BatchNumberDetailDto::fromResponse($item),
            $response['value'] ?? []
        );
    }

    /**
     * Get available batch numbers for an item.
     *
     * @return array<BatchNumberDetailDto>
     */
    public function getAvailable(string $itemCode): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("ItemCode eq '{$itemCode}' and Quantity gt 0")
            ->get();

        return array_map(
            fn (array $item) => BatchNumberDetailDto::fromResponse($item),
            $response['value'] ?? []
        );
    }

    /**
     * Get expiring batches.
     *
     * @return array<BatchNumberDetailDto>
     */
    public function getExpiring(string $beforeDate): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("ExpiryDate le '{$beforeDate}' and Quantity gt 0")
            ->get();

        return array_map(
            fn (array $item) => BatchNumberDetailDto::fromResponse($item),
            $response['value'] ?? []
        );
    }

    /**
     * Get all batch number details.
     *
     * @return array<BatchNumberDetailDto>
     */
    public function all(): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->get();

        return array_map(
            fn (array $item) => BatchNumberDetailDto::fromResponse($item),
            $response['value'] ?? []
        );
    }
}
