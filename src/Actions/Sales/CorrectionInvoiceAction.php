<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\Sales;

use SapB1\Toolkit\Actions\Base\DocumentAction;
use SapB1\Toolkit\Builders\Sales\CorrectionInvoiceBuilder;
use SapB1\Toolkit\DTOs\Sales\CorrectionInvoiceDto;

/**
 * Correction Invoice actions.
 */
final class CorrectionInvoiceAction extends DocumentAction
{
    protected string $entity = 'CorrectionInvoice';

    /**
     * @param  int|CorrectionInvoiceBuilder|array<string, mixed>  ...$args
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
     * Find a correction invoice by DocEntry.
     */
    public function find(int $docEntry): CorrectionInvoiceDto
    {
        $data = $this->getDocument($docEntry);

        return CorrectionInvoiceDto::fromResponse($data);
    }

    /**
     * Create a new correction invoice.
     *
     * @param  CorrectionInvoiceBuilder|array<string, mixed>  $data
     */
    public function create(CorrectionInvoiceBuilder|array $data): CorrectionInvoiceDto
    {
        $payload = $data instanceof CorrectionInvoiceBuilder ? $data->build() : $data;
        $response = $this->createDocument($payload);

        return CorrectionInvoiceDto::fromResponse($response);
    }

    /**
     * Update an existing correction invoice.
     *
     * @param  CorrectionInvoiceBuilder|array<string, mixed>  $data
     */
    public function update(int $docEntry, CorrectionInvoiceBuilder|array $data): CorrectionInvoiceDto
    {
        $payload = $data instanceof CorrectionInvoiceBuilder ? $data->build() : $data;
        $response = $this->updateDocument($docEntry, $payload);

        return CorrectionInvoiceDto::fromResponse($response);
    }

    /**
     * Cancel a correction invoice.
     */
    public function cancel(int $docEntry): bool
    {
        return $this->cancelDocument($docEntry);
    }

    /**
     * Get corrections for a specific invoice.
     *
     * @return array<CorrectionInvoiceDto>
     */
    public function getByOriginalInvoice(int $originalDocEntry): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("CorrectedDocEntry eq {$originalDocEntry}")
            ->get();

        return array_map(
            fn (array $item) => CorrectionInvoiceDto::fromResponse($item),
            $response['value'] ?? []
        );
    }
}
