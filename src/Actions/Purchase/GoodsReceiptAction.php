<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\Purchase;

use SapB1\Toolkit\Actions\Base\DocumentAction;
use SapB1\Toolkit\Builders\Purchase\GoodsReceiptBuilder;
use SapB1\Toolkit\DTOs\Purchase\GoodsReceiptDto;
use SapB1\Toolkit\Enums\DocumentType;

/**
 * Goods Receipt PO actions.
 */
final class GoodsReceiptAction extends DocumentAction
{
    protected string $entity = 'PurchaseDeliveryNotes';

    /**
     * @param  int|GoodsReceiptBuilder|array<string, mixed>  ...$args
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
     * Find a goods receipt by DocEntry.
     */
    public function find(int $docEntry): GoodsReceiptDto
    {
        $data = $this->getDocument($docEntry);

        return GoodsReceiptDto::fromResponse($data);
    }

    /**
     * Create a new goods receipt.
     *
     * @param  GoodsReceiptBuilder|array<string, mixed>  $data
     */
    public function create(GoodsReceiptBuilder|array $data): GoodsReceiptDto
    {
        $payload = $data instanceof GoodsReceiptBuilder ? $data->build() : $data;
        $response = $this->createDocument($payload);

        return GoodsReceiptDto::fromResponse($response);
    }

    /**
     * Update an existing goods receipt.
     *
     * @param  GoodsReceiptBuilder|array<string, mixed>  $data
     */
    public function update(int $docEntry, GoodsReceiptBuilder|array $data): GoodsReceiptDto
    {
        $payload = $data instanceof GoodsReceiptBuilder ? $data->build() : $data;
        $response = $this->updateDocument($docEntry, $payload);

        return GoodsReceiptDto::fromResponse($response);
    }

    /**
     * Close a goods receipt.
     */
    public function close(int $docEntry): bool
    {
        return $this->closeDocument($docEntry);
    }

    /**
     * Cancel a goods receipt.
     */
    public function cancel(int $docEntry): bool
    {
        return $this->cancelDocument($docEntry);
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
            DocumentType::GoodsReceiptPO->value,
            $lineFilter
        );
    }

    /**
     * Copy to purchase return.
     *
     * @param  array<int>|null  $lineFilter
     */
    public function copyToReturn(int $docEntry, ?array $lineFilter = null): mixed
    {
        return $this->copyToDocument(
            $docEntry,
            'PurchaseReturns',
            DocumentType::GoodsReceiptPO->value,
            $lineFilter
        );
    }
}
