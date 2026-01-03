<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\Inventory;

use SapB1\Toolkit\Actions\Base\BaseAction;
use SapB1\Toolkit\Builders\Inventory\ItemBuilder;
use SapB1\Toolkit\DTOs\Inventory\ItemDto;

/**
 * Item Master Data actions.
 */
final class ItemAction extends BaseAction
{
    protected string $entity = 'Items';

    /**
     * @param  string|ItemBuilder|array<string, mixed>  ...$args
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
     * Find an item by ItemCode.
     */
    public function find(string $itemCode): ItemDto
    {
        $data = $this->client()
            ->service($this->entity)
            ->find($itemCode);

        return ItemDto::fromResponse($data);
    }

    /**
     * Create a new item.
     *
     * @param  ItemBuilder|array<string, mixed>  $data
     */
    public function create(ItemBuilder|array $data): ItemDto
    {
        $payload = $data instanceof ItemBuilder ? $data->build() : $data;
        $response = $this->client()
            ->service($this->entity)
            ->create($payload);

        return ItemDto::fromResponse($response);
    }

    /**
     * Update an existing item.
     *
     * @param  ItemBuilder|array<string, mixed>  $data
     */
    public function update(string $itemCode, ItemBuilder|array $data): ItemDto
    {
        $payload = $data instanceof ItemBuilder ? $data->build() : $data;
        $response = $this->client()
            ->service($this->entity)
            ->update($itemCode, $payload);

        return ItemDto::fromResponse($response);
    }

    /**
     * Delete an item.
     */
    public function delete(string $itemCode): bool
    {
        $this->client()
            ->service($this->entity)
            ->delete($itemCode);

        return true;
    }

    /**
     * Get all active items.
     *
     * @return array<ItemDto>
     */
    public function getActive(): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("Valid eq 'tYES' and Frozen eq 'tNO'")
            ->get();

        return array_map(
            fn (array $item) => ItemDto::fromResponse($item),
            $response['value'] ?? []
        );
    }

    /**
     * Get items by group.
     *
     * @return array<ItemDto>
     */
    public function getByGroup(int $groupCode): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("ItemsGroupCode eq {$groupCode}")
            ->get();

        return array_map(
            fn (array $item) => ItemDto::fromResponse($item),
            $response['value'] ?? []
        );
    }

    /**
     * Search items by name.
     *
     * @return array<ItemDto>
     */
    public function search(string $query): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("contains(ItemName, '{$query}')")
            ->get();

        return array_map(
            fn (array $item) => ItemDto::fromResponse($item),
            $response['value'] ?? []
        );
    }
}
