<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\Inventory;

use SapB1\Toolkit\Actions\Base\DocumentAction;
use SapB1\Toolkit\Builders\Inventory\InventoryGenEntryBuilder;
use SapB1\Toolkit\DTOs\Inventory\InventoryGenEntryDto;

/**
 * Inventory General Entry (Goods Receipt) actions.
 */
final class InventoryGenEntryAction extends DocumentAction
{
    protected string $entity = 'InventoryGenEntries';

    /**
     * @param  int|InventoryGenEntryBuilder|array<string, mixed>  ...$args
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
     * Find an inventory entry by DocEntry.
     */
    public function find(int $docEntry): InventoryGenEntryDto
    {
        $data = $this->getDocument($docEntry);

        return InventoryGenEntryDto::fromResponse($data);
    }

    /**
     * Create a new inventory entry.
     *
     * @param  InventoryGenEntryBuilder|array<string, mixed>  $data
     */
    public function create(InventoryGenEntryBuilder|array $data): InventoryGenEntryDto
    {
        $payload = $data instanceof InventoryGenEntryBuilder ? $data->build() : $data;
        $response = $this->createDocument($payload);

        return InventoryGenEntryDto::fromResponse($response);
    }

    /**
     * Update an existing inventory entry.
     *
     * @param  InventoryGenEntryBuilder|array<string, mixed>  $data
     */
    public function update(int $docEntry, InventoryGenEntryBuilder|array $data): InventoryGenEntryDto
    {
        $payload = $data instanceof InventoryGenEntryBuilder ? $data->build() : $data;
        $response = $this->updateDocument($docEntry, $payload);

        return InventoryGenEntryDto::fromResponse($response);
    }

    /**
     * Cancel an inventory entry.
     */
    public function cancel(int $docEntry): bool
    {
        return $this->cancelDocument($docEntry);
    }

    /**
     * Close an inventory entry.
     */
    public function close(int $docEntry): bool
    {
        return $this->closeDocument($docEntry);
    }

    /**
     * Get entries by date range.
     *
     * @return array<InventoryGenEntryDto>
     */
    public function getByDateRange(string $fromDate, string $toDate): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("DocDate ge '{$fromDate}' and DocDate le '{$toDate}'")
            ->get();

        return array_map(
            fn (array $item) => InventoryGenEntryDto::fromResponse($item),
            $response['value'] ?? []
        );
    }

    /**
     * Get all inventory entries.
     *
     * @return array<InventoryGenEntryDto>
     */
    public function all(): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->get();

        return array_map(
            fn (array $item) => InventoryGenEntryDto::fromResponse($item),
            $response['value'] ?? []
        );
    }
}
