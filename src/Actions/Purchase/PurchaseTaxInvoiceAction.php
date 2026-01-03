<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\Purchase;

use SapB1\Toolkit\Actions\Base\DocumentAction;
use SapB1\Toolkit\Builders\Purchase\PurchaseTaxInvoiceBuilder;
use SapB1\Toolkit\DTOs\Purchase\PurchaseTaxInvoiceDto;

/**
 * Purchase Tax Invoice actions.
 */
final class PurchaseTaxInvoiceAction extends DocumentAction
{
    protected string $entity = 'PurchaseTaxInvoices';

    /**
     * @param  int|PurchaseTaxInvoiceBuilder|array<string, mixed>  ...$args
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
     * Find a purchase tax invoice by DocEntry.
     */
    public function find(int $docEntry): PurchaseTaxInvoiceDto
    {
        $data = $this->getDocument($docEntry);

        return PurchaseTaxInvoiceDto::fromResponse($data);
    }

    /**
     * Create a new purchase tax invoice.
     *
     * @param  PurchaseTaxInvoiceBuilder|array<string, mixed>  $data
     */
    public function create(PurchaseTaxInvoiceBuilder|array $data): PurchaseTaxInvoiceDto
    {
        $payload = $data instanceof PurchaseTaxInvoiceBuilder ? $data->build() : $data;
        $response = $this->createDocument($payload);

        return PurchaseTaxInvoiceDto::fromResponse($response);
    }

    /**
     * Update an existing purchase tax invoice.
     *
     * @param  PurchaseTaxInvoiceBuilder|array<string, mixed>  $data
     */
    public function update(int $docEntry, PurchaseTaxInvoiceBuilder|array $data): PurchaseTaxInvoiceDto
    {
        $payload = $data instanceof PurchaseTaxInvoiceBuilder ? $data->build() : $data;
        $response = $this->updateDocument($docEntry, $payload);

        return PurchaseTaxInvoiceDto::fromResponse($response);
    }

    /**
     * Cancel a purchase tax invoice.
     */
    public function cancel(int $docEntry): bool
    {
        return $this->cancelDocument($docEntry);
    }

    /**
     * Get all open purchase tax invoices.
     *
     * @return array<PurchaseTaxInvoiceDto>
     */
    public function getOpen(): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("DocumentStatus eq 'bost_Open'")
            ->get();

        return array_map(
            fn (array $item) => PurchaseTaxInvoiceDto::fromResponse($item),
            $response['value'] ?? []
        );
    }
}
