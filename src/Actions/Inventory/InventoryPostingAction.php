<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\Inventory;

use SapB1\Toolkit\Actions\Base\DocumentAction;
use SapB1\Toolkit\Builders\Inventory\InventoryPostingBuilder;
use SapB1\Toolkit\DTOs\Inventory\InventoryPostingDto;

/**
 * Inventory Posting actions.
 */
final class InventoryPostingAction extends DocumentAction
{
    protected string $entity = 'InventoryPostings';

    public function execute(mixed ...$args): mixed
    {
        if (empty($args)) {
            return $this;
        }

        $first = $args[0];

        return is_int($first) ? $this->find($first) : $this->create($first);
    }

    public function find(int $docEntry): InventoryPostingDto
    {
        return InventoryPostingDto::fromResponse($this->getDocument($docEntry));
    }

    /**
     * @param  InventoryPostingBuilder|array<string, mixed>  $data
     */
    public function create(InventoryPostingBuilder|array $data): InventoryPostingDto
    {
        $payload = $data instanceof InventoryPostingBuilder ? $data->build() : $data;

        return InventoryPostingDto::fromResponse($this->createDocument($payload));
    }

    /**
     * @param  InventoryPostingBuilder|array<string, mixed>  $data
     */
    public function update(int $docEntry, InventoryPostingBuilder|array $data): InventoryPostingDto
    {
        $payload = $data instanceof InventoryPostingBuilder ? $data->build() : $data;

        return InventoryPostingDto::fromResponse($this->updateDocument($docEntry, $payload));
    }

    public function cancel(int $docEntry): bool
    {
        return $this->cancelDocument($docEntry);
    }

    /**
     * @return array<InventoryPostingDto>
     */
    public function getByDateRange(string $fromDate, string $toDate): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("DocDate ge '{$fromDate}' and DocDate le '{$toDate}'")
            ->get();

        return array_map(fn (array $item) => InventoryPostingDto::fromResponse($item), $response['value'] ?? []);
    }

    /**
     * @return array<InventoryPostingDto>
     */
    public function all(): array
    {
        $response = $this->client()->service($this->entity)->queryBuilder()->get();

        return array_map(fn (array $item) => InventoryPostingDto::fromResponse($item), $response['value'] ?? []);
    }
}
