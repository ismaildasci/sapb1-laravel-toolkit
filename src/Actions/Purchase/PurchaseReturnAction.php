<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\Purchase;

use SapB1\Toolkit\Actions\Base\DocumentAction;
use SapB1\Toolkit\Builders\Purchase\PurchaseReturnBuilder;
use SapB1\Toolkit\DTOs\Purchase\PurchaseReturnDto;
use SapB1\Toolkit\Enums\DocumentType;

/**
 * Purchase Return actions.
 */
final class PurchaseReturnAction extends DocumentAction
{
    protected string $entity = 'PurchaseReturns';

    /**
     * @param  int|PurchaseReturnBuilder|array<string, mixed>  ...$args
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
     * Find a purchase return by DocEntry.
     */
    public function find(int $docEntry): PurchaseReturnDto
    {
        $data = $this->getDocument($docEntry);

        return PurchaseReturnDto::fromResponse($data);
    }

    /**
     * Create a new purchase return.
     *
     * @param  PurchaseReturnBuilder|array<string, mixed>  $data
     */
    public function create(PurchaseReturnBuilder|array $data): PurchaseReturnDto
    {
        $payload = $data instanceof PurchaseReturnBuilder ? $data->build() : $data;
        $response = $this->createDocument($payload);

        return PurchaseReturnDto::fromResponse($response);
    }

    /**
     * Update an existing purchase return.
     *
     * @param  PurchaseReturnBuilder|array<string, mixed>  $data
     */
    public function update(int $docEntry, PurchaseReturnBuilder|array $data): PurchaseReturnDto
    {
        $payload = $data instanceof PurchaseReturnBuilder ? $data->build() : $data;
        $response = $this->updateDocument($docEntry, $payload);

        return PurchaseReturnDto::fromResponse($response);
    }

    /**
     * Cancel a purchase return.
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
            DocumentType::PurchaseReturn->value,
            $lineFilter
        );
    }
}
