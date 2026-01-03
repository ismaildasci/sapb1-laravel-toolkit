<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\Sales;

use SapB1\Toolkit\Actions\Base\DocumentAction;
use SapB1\Toolkit\Builders\Sales\OrderBuilder;
use SapB1\Toolkit\DTOs\Sales\OrderDto;
use SapB1\Toolkit\Enums\DocumentType;

/**
 * Sales Order actions.
 */
final class OrderAction extends DocumentAction
{
    protected string $entity = 'Orders';

    /**
     * @param  int|OrderBuilder|array<string, mixed>  ...$args
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
     * Find a sales order by DocEntry.
     */
    public function find(int $docEntry): OrderDto
    {
        $data = $this->getDocument($docEntry);

        return OrderDto::fromResponse($data);
    }

    /**
     * Create a new sales order.
     *
     * @param  OrderBuilder|array<string, mixed>  $data
     */
    public function create(OrderBuilder|array $data): OrderDto
    {
        $payload = $data instanceof OrderBuilder ? $data->build() : $data;
        $response = $this->createDocument($payload);

        return OrderDto::fromResponse($response);
    }

    /**
     * Update an existing sales order.
     *
     * @param  OrderBuilder|array<string, mixed>  $data
     */
    public function update(int $docEntry, OrderBuilder|array $data): OrderDto
    {
        $payload = $data instanceof OrderBuilder ? $data->build() : $data;
        $this->updateDocument($docEntry, $payload);

        // SAP B1 PATCH returns 204 No Content, so we fetch the updated document
        return $this->find($docEntry);
    }

    /**
     * Close a sales order.
     */
    public function close(int $docEntry): bool
    {
        return $this->closeDocument($docEntry);
    }

    /**
     * Cancel a sales order.
     */
    public function cancel(int $docEntry): bool
    {
        return $this->cancelDocument($docEntry);
    }

    /**
     * Copy order to delivery note.
     *
     * @param  array<int>|null  $lineFilter
     */
    public function copyToDelivery(int $docEntry, ?array $lineFilter = null): mixed
    {
        return $this->copyToDocument(
            $docEntry,
            'DeliveryNotes',
            DocumentType::SalesOrder->value,
            $lineFilter
        );
    }

    /**
     * Copy order to invoice.
     *
     * @param  array<int>|null  $lineFilter
     */
    public function copyToInvoice(int $docEntry, ?array $lineFilter = null): mixed
    {
        return $this->copyToDocument(
            $docEntry,
            'Invoices',
            DocumentType::SalesOrder->value,
            $lineFilter
        );
    }

    /**
     * Get all open orders.
     *
     * @return array<OrderDto>
     */
    public function getOpen(): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("DocumentStatus eq 'bost_Open'")
            ->get();

        return array_map(
            fn (array $item) => OrderDto::fromResponse($item),
            $response['value'] ?? []
        );
    }
}
