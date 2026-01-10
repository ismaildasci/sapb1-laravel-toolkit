<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Services;

use Illuminate\Support\Collection;
use SapB1\Toolkit\Enums\DocumentType;
use SapB1\Toolkit\Exceptions\DraftException;

/**
 * Service for managing draft documents in SAP B1.
 *
 * Provides methods to create, list, update, save, and delete draft documents
 * through the Service Layer Drafts endpoint.
 *
 * Service Layer Drafts Endpoint:
 * - GET    Drafts                              - List all drafts
 * - GET    Drafts?$filter=DocObjectCode eq 17  - List drafts by type
 * - POST   Drafts                              - Create a draft
 * - PATCH  Drafts({DocEntry})                  - Update a draft
 * - DELETE Drafts({DocEntry})                  - Delete a draft
 * - POST   Drafts({DocEntry})/SaveDraftToDocument - Convert to document
 *
 * @example
 * ```php
 * $service = app(DraftService::class);
 *
 * // Create an order draft
 * $draft = $service->createOrderDraft([
 *     'CardCode' => 'C001',
 *     'DocumentLines' => [...]
 * ]);
 *
 * // List all order drafts
 * $drafts = $service->listOrderDrafts();
 *
 * // Save draft as real document
 * $document = $service->saveAsDocument($draft['DocEntry']);
 *
 * // Delete a draft
 * $service->delete($draftEntry);
 * ```
 */
final class DraftService extends BaseService
{
    private const DRAFTS_ENDPOINT = 'Drafts';

    // ==================== CREATE DRAFTS ====================

    /**
     * Create a draft document.
     *
     * @param  DocumentType  $type  The target document type
     * @param  array<string, mixed>  $data  The draft data
     * @return array<string, mixed> The created draft
     *
     * @throws DraftException
     */
    public function create(DocumentType $type, array $data): array
    {
        $data['DocObjectCode'] = (string) $type->value;

        $response = $this->client()->create(self::DRAFTS_ENDPOINT, $data);

        if (! $response->successful()) {
            throw DraftException::createFailed($type->label(), $response->error() ?? 'Unknown error');
        }

        return $response->data();
    }

    /**
     * Create a sales order draft.
     *
     * @param  array<string, mixed>  $data  The order data
     * @return array<string, mixed>
     *
     * @throws DraftException
     */
    public function createOrderDraft(array $data): array
    {
        return $this->create(DocumentType::SalesOrder, $data);
    }

    /**
     * Create a sales quotation draft.
     *
     * @param  array<string, mixed>  $data  The quotation data
     * @return array<string, mixed>
     *
     * @throws DraftException
     */
    public function createQuotationDraft(array $data): array
    {
        return $this->create(DocumentType::SalesQuotation, $data);
    }

    /**
     * Create an A/R invoice draft.
     *
     * @param  array<string, mixed>  $data  The invoice data
     * @return array<string, mixed>
     *
     * @throws DraftException
     */
    public function createInvoiceDraft(array $data): array
    {
        return $this->create(DocumentType::ARInvoice, $data);
    }

    /**
     * Create a delivery note draft.
     *
     * @param  array<string, mixed>  $data  The delivery data
     * @return array<string, mixed>
     *
     * @throws DraftException
     */
    public function createDeliveryDraft(array $data): array
    {
        return $this->create(DocumentType::DeliveryNote, $data);
    }

    /**
     * Create a purchase order draft.
     *
     * @param  array<string, mixed>  $data  The purchase order data
     * @return array<string, mixed>
     *
     * @throws DraftException
     */
    public function createPurchaseOrderDraft(array $data): array
    {
        return $this->create(DocumentType::PurchaseOrder, $data);
    }

    /**
     * Create a purchase invoice draft.
     *
     * @param  array<string, mixed>  $data  The purchase invoice data
     * @return array<string, mixed>
     *
     * @throws DraftException
     */
    public function createPurchaseInvoiceDraft(array $data): array
    {
        return $this->create(DocumentType::APInvoice, $data);
    }

