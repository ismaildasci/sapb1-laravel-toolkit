<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\Sales;

use SapB1\Toolkit\Actions\Base\DocumentAction;
use SapB1\Toolkit\Builders\Sales\CorrectionInvoiceReversalBuilder;
use SapB1\Toolkit\DTOs\Sales\CorrectionInvoiceReversalDto;

/**
 * Correction Invoice Reversal actions.
 */
final class CorrectionInvoiceReversalAction extends DocumentAction
{
    protected string $entity = 'CorrectionInvoiceReversal';

    /**
     * @param  int|CorrectionInvoiceReversalBuilder|array<string, mixed>  ...$args
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
     * Find a correction invoice reversal by DocEntry.
     */
    public function find(int $docEntry): CorrectionInvoiceReversalDto
    {
        $data = $this->getDocument($docEntry);

        return CorrectionInvoiceReversalDto::fromResponse($data);
    }

    /**
     * Create a new correction invoice reversal.
     *
     * @param  CorrectionInvoiceReversalBuilder|array<string, mixed>  $data
     */
    public function create(CorrectionInvoiceReversalBuilder|array $data): CorrectionInvoiceReversalDto
    {
        $payload = $data instanceof CorrectionInvoiceReversalBuilder ? $data->build() : $data;
        $response = $this->createDocument($payload);

        return CorrectionInvoiceReversalDto::fromResponse($response);
    }

    /**
     * Update an existing correction invoice reversal.
     *
     * @param  CorrectionInvoiceReversalBuilder|array<string, mixed>  $data
     */
    public function update(int $docEntry, CorrectionInvoiceReversalBuilder|array $data): CorrectionInvoiceReversalDto
    {
        $payload = $data instanceof CorrectionInvoiceReversalBuilder ? $data->build() : $data;
        $response = $this->updateDocument($docEntry, $payload);

        return CorrectionInvoiceReversalDto::fromResponse($response);
    }

    /**
     * Cancel a correction invoice reversal.
     */
    public function cancel(int $docEntry): bool
    {
        return $this->cancelDocument($docEntry);
    }

    /**
     * Get reversals for a specific correction.
     *
     * @return array<CorrectionInvoiceReversalDto>
     */
    public function getByOriginalCorrection(int $originalCorrectionEntry): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("OriginalCorrectionEntry eq {$originalCorrectionEntry}")
            ->get();

        return array_map(
            fn (array $item) => CorrectionInvoiceReversalDto::fromResponse($item),
            $response['value'] ?? []
        );
    }
}
