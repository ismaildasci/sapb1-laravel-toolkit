<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\Purchase;

use SapB1\Toolkit\Actions\Base\DocumentAction;
use SapB1\Toolkit\Builders\Purchase\PurchaseOrderBuilder;
use SapB1\Toolkit\DTOs\Purchase\PurchaseOrderDto;
use SapB1\Toolkit\Enums\DocumentType;

/**
 * Purchase Order actions.
 */
final class PurchaseOrderAction extends DocumentAction
{
    protected string $entity = 'PurchaseOrders';

    /**
     * @param  int|PurchaseOrderBuilder|array<string, mixed>  ...$args
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
     * Find a purchase order by DocEntry.
     */
    public function find(int $docEntry): PurchaseOrderDto
    {
        $data = $this->getDocument($docEntry);

        return PurchaseOrderDto::fromResponse($data);
    }

    /**
     * Create a new purchase order.
     *
     * @param  PurchaseOrderBuilder|array<string, mixed>  $data
     */
    public function create(PurchaseOrderBuilder|array $data): PurchaseOrderDto
    {
        $payload = $data instanceof PurchaseOrderBuilder ? $data->build() : $data;
        $response = $this->createDocument($payload);

        return PurchaseOrderDto::fromResponse($response);
    }

    /**
     * Update an existing purchase order.
     *
     * @param  PurchaseOrderBuilder|array<string, mixed>  $data
     */
    public function update(int $docEntry, PurchaseOrderBuilder|array $data): PurchaseOrderDto
    {
        $payload = $data instanceof PurchaseOrderBuilder ? $data->build() : $data;
        $response = $this->updateDocument($docEntry, $payload);

        return PurchaseOrderDto::fromResponse($response);
    }

    /**
     * Close a purchase order.
     */
    public function close(int $docEntry): bool
    {
        return $this->closeDocument($docEntry);
    }

    /**
     * Cancel a purchase order.
     */
    public function cancel(int $docEntry): bool
    {
        return $this->cancelDocument($docEntry);
    }

    /**
     * Copy to goods receipt.
     *
     * @param  array<int>|null  $lineFilter
     */
    public function copyToGoodsReceipt(int $docEntry, ?array $lineFilter = null): mixed
    {
        return $this->copyToDocument(
            $docEntry,
            'PurchaseDeliveryNotes',
            DocumentType::PurchaseOrder->value,
            $lineFilter
        );
    }

    /**
     * Copy to purchase invoice.
     *
     * @param  array<int>|null  $lineFilter
     */
    public function copyToInvoice(int $docEntry, ?array $lineFilter = null): mixed
    {
        return $this->copyToDocument(
            $docEntry,
            'PurchaseInvoices',
            DocumentType::PurchaseOrder->value,
            $lineFilter
        );
    }

    /**
     * Get all open purchase orders.
     *
     * @return array<PurchaseOrderDto>
     */
    public function getOpen(): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("DocumentStatus eq 'bost_Open'")
            ->get();

        return array_map(
            fn (array $item) => PurchaseOrderDto::fromResponse($item),
            $response['value'] ?? []
        );
    }
}
