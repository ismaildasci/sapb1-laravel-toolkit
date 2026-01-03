<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\Purchase;

use SapB1\Toolkit\Actions\Base\DocumentAction;
use SapB1\Toolkit\Builders\Purchase\CorrectionPurchaseInvoiceBuilder;
use SapB1\Toolkit\DTOs\Purchase\CorrectionPurchaseInvoiceDto;

/**
 * Correction Purchase Invoice actions.
 */
final class CorrectionPurchaseInvoiceAction extends DocumentAction
{
    protected string $entity = 'CorrectionPurchaseInvoice';

    /**
     * @param  int|CorrectionPurchaseInvoiceBuilder|array<string, mixed>  ...$args
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
     * Find a correction purchase invoice by DocEntry.
     */
    public function find(int $docEntry): CorrectionPurchaseInvoiceDto
    {
        $data = $this->getDocument($docEntry);

        return CorrectionPurchaseInvoiceDto::fromResponse($data);
    }

    /**
     * Create a new correction purchase invoice.
     *
     * @param  CorrectionPurchaseInvoiceBuilder|array<string, mixed>  $data
     */
    public function create(CorrectionPurchaseInvoiceBuilder|array $data): CorrectionPurchaseInvoiceDto
    {
        $payload = $data instanceof CorrectionPurchaseInvoiceBuilder ? $data->build() : $data;
        $response = $this->createDocument($payload);

        return CorrectionPurchaseInvoiceDto::fromResponse($response);
    }

    /**
     * Update an existing correction purchase invoice.
     *
     * @param  CorrectionPurchaseInvoiceBuilder|array<string, mixed>  $data
     */
    public function update(int $docEntry, CorrectionPurchaseInvoiceBuilder|array $data): CorrectionPurchaseInvoiceDto
    {
        $payload = $data instanceof CorrectionPurchaseInvoiceBuilder ? $data->build() : $data;
        $response = $this->updateDocument($docEntry, $payload);

        return CorrectionPurchaseInvoiceDto::fromResponse($response);
    }

    /**
     * Cancel a correction purchase invoice.
     */
    public function cancel(int $docEntry): bool
    {
        return $this->cancelDocument($docEntry);
    }

    /**
     * Get corrections for a specific invoice.
     *
     * @return array<CorrectionPurchaseInvoiceDto>
     */
    public function getByOriginalInvoice(int $originalDocEntry): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("CorrectedDocEntry eq {$originalDocEntry}")
            ->get();

        return array_map(
            fn (array $item) => CorrectionPurchaseInvoiceDto::fromResponse($item),
            $response['value'] ?? []
        );
    }
}
