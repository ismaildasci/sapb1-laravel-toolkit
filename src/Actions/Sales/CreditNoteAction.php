<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\Sales;

use SapB1\Toolkit\Actions\Base\DocumentAction;
use SapB1\Toolkit\Builders\Sales\CreditNoteBuilder;
use SapB1\Toolkit\DTOs\Sales\CreditNoteDto;

/**
 * Credit Note actions.
 */
final class CreditNoteAction extends DocumentAction
{
    protected string $entity = 'CreditNotes';

    /**
     * @param  int|CreditNoteBuilder|array<string, mixed>  ...$args
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
     * Find a credit note by DocEntry.
     */
    public function find(int $docEntry): CreditNoteDto
    {
        $data = $this->getDocument($docEntry);

        return CreditNoteDto::fromResponse($data);
    }

    /**
     * Create a new credit note.
     *
     * @param  CreditNoteBuilder|array<string, mixed>  $data
     */
    public function create(CreditNoteBuilder|array $data): CreditNoteDto
    {
        $payload = $data instanceof CreditNoteBuilder ? $data->build() : $data;
        $response = $this->createDocument($payload);

        return CreditNoteDto::fromResponse($response);
    }

    /**
     * Update an existing credit note.
     *
     * @param  CreditNoteBuilder|array<string, mixed>  $data
     */
    public function update(int $docEntry, CreditNoteBuilder|array $data): CreditNoteDto
    {
        $payload = $data instanceof CreditNoteBuilder ? $data->build() : $data;
        $response = $this->updateDocument($docEntry, $payload);

        return CreditNoteDto::fromResponse($response);
    }

    /**
     * Cancel a credit note.
     */
    public function cancel(int $docEntry): bool
    {
        return $this->cancelDocument($docEntry);
    }
}
