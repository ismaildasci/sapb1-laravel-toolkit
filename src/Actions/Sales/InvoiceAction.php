<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\Sales;

use SapB1\Toolkit\Actions\Base\DocumentAction;
use SapB1\Toolkit\Builders\Sales\InvoiceBuilder;
use SapB1\Toolkit\DTOs\Sales\InvoiceDto;
use SapB1\Toolkit\Enums\DocumentType;

/**
 * Sales Invoice actions.
 */
final class InvoiceAction extends DocumentAction
{
    protected string $entity = 'Invoices';

    /**
     * @param  int|InvoiceBuilder|array<string, mixed>  ...$args
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
     * Find an invoice by DocEntry.
     */
    public function find(int $docEntry): InvoiceDto
    {
        $data = $this->getDocument($docEntry);

        return InvoiceDto::fromResponse($data);
    }

    /**
     * Create a new invoice.
     *
     * @param  InvoiceBuilder|array<string, mixed>  $data
     */
    public function create(InvoiceBuilder|array $data): InvoiceDto
    {
        $payload = $data instanceof InvoiceBuilder ? $data->build() : $data;
        $response = $this->createDocument($payload);

        return InvoiceDto::fromResponse($response);
    }

    /**
     * Update an existing invoice.
     *
     * @param  InvoiceBuilder|array<string, mixed>  $data
     */
    public function update(int $docEntry, InvoiceBuilder|array $data): InvoiceDto
    {
        $payload = $data instanceof InvoiceBuilder ? $data->build() : $data;
        $response = $this->updateDocument($docEntry, $payload);

        return InvoiceDto::fromResponse($response);
    }

    /**
     * Cancel an invoice.
     */
    public function cancel(int $docEntry): bool
    {
        return $this->cancelDocument($docEntry);
    }

    /**
     * Copy invoice to credit note.
     *
     * @param  array<int>|null  $lineFilter
     */
    public function copyToCreditNote(int $docEntry, ?array $lineFilter = null): mixed
    {
        return $this->copyToDocument(
            $docEntry,
            'CreditNotes',
            DocumentType::ARInvoice->value,
            $lineFilter
        );
    }

    /**
     * Get unpaid invoices.
     *
     * @return array<InvoiceDto>
     */
    public function getUnpaid(): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("DocumentStatus eq 'bost_Open' and PaidToDate lt DocTotal")
            ->get();

        return array_map(
            fn (array $item) => InvoiceDto::fromResponse($item),
            $response['value'] ?? []
        );
    }

    /**
     * Get invoices by customer.
     *
     * @return array<InvoiceDto>
     */
    public function getByCustomer(string $cardCode): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("CardCode eq '{$cardCode}'")
            ->get();

        return array_map(
            fn (array $item) => InvoiceDto::fromResponse($item),
            $response['value'] ?? []
        );
    }
}