    // ==================== LIST DRAFTS ====================

    /**
     * List all drafts.
     *
     * @param  int|null  $top  Maximum number of results
     * @param  int|null  $skip  Number of results to skip
     * @return Collection<int, array<string, mixed>>
     */
    public function listAll(?int $top = null, ?int $skip = null): Collection
    {
        return $this->fetchDrafts(null, $top, $skip);
    }

    /**
     * List drafts by document type.
     *
     * @param  DocumentType  $type  The document type
     * @param  int|null  $top  Maximum number of results
     * @param  int|null  $skip  Number of results to skip
     * @return Collection<int, array<string, mixed>>
     */
    public function listByType(DocumentType $type, ?int $top = null, ?int $skip = null): Collection
    {
        return $this->fetchDrafts($type, $top, $skip);
    }

    /**
     * List sales order drafts.
     *
     * @param  int|null  $top  Maximum number of results
     * @return Collection<int, array<string, mixed>>
     */
    public function listOrderDrafts(?int $top = null): Collection
    {
        return $this->listByType(DocumentType::SalesOrder, $top);
    }

    /**
     * List sales quotation drafts.
     *
     * @param  int|null  $top  Maximum number of results
     * @return Collection<int, array<string, mixed>>
     */
    public function listQuotationDrafts(?int $top = null): Collection
    {
        return $this->listByType(DocumentType::SalesQuotation, $top);
    }

    /**
     * List A/R invoice drafts.
     *
     * @param  int|null  $top  Maximum number of results
     * @return Collection<int, array<string, mixed>>
     */
    public function listInvoiceDrafts(?int $top = null): Collection
    {
        return $this->listByType(DocumentType::ARInvoice, $top);
    }

    /**
     * List delivery note drafts.
     *
     * @param  int|null  $top  Maximum number of results
     * @return Collection<int, array<string, mixed>>
     */
    public function listDeliveryDrafts(?int $top = null): Collection
    {
        return $this->listByType(DocumentType::DeliveryNote, $top);
    }

    /**
     * List purchase order drafts.
     *
     * @param  int|null  $top  Maximum number of results
     * @return Collection<int, array<string, mixed>>
     */
    public function listPurchaseOrderDrafts(?int $top = null): Collection
    {
        return $this->listByType(DocumentType::PurchaseOrder, $top);
    }

    /**
     * List purchase invoice drafts.
     *
     * @param  int|null  $top  Maximum number of results
     * @return Collection<int, array<string, mixed>>
     */
    public function listPurchaseInvoiceDrafts(?int $top = null): Collection
    {
        return $this->listByType(DocumentType::APInvoice, $top);
    }

    /**
     * List drafts for a specific business partner.
     *
     * @param  string  $cardCode  The business partner code
     * @param  DocumentType|null  $type  Optional document type filter
     * @return Collection<int, array<string, mixed>>
     */
    public function listForPartner(string $cardCode, ?DocumentType $type = null): Collection
    {
        $filter = "CardCode eq '{$cardCode}'";

        if ($type !== null) {
            $filter .= " and DocObjectCode eq '{$type->value}'";
        }

        return $this->fetchDraftsWithFilter($filter);
    }

    // ==================== GET DRAFT ====================

    /**
     * Get a draft by DocEntry.
     *
     * @param  int  $docEntry  The draft DocEntry
     * @return array<string, mixed>
     *
     * @throws DraftException
     */
    public function find(int $docEntry): array
    {
        $response = $this->client()->find(self::DRAFTS_ENDPOINT, $docEntry);

        if (! $response->successful()) {
            throw DraftException::notFound($docEntry);
        }

        return $response->data();
    }

    /**
     * Check if a draft exists.
     *
     * @param  int  $docEntry  The draft DocEntry
     */
    public function exists(int $docEntry): bool
    {
        return $this->client()->exists(self::DRAFTS_ENDPOINT, $docEntry);
    }

    // ==================== UPDATE DRAFT ====================

