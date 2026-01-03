<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\Sales;

use SapB1\Toolkit\Actions\Base\DocumentAction;
use SapB1\Toolkit\Builders\Sales\DeliveryBuilder;
use SapB1\Toolkit\DTOs\Sales\DeliveryNoteDto;
use SapB1\Toolkit\Enums\DocumentType;

/**
 * Delivery Note actions.
 */
final class DeliveryAction extends DocumentAction
{
    protected string $entity = 'DeliveryNotes';

    /**
     * @param  int|DeliveryBuilder|array<string, mixed>  ...$args
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
     * Find a delivery note by DocEntry.
     */
    public function find(int $docEntry): DeliveryNoteDto
    {
        $data = $this->getDocument($docEntry);

        return DeliveryNoteDto::fromResponse($data);
    }

    /**
     * Create a new delivery note.
     *
     * @param  DeliveryBuilder|array<string, mixed>  $data
     */
    public function create(DeliveryBuilder|array $data): DeliveryNoteDto
    {
        $payload = $data instanceof DeliveryBuilder ? $data->build() : $data;
        $response = $this->createDocument($payload);

        return DeliveryNoteDto::fromResponse($response);
    }

    /**
     * Update an existing delivery note.
     *
     * @param  DeliveryBuilder|array<string, mixed>  $data
     */
    public function update(int $docEntry, DeliveryBuilder|array $data): DeliveryNoteDto
    {
        $payload = $data instanceof DeliveryBuilder ? $data->build() : $data;
        $response = $this->updateDocument($docEntry, $payload);

        return DeliveryNoteDto::fromResponse($response);
    }

    /**
     * Close a delivery note.
     */
    public function close(int $docEntry): bool
    {
        return $this->closeDocument($docEntry);
    }

    /**
     * Cancel a delivery note.
     */
    public function cancel(int $docEntry): bool
    {
        return $this->cancelDocument($docEntry);
    }

    /**
     * Copy delivery to invoice.
     *
     * @param  array<int>|null  $lineFilter
     */
    public function copyToInvoice(int $docEntry, ?array $lineFilter = null): mixed
    {
        return $this->copyToDocument(
            $docEntry,
            'Invoices',
            DocumentType::DeliveryNote->value,
            $lineFilter
        );
    }

    /**
     * Copy delivery to return.
     *
     * @param  array<int>|null  $lineFilter
     */
    public function copyToReturn(int $docEntry, ?array $lineFilter = null): mixed
    {
        return $this->copyToDocument(
            $docEntry,
            'Returns',
            DocumentType::DeliveryNote->value,
            $lineFilter
        );
    }
}
