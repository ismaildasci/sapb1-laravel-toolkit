<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\Purchase;

use SapB1\Toolkit\Actions\Base\DocumentAction;
use SapB1\Toolkit\Builders\Purchase\PurchaseInvoiceBuilder;
use SapB1\Toolkit\DTOs\Purchase\PurchaseInvoiceDto;
use SapB1\Toolkit\Enums\DocumentType;

/**
 * Purchase Invoice actions.
 */
final class PurchaseInvoiceAction extends DocumentAction
{
    protected string $entity = 'PurchaseInvoices';

    /**
     * @param  int|PurchaseInvoiceBuilder|array<string, mixed>  ...$args
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
     * Find a purchase invoice by DocEntry.
     */
    public function find(int $docEntry): PurchaseInvoiceDto
    {
        $data = $this->getDocument($docEntry);

        return PurchaseInvoiceDto::fromResponse($data);
    }

    /**
     * Create a new purchase invoice.
     *
     * @param  PurchaseInvoiceBuilder|array<string, mixed>  $data
     */
    public function create(PurchaseInvoiceBuilder|array $data): PurchaseInvoiceDto
    {
        $payload = $data instanceof PurchaseInvoiceBuilder ? $data->build() : $data;
        $response = $this->createDocument($payload);

        return PurchaseInvoiceDto::fromResponse($response);
    }

    /**
     * Update an existing purchase invoice.
     *
     * @param  PurchaseInvoiceBuilder|array<string, mixed>  $data
     */
    public function update(int $docEntry, PurchaseInvoiceBuilder|array $data): PurchaseInvoiceDto
    {
        $payload = $data instanceof PurchaseInvoiceBuilder ? $data->build() : $data;
        $response = $this->updateDocument($docEntry, $payload);

        return PurchaseInvoiceDto::fromResponse($response);
    }

    /**
     * Cancel a purchase invoice.
     */
    public function cancel(int $docEntry): bool
    {
        return $this->cancelDocument($docEntry);
    }

    /**
     * Copy to purchase credit note.
     *
     * @param  array<int>|null  $lineFilter
     */
    public function copyToCreditNote(int $docEntry, ?array $lineFilter = null): mixed
    {
        return $this->copyToDocument(
            $docEntry,
            'PurchaseCreditNotes',
            DocumentType::APInvoice->value,
            $lineFilter
        );
    }

    /**
     * Get unpaid purchase invoices.
     *
     * @return array<PurchaseInvoiceDto>
     */
    public function getUnpaid(): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("DocumentStatus eq 'bost_Open' and PaidToDate lt DocTotal")
            ->get();

        return array_map(
            fn (array $item) => PurchaseInvoiceDto::fromResponse($item),
            $response['value'] ?? []
        );
    }
}
