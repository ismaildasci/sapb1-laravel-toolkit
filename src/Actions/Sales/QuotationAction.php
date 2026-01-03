<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\Sales;

use SapB1\Toolkit\Actions\Base\DocumentAction;
use SapB1\Toolkit\Builders\Sales\QuotationBuilder;
use SapB1\Toolkit\DTOs\Sales\QuotationDto;
use SapB1\Toolkit\Enums\DocumentType;

/**
 * Sales Quotation actions.
 */
final class QuotationAction extends DocumentAction
{
    protected string $entity = 'Quotations';

    /**
     * @param  int|QuotationBuilder|array<string, mixed>  ...$args
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
     * Find a quotation by DocEntry.
     */
    public function find(int $docEntry): QuotationDto
    {
        $data = $this->getDocument($docEntry);

        return QuotationDto::fromResponse($data);
    }

    /**
     * Create a new quotation.
     *
     * @param  QuotationBuilder|array<string, mixed>  $data
     */
    public function create(QuotationBuilder|array $data): QuotationDto
    {
        $payload = $data instanceof QuotationBuilder ? $data->build() : $data;
        $response = $this->createDocument($payload);

        return QuotationDto::fromResponse($response);
    }

    /**
     * Update an existing quotation.
     *
     * @param  QuotationBuilder|array<string, mixed>  $data
     */
    public function update(int $docEntry, QuotationBuilder|array $data): QuotationDto
    {
        $payload = $data instanceof QuotationBuilder ? $data->build() : $data;
        $response = $this->updateDocument($docEntry, $payload);

        return QuotationDto::fromResponse($response);
    }

    /**
     * Close a quotation.
     */
    public function close(int $docEntry): bool
    {
        return $this->closeDocument($docEntry);
    }

    /**
     * Cancel a quotation.
     */
    public function cancel(int $docEntry): bool
    {
        return $this->cancelDocument($docEntry);
    }

    /**
     * Copy quotation to order.
     *
     * @param  array<int>|null  $lineFilter
     */
    public function copyToOrder(int $docEntry, ?array $lineFilter = null): mixed
    {
        return $this->copyToDocument(
            $docEntry,
            'Orders',
            DocumentType::SalesQuotation->value,
            $lineFilter
        );
    }

    /**
     * Get all open quotations.
     *
     * @return array<QuotationDto>
     */
    public function getOpen(): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("DocumentStatus eq 'bost_Open'")
            ->get();

        return array_map(
            fn (array $item) => QuotationDto::fromResponse($item),
            $response['value'] ?? []
        );
    }
}
