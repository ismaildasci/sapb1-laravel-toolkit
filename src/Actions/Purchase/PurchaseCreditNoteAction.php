<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\Purchase;

use SapB1\Toolkit\Actions\Base\DocumentAction;
use SapB1\Toolkit\Builders\Purchase\PurchaseCreditNoteBuilder;
use SapB1\Toolkit\DTOs\Purchase\PurchaseCreditNoteDto;

/**
 * Purchase Credit Note actions.
 */
final class PurchaseCreditNoteAction extends DocumentAction
{
    protected string $entity = 'PurchaseCreditNotes';

    /**
     * @param  int|PurchaseCreditNoteBuilder|array<string, mixed>  ...$args
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
     * Find a purchase credit note by DocEntry.
     */
    public function find(int $docEntry): PurchaseCreditNoteDto
    {
        $data = $this->getDocument($docEntry);

        return PurchaseCreditNoteDto::fromResponse($data);
    }

    /**
     * Create a new purchase credit note.
     *
     * @param  PurchaseCreditNoteBuilder|array<string, mixed>  $data
     */
    public function create(PurchaseCreditNoteBuilder|array $data): PurchaseCreditNoteDto
    {
        $payload = $data instanceof PurchaseCreditNoteBuilder ? $data->build() : $data;
        $response = $this->createDocument($payload);

        return PurchaseCreditNoteDto::fromResponse($response);
    }

    /**
     * Update an existing purchase credit note.
     *
     * @param  PurchaseCreditNoteBuilder|array<string, mixed>  $data
     */
    public function update(int $docEntry, PurchaseCreditNoteBuilder|array $data): PurchaseCreditNoteDto
    {
        $payload = $data instanceof PurchaseCreditNoteBuilder ? $data->build() : $data;
        $response = $this->updateDocument($docEntry, $payload);

        return PurchaseCreditNoteDto::fromResponse($response);
    }

    /**
     * Cancel a purchase credit note.
     */
    public function cancel(int $docEntry): bool
    {
        return $this->cancelDocument($docEntry);
    }

    /**
     * Get all open purchase credit notes.
     *
     * @return array<PurchaseCreditNoteDto>
     */
    public function getOpen(): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("DocumentStatus eq 'bost_Open'")
            ->get();

        return array_map(
            fn (array $item) => PurchaseCreditNoteDto::fromResponse($item),
            $response['value'] ?? []
        );
    }
}