    /**
     * Update a draft.
     *
     * @param  int  $docEntry  The draft DocEntry
     * @param  array<string, mixed>  $data  The data to update
     * @return array<string, mixed> The updated draft
     *
     * @throws DraftException
     */
    public function update(int $docEntry, array $data): array
    {
        $response = $this->client()->update(self::DRAFTS_ENDPOINT, $docEntry, $data);

        if (! $response->successful()) {
            throw DraftException::updateFailed($docEntry, $response->error() ?? 'Unknown error');
        }

        return $this->find($docEntry);
    }

    // ==================== DELETE DRAFT ====================

    /**
     * Delete a draft.
     *
     * @param  int  $docEntry  The draft DocEntry
     *
     * @throws DraftException
     */
    public function delete(int $docEntry): void
    {
        $response = $this->client()->delete(self::DRAFTS_ENDPOINT, $docEntry);

        if (! $response->successful()) {
            throw DraftException::deleteFailed($docEntry, $response->error() ?? 'Unknown error');
        }
    }

    /**
     * Delete multiple drafts.
     *
     * @param  array<int>  $docEntries  The draft DocEntries
     * @return array<int, bool> Map of DocEntry => success
     */
    public function deleteMultiple(array $docEntries): array
    {
        $results = [];

        foreach ($docEntries as $docEntry) {
            try {
                $this->delete($docEntry);
                $results[$docEntry] = true;
            } catch (DraftException) {
                $results[$docEntry] = false;
            }
        }

        return $results;
    }

    // ==================== SAVE AS DOCUMENT ====================

    /**
     * Save a draft as a real document.
     *
     * This converts the draft to an actual SAP B1 document
     * and removes it from the Drafts collection.
     *
     * @param  int  $docEntry  The draft DocEntry
     * @return array<string, mixed> The created document
     *
     * @throws DraftException
     */
    public function saveAsDocument(int $docEntry): array
    {
        $response = $this->client()->action(self::DRAFTS_ENDPOINT, $docEntry, 'SaveDraftToDocument');

        if (! $response->successful()) {
            throw DraftException::saveFailed($docEntry, $response->error() ?? 'Unknown error');
        }

        return $response->data();
    }

    // ==================== COUNT DRAFTS ====================

    /**
     * Count all drafts.
     */
    public function count(): int
    {
        return $this->client()->count(self::DRAFTS_ENDPOINT);
    }

    /**
     * Count drafts by type.
     *
     * @param  DocumentType  $type  The document type
     */
    public function countByType(DocumentType $type): int
    {
        $query = $this->client()->query()
            ->filter("DocObjectCode eq '{$type->value}'")
            ->inlineCount()
            ->top(1);

        $response = $this->client()->withOData($query)->get(self::DRAFTS_ENDPOINT);

        return $response->count() ?? 0;
    }

    // ==================== INTERNAL METHODS ====================

    /**
     * Fetch drafts with optional type filter.
     *
     * @return Collection<int, array<string, mixed>>
     */
    private function fetchDrafts(?DocumentType $type, ?int $top, ?int $skip): Collection
    {
        $query = $this->client()->query();

        if ($type !== null) {
            $query->filter("DocObjectCode eq '{$type->value}'");
        }

        if ($top !== null) {
            $query->top($top);
        }

        if ($skip !== null) {
            $query->skip($skip);
        }

        $response = $this->client()->withOData($query)->get(self::DRAFTS_ENDPOINT);

        /** @var array<int, array<string, mixed>> $items */
        $items = $response->value() ?? [];

        return collect($items);
    }

    /**
     * Fetch drafts with custom filter.
     *
     * @return Collection<int, array<string, mixed>>
     */
    private function fetchDraftsWithFilter(string $filter): Collection
    {
        $query = $this->client()->query()->filter($filter);

        $response = $this->client()->withOData($query)->get(self::DRAFTS_ENDPOINT);

        /** @var array<int, array<string, mixed>> $items */
        $items = $response->value() ?? [];

        return collect($items);
    }
}
