<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\Sales;

use SapB1\Toolkit\Actions\Base\DocumentAction;
use SapB1\Toolkit\Builders\Sales\DraftBuilder;
use SapB1\Toolkit\DTOs\Sales\DraftDto;
use SapB1\Toolkit\Enums\DocumentType;

/**
 * Draft actions.
 */
final class DraftAction extends DocumentAction
{
    protected string $entity = 'Drafts';

    /**
     * @param  int|DraftBuilder|array<string, mixed>  ...$args
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
     * Find a draft by DocEntry.
     */
    public function find(int $docEntry): DraftDto
    {
        $data = $this->getDocument($docEntry);

        return DraftDto::fromResponse($data);
    }

    /**
     * Create a new draft.
     *
     * @param  DraftBuilder|array<string, mixed>  $data
     */
    public function create(DraftBuilder|array $data): DraftDto
    {
        $payload = $data instanceof DraftBuilder ? $data->build() : $data;
        $response = $this->createDocument($payload);

        return DraftDto::fromResponse($response);
    }

    /**
     * Update an existing draft.
     *
     * @param  DraftBuilder|array<string, mixed>  $data
     */
    public function update(int $docEntry, DraftBuilder|array $data): DraftDto
    {
        $payload = $data instanceof DraftBuilder ? $data->build() : $data;
        $response = $this->updateDocument($docEntry, $payload);

        return DraftDto::fromResponse($response);
    }

    /**
     * Delete a draft.
     */
    public function delete(int $docEntry): bool
    {
        $this->client()
            ->service($this->entity)
            ->delete($docEntry);

        return true;
    }

    /**
     * Get drafts by document type.
     *
     * @return array<DraftDto>
     */
    public function getByDocumentType(DocumentType $docType): array
    {
        $response = $this->client()
            ->service($this->entity)
            ->queryBuilder()
            ->filter("DocObjectCode eq '{$docType->value}'")
            ->get();

        return array_map(
            fn (array $item) => DraftDto::fromResponse($item),
            $response['value'] ?? []
        );
    }

    /**
     * Get all sales order drafts.
     *
     * @return array<DraftDto>
     */
    public function getSalesOrderDrafts(): array
    {
        return $this->getByDocumentType(DocumentType::SalesOrder);
    }

    /**
     * Get all invoice drafts.
     *
     * @return array<DraftDto>
     */
    public function getInvoiceDrafts(): array
    {
        return $this->getByDocumentType(DocumentType::ARInvoice);
    }
}
