<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\Purchase;

use SapB1\Toolkit\Actions\Base\DocumentAction;
use SapB1\Toolkit\Builders\Purchase\CorrectionPurchaseInvoiceReversalBuilder;
use SapB1\Toolkit\DTOs\Purchase\CorrectionPurchaseInvoiceReversalDto;

/**
 * Correction Purchase Invoice Reversal actions.
 */
final class CorrectionPurchaseInvoiceReversalAction extends DocumentAction
{
    protected string $entity = 'CorrectionPurchaseInvoiceReversal';

    /**
     * @param  int|CorrectionPurchaseInvoiceReversalBuilder|array<string, mixed>  ...$args
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
     * Find a correction purchase invoice reversal by DocEntry.
     */
    public function find(int $docEntry): CorrectionPurchaseInvoiceReversalDto
    {
        $data = $this->getDocument($docEntry);

        return CorrectionPurchaseInvoiceReversalDto::fromResponse($data);
    }

    /**
     * Create a new correction purchase invoice reversal.
     *
     * @param  CorrectionPurchaseInvoiceReversalBuilder|array<string, mixed>  $data
     */
    public function create(CorrectionPurchaseInvoiceReversalBuilder|array $data): CorrectionPurchaseInvoiceReversalDto
    {
        $payload = $data instanceof CorrectionPurchaseInvoiceReversalBuilder ? $data->build() : $data;
        $response = $this->createDocument($payload);

        return CorrectionPurchaseInvoiceReversalDto::fromResponse($response);
    }

    /**
     * Update an existing correction purchase invoice reversal.
     *
     * @param  CorrectionPurchaseInvoiceReversalBuilder|array<string, mixed>  $data
     */
    public function update(int $docEntry, CorrectionPurchaseInvoiceReversalBuilder|array $data): CorrectionPurchaseInvoiceReversalDto
    {
        $payload = $data instanceof CorrectionPurchaseInvoiceReversalBuilder ? $data->build() : $data;
        $response = $this->updateDocument($docEntry, $payload);

        return CorrectionPurchaseInvoiceReversalDto::fromResponse($response);
    }

    /**
     * Cancel a correction purchase invoice reversal.
     */
    public function cancel(int $docEntry): bool
    {
        return $this->cancelDocument($docEntry);
    }

    /**
     * Get reversals for a specific correction.
     *
     * @return array<CorrectionPurchaseInvoiceReversalDto>
     */
    public function getByOriginalCorrection(int $originalCorrectionEntry): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("OriginalCorrectionEntry eq {$originalCorrectionEntry}")
            ->get();

        return array_map(
            fn (array $item) => CorrectionPurchaseInvoiceReversalDto::fromResponse($item),
            $response['value'] ?? []
        );
    }
}
