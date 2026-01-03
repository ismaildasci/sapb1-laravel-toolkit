<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\Inventory;

use SapB1\Toolkit\Actions\Base\DocumentAction;
use SapB1\Toolkit\Builders\Inventory\InventoryCountingBuilder;
use SapB1\Toolkit\DTOs\Inventory\InventoryCountingDto;

/**
 * Inventory Counting actions.
 */
final class InventoryCountingAction extends DocumentAction
{
    protected string $entity = 'InventoryCountings';

    public function execute(mixed ...$args): mixed
    {
        if (empty($args)) {
            return $this;
        }

        return is_int($args[0]) ? $this->find($args[0]) : $this->create($args[0]);
    }

    public function find(int $docEntry): InventoryCountingDto
    {
        return InventoryCountingDto::fromResponse($this->getDocument($docEntry));
    }

    /**
     * @param  InventoryCountingBuilder|array<string, mixed>  $data
     */
    public function create(InventoryCountingBuilder|array $data): InventoryCountingDto
    {
        $payload = $data instanceof InventoryCountingBuilder ? $data->build() : $data;

        return InventoryCountingDto::fromResponse($this->createDocument($payload));
    }

    /**
     * @param  InventoryCountingBuilder|array<string, mixed>  $data
     */
    public function update(int $docEntry, InventoryCountingBuilder|array $data): InventoryCountingDto
    {
        $payload = $data instanceof InventoryCountingBuilder ? $data->build() : $data;

        return InventoryCountingDto::fromResponse($this->updateDocument($docEntry, $payload));
    }

    public function close(int $docEntry): bool
    {
        return $this->closeDocument($docEntry);
    }

    public function cancel(int $docEntry): bool
    {
        return $this->cancelDocument($docEntry);
    }

    /**
     * @return array<InventoryCountingDto>
     */
    public function getOpen(): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("DocumentStatus eq 'bost_Open'")
            ->get();

        return array_map(fn (array $item) => InventoryCountingDto::fromResponse($item), $response['value'] ?? []);
    }

    /**
     * @return array<InventoryCountingDto>
     */
    public function all(): array
    {
        $response = $this->client()->service($this->entity)->queryBuilder()->get();

        return array_map(fn (array $item) => InventoryCountingDto::fromResponse($item), $response['value'] ?? []);
    }
}
