<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\Purchase;

use SapB1\Toolkit\Actions\Base\DocumentAction;
use SapB1\Toolkit\Builders\Purchase\PurchaseQuotationBuilder;
use SapB1\Toolkit\DTOs\Purchase\PurchaseQuotationDto;

/**
 * Purchase Quotation actions.
 */
final class PurchaseQuotationAction extends DocumentAction
{
    protected string $entity = 'PurchaseQuotations';

    /**
     * @param  int|PurchaseQuotationBuilder|array<string, mixed>  ...$args
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
     * Find a purchase quotation by DocEntry.
     */
    public function find(int $docEntry): PurchaseQuotationDto
    {
        $data = $this->getDocument($docEntry);

        return PurchaseQuotationDto::fromResponse($data);
    }

    /**
     * Create a new purchase quotation.
     *
     * @param  PurchaseQuotationBuilder|array<string, mixed>  $data
     */
    public function create(PurchaseQuotationBuilder|array $data): PurchaseQuotationDto
    {
        $payload = $data instanceof PurchaseQuotationBuilder ? $data->build() : $data;
        $response = $this->createDocument($payload);

        return PurchaseQuotationDto::fromResponse($response);
    }

    /**
     * Update an existing purchase quotation.
     *
     * @param  PurchaseQuotationBuilder|array<string, mixed>  $data
     */
    public function update(int $docEntry, PurchaseQuotationBuilder|array $data): PurchaseQuotationDto
    {
        $payload = $data instanceof PurchaseQuotationBuilder ? $data->build() : $data;
        $response = $this->updateDocument($docEntry, $payload);

        return PurchaseQuotationDto::fromResponse($response);
    }

    /**
     * Close a purchase quotation.
     */
    public function close(int $docEntry): bool
    {
        return $this->closeDocument($docEntry);
    }

    /**
     * Cancel a purchase quotation.
     */
    public function cancel(int $docEntry): bool
    {
        return $this->cancelDocument($docEntry);
    }

    /**
     * Get all open purchase quotations.
     *
     * @return array<PurchaseQuotationDto>
     */
    public function getOpen(): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("DocumentStatus eq 'bost_Open'")
            ->get();

        return array_map(
            fn (array $item) => PurchaseQuotationDto::fromResponse($item),
            $response['value'] ?? []
        );
    }

    /**
     * Get quotations by vendor.
     *
     * @return array<PurchaseQuotationDto>
     */
    public function getByVendor(string $cardCode): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("CardCode eq '{$cardCode}'")
            ->get();

        return array_map(
            fn (array $item) => PurchaseQuotationDto::fromResponse($item),
            $response['value'] ?? []
        );
    }
}
