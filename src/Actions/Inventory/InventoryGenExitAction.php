<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\Inventory;

use SapB1\Toolkit\Actions\Base\DocumentAction;
use SapB1\Toolkit\Builders\Inventory\InventoryGenExitBuilder;
use SapB1\Toolkit\DTOs\Inventory\InventoryGenExitDto;

/**
 * Inventory General Exit (Goods Issue) actions.
 */
final class InventoryGenExitAction extends DocumentAction
{
    protected string $entity = 'InventoryGenExits';

    /**
     * @param  int|InventoryGenExitBuilder|array<string, mixed>  ...$args
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
     * Find an inventory exit by DocEntry.
     */
    public function find(int $docEntry): InventoryGenExitDto
    {
        $data = $this->getDocument($docEntry);

        return InventoryGenExitDto::fromResponse($data);
    }

    /**
     * Create a new inventory exit.
     *
     * @param  InventoryGenExitBuilder|array<string, mixed>  $data
     */
    public function create(InventoryGenExitBuilder|array $data): InventoryGenExitDto
    {
        $payload = $data instanceof InventoryGenExitBuilder ? $data->build() : $data;
        $response = $this->createDocument($payload);

        return InventoryGenExitDto::fromResponse($response);
    }

    /**
     * Update an existing inventory exit.
     *
     * @param  InventoryGenExitBuilder|array<string, mixed>  $data
     */
    public function update(int $docEntry, InventoryGenExitBuilder|array $data): InventoryGenExitDto
    {
        $payload = $data instanceof InventoryGenExitBuilder ? $data->build() : $data;
        $response = $this->updateDocument($docEntry, $payload);

        return InventoryGenExitDto::fromResponse($response);
    }

    /**
     * Cancel an inventory exit.
     */
    public function cancel(int $docEntry): bool
    {
        return $this->cancelDocument($docEntry);
    }

    /**
     * Close an inventory exit.
     */
    public function close(int $docEntry): bool
    {
        return $this->closeDocument($docEntry);
    }

    /**
     * Get exits by date range.
     *
     * @return array<InventoryGenExitDto>
     */
    public function getByDateRange(string $fromDate, string $toDate): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("DocDate ge '{$fromDate}' and DocDate le '{$toDate}'")
            ->get();

        return array_map(
            fn (array $item) => InventoryGenExitDto::fromResponse($item),
            $response['value'] ?? []
        );
    }

    /**
     * Get all inventory exits.
     *
     * @return array<InventoryGenExitDto>
     */
    public function all(): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->get();

        return array_map(
            fn (array $item) => InventoryGenExitDto::fromResponse($item),
            $response['value'] ?? []
        );
    }
}
