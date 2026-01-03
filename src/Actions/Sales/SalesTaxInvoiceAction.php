<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\Sales;

use SapB1\Toolkit\Actions\Base\DocumentAction;
use SapB1\Toolkit\Builders\Sales\SalesTaxInvoiceBuilder;
use SapB1\Toolkit\DTOs\Sales\SalesTaxInvoiceDto;

/**
 * Sales Tax Invoice actions.
 */
final class SalesTaxInvoiceAction extends DocumentAction
{
    protected string $entity = 'SalesTaxInvoices';

    /**
     * @param  int|SalesTaxInvoiceBuilder|array<string, mixed>  ...$args
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
     * Find a sales tax invoice by DocEntry.
     */
    public function find(int $docEntry): SalesTaxInvoiceDto
    {
        $data = $this->getDocument($docEntry);

        return SalesTaxInvoiceDto::fromResponse($data);
    }

    /**
     * Create a new sales tax invoice.
     *
     * @param  SalesTaxInvoiceBuilder|array<string, mixed>  $data
     */
    public function create(SalesTaxInvoiceBuilder|array $data): SalesTaxInvoiceDto
    {
        $payload = $data instanceof SalesTaxInvoiceBuilder ? $data->build() : $data;
        $response = $this->createDocument($payload);

        return SalesTaxInvoiceDto::fromResponse($response);
    }

    /**
     * Update an existing sales tax invoice.
     *
     * @param  SalesTaxInvoiceBuilder|array<string, mixed>  $data
     */
    public function update(int $docEntry, SalesTaxInvoiceBuilder|array $data): SalesTaxInvoiceDto
    {
        $payload = $data instanceof SalesTaxInvoiceBuilder ? $data->build() : $data;
        $response = $this->updateDocument($docEntry, $payload);

        return SalesTaxInvoiceDto::fromResponse($response);
    }

    /**
     * Cancel a sales tax invoice.
     */
    public function cancel(int $docEntry): bool
    {
        return $this->cancelDocument($docEntry);
    }

    /**
     * Get all open sales tax invoices.
     *
     * @return array<SalesTaxInvoiceDto>
     */
    public function getOpen(): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("DocumentStatus eq 'bost_Open'")
            ->get();

        return array_map(
            fn (array $item) => SalesTaxInvoiceDto::fromResponse($item),
            $response['value'] ?? []
        );
    }
}
