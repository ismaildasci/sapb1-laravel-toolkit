<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\Production;

use SapB1\Toolkit\Actions\Base\BaseAction;
use SapB1\Toolkit\Builders\Production\ProductionOrderBuilder;
use SapB1\Toolkit\DTOs\Production\ProductionOrderDto;
use SapB1\Toolkit\Enums\ProductionOrderStatus;

/**
 * Production Order actions.
 */
final class ProductionOrderAction extends BaseAction
{
    protected string $entity = 'ProductionOrders';

    public function execute(mixed ...$args): mixed
    {
        if (empty($args)) {
            return $this;
        }

        return is_int($args[0]) ? $this->find($args[0]) : $this->create($args[0]);
    }

    public function find(int $absoluteEntry): ProductionOrderDto
    {
        $data = $this->client()->service($this->entity)->find($absoluteEntry);

        return ProductionOrderDto::fromResponse($data);
    }

    /**
     * @param  ProductionOrderBuilder|array<string, mixed>  $data
     */
    public function create(ProductionOrderBuilder|array $data): ProductionOrderDto
    {
        $payload = $data instanceof ProductionOrderBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->create($payload);

        return ProductionOrderDto::fromResponse($response);
    }

    /**
     * @param  ProductionOrderBuilder|array<string, mixed>  $data
     */
    public function update(int $absoluteEntry, ProductionOrderBuilder|array $data): ProductionOrderDto
    {
        $payload = $data instanceof ProductionOrderBuilder ? $data->build() : $data;
        $response = $this->client()->service($this->entity)->update($absoluteEntry, $payload);

        return ProductionOrderDto::fromResponse($response);
    }

    /**
     * Cancel a production order.
     */
    public function cancel(int $absoluteEntry): bool
    {
        $this->client()->service($this->entity)->action($absoluteEntry, 'Cancel');

        return true;
    }

    /**
     * @return array<ProductionOrderDto>
     */
    public function all(): array
    {
        $response = $this->client()->service($this->entity)->queryBuilder()->get();

        return array_map(fn (array $item) => ProductionOrderDto::fromResponse($item), $response['value'] ?? []);
    }

    /**
     * Get planned production orders.
     *
     * @return array<ProductionOrderDto>
     */
    public function getPlanned(): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("ProductionOrderStatus eq '".ProductionOrderStatus::Planned->value."'")
            ->get();

        return array_map(fn (array $item) => ProductionOrderDto::fromResponse($item), $response['value'] ?? []);
    }

    /**
     * Get released production orders.
     *
     * @return array<ProductionOrderDto>
     */
    public function getReleased(): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("ProductionOrderStatus eq '".ProductionOrderStatus::Released->value."'")
            ->get();

        return array_map(fn (array $item) => ProductionOrderDto::fromResponse($item), $response['value'] ?? []);
    }

    /**
     * Get production orders by status.
     *
     * @return array<ProductionOrderDto>
     */
    public function getByStatus(ProductionOrderStatus $status): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("ProductionOrderStatus eq '{$status->value}'")
            ->get();

        return array_map(fn (array $item) => ProductionOrderDto::fromResponse($item), $response['value'] ?? []);
    }

    /**
     * Get production orders by item.
     *
     * @return array<ProductionOrderDto>
     */
    public function getByItem(string $itemNo): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("ItemNo eq '{$itemNo}'")
            ->get();

        return array_map(fn (array $item) => ProductionOrderDto::fromResponse($item), $response['value'] ?? []);
    }

    /**
     * Get production orders by customer.
     *
     * @return array<ProductionOrderDto>
     */
    public function getByCustomer(string $customer): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("Customer eq '{$customer}'")
            ->get();

        return array_map(fn (array $item) => ProductionOrderDto::fromResponse($item), $response['value'] ?? []);
    }
}
