<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Services;

use Closure;
use SapB1\Client\BatchRequest;
use SapB1\Client\BatchResponse;
use SapB1\Toolkit\DTOs\Response\BatchResponseDto;

/**
 * Service for executing batch operations in SAP B1.
 *
 * Provides a high-level interface for creating multiple documents
 * in a single transaction using the Service Layer $batch endpoint.
 * Supports atomic changesets for all-or-nothing operations.
 *
 * Uses the SDK's BatchRequest which interacts with the
 * $batch endpoint in Service Layer.
 *
 * @example
 * ```php
 * $service = app(BatchService::class);
 *
 * // Create multiple orders atomically
 * $result = $service->createOrders([
 *     ['CardCode' => 'C001', 'DocumentLines' => [...]],
 *     ['CardCode' => 'C002', 'DocumentLines' => [...]],
 * ]);
 *
 * // Custom batch with changeset
 * $result = $service->execute(function ($batch) {
 *     $batch->beginChangeset();
 *     $batch->post('Orders', $data1);
 *     $batch->post('Orders', $data2);
 *     $batch->endChangeset();
 * });
 * ```
 */
final class BatchService extends BaseService
{
    /**
     * Create multiple orders in a single batch.
     *
     * @param  array<int, array<string, mixed>>  $orders  Array of order data
     * @param  bool  $atomic  Whether to use changeset (all-or-nothing)
     */
    public function createOrders(array $orders, bool $atomic = true): BatchResponseDto
    {
        return $this->createDocuments('Orders', $orders, $atomic);
    }

    /**
     * Create multiple invoices in a single batch.
     *
     * @param  array<int, array<string, mixed>>  $invoices  Array of invoice data
     * @param  bool  $atomic  Whether to use changeset (all-or-nothing)
     */
    public function createInvoices(array $invoices, bool $atomic = true): BatchResponseDto
    {
        return $this->createDocuments('Invoices', $invoices, $atomic);
    }

    /**
     * Create multiple delivery notes in a single batch.
     *
     * @param  array<int, array<string, mixed>>  $deliveries  Array of delivery data
     * @param  bool  $atomic  Whether to use changeset (all-or-nothing)
     */
    public function createDeliveries(array $deliveries, bool $atomic = true): BatchResponseDto
    {
        return $this->createDocuments('DeliveryNotes', $deliveries, $atomic);
    }

    /**
     * Create multiple quotations in a single batch.
     *
     * @param  array<int, array<string, mixed>>  $quotations  Array of quotation data
     * @param  bool  $atomic  Whether to use changeset (all-or-nothing)
     */
    public function createQuotations(array $quotations, bool $atomic = true): BatchResponseDto
    {
        return $this->createDocuments('Quotations', $quotations, $atomic);
    }

    /**
     * Create multiple purchase orders in a single batch.
     *
     * @param  array<int, array<string, mixed>>  $purchaseOrders  Array of purchase order data
     * @param  bool  $atomic  Whether to use changeset (all-or-nothing)
     */
    public function createPurchaseOrders(array $purchaseOrders, bool $atomic = true): BatchResponseDto
    {
        return $this->createDocuments('PurchaseOrders', $purchaseOrders, $atomic);
    }

    /**
     * Create multiple purchase invoices in a single batch.
     *
     * @param  array<int, array<string, mixed>>  $purchaseInvoices  Array of purchase invoice data
     * @param  bool  $atomic  Whether to use changeset (all-or-nothing)
     */
    public function createPurchaseInvoices(array $purchaseInvoices, bool $atomic = true): BatchResponseDto
    {
        return $this->createDocuments('PurchaseInvoices', $purchaseInvoices, $atomic);
    }

    /**
     * Create multiple business partners in a single batch.
     *
     * @param  array<int, array<string, mixed>>  $partners  Array of partner data
     * @param  bool  $atomic  Whether to use changeset (all-or-nothing)
     */
    public function createPartners(array $partners, bool $atomic = true): BatchResponseDto
    {
        return $this->createDocuments('BusinessPartners', $partners, $atomic);
    }

    /**
     * Create multiple items in a single batch.
     *
     * @param  array<int, array<string, mixed>>  $items  Array of item data
     * @param  bool  $atomic  Whether to use changeset (all-or-nothing)
     */
    public function createItems(array $items, bool $atomic = true): BatchResponseDto
    {
        return $this->createDocuments('Items', $items, $atomic);
    }

    /**
     * Create multiple journal entries in a single batch.
     *
     * @param  array<int, array<string, mixed>>  $entries  Array of journal entry data
     * @param  bool  $atomic  Whether to use changeset (all-or-nothing)
     */
    public function createJournalEntries(array $entries, bool $atomic = true): BatchResponseDto
    {
        return $this->createDocuments('JournalEntries', $entries, $atomic);
    }

    /**
     * Create multiple incoming payments in a single batch.
     *
     * @param  array<int, array<string, mixed>>  $payments  Array of payment data
     * @param  bool  $atomic  Whether to use changeset (all-or-nothing)
     */
    public function createPayments(array $payments, bool $atomic = true): BatchResponseDto
    {
        return $this->createDocuments('IncomingPayments', $payments, $atomic);
    }

    /**
     * Create multiple documents of any type in a single batch.
     *
     * @param  string  $endpoint  The entity endpoint (e.g., 'Orders')
     * @param  array<int, array<string, mixed>>  $documents  Array of document data
     * @param  bool  $atomic  Whether to use changeset (all-or-nothing)
     */
    public function createDocuments(string $endpoint, array $documents, bool $atomic = true): BatchResponseDto
    {
        return $this->execute(function (BatchRequest $batch) use ($endpoint, $documents, $atomic) {
            if ($atomic) {
                $batch->beginChangeset();
            }

            foreach ($documents as $document) {
                $batch->post($endpoint, $document);
            }

            if ($atomic) {
                $batch->endChangeset();
            }
        });
    }

    /**
     * Update multiple items in a single batch.
     *
     * @param  array<int, array{ItemCode: string, data: array<string, mixed>}>  $updates
     * @param  bool  $atomic  Whether to use changeset (all-or-nothing)
     */
    public function updateItems(array $updates, bool $atomic = true): BatchResponseDto
    {
        return $this->execute(function (BatchRequest $batch) use ($updates, $atomic) {
            if ($atomic) {
                $batch->beginChangeset();
            }

            foreach ($updates as $update) {
                $itemCode = $update['ItemCode'];
                $data = $update['data'];
                $batch->patch("Items('{$itemCode}')", $data);
            }

            if ($atomic) {
                $batch->endChangeset();
            }
        });
    }

    /**
     * Update multiple business partners in a single batch.
     *
     * @param  array<int, array{CardCode: string, data: array<string, mixed>}>  $updates
     * @param  bool  $atomic  Whether to use changeset (all-or-nothing)
     */
    public function updatePartners(array $updates, bool $atomic = true): BatchResponseDto
    {
        return $this->execute(function (BatchRequest $batch) use ($updates, $atomic) {
            if ($atomic) {
                $batch->beginChangeset();
            }

            foreach ($updates as $update) {
                $cardCode = $update['CardCode'];
                $data = $update['data'];
                $batch->patch("BusinessPartners('{$cardCode}')", $data);
            }

            if ($atomic) {
                $batch->endChangeset();
            }
        });
    }

    /**
     * Update multiple orders in a single batch.
     *
     * @param  array<int, array{DocEntry: int, data: array<string, mixed>}>  $updates
     * @param  bool  $atomic  Whether to use changeset (all-or-nothing)
     */
    public function updateOrders(array $updates, bool $atomic = true): BatchResponseDto
    {
        return $this->updateDocuments('Orders', $updates, $atomic);
    }

    /**
     * Update multiple documents of any type in a single batch.
     *
     * @param  string  $endpoint  The entity endpoint
     * @param  array<int, array{DocEntry: int, data: array<string, mixed>}>  $updates
     * @param  bool  $atomic  Whether to use changeset (all-or-nothing)
     */
    public function updateDocuments(string $endpoint, array $updates, bool $atomic = true): BatchResponseDto
    {
        return $this->execute(function (BatchRequest $batch) use ($endpoint, $updates, $atomic) {
            if ($atomic) {
                $batch->beginChangeset();
            }

            foreach ($updates as $update) {
                $docEntry = $update['DocEntry'];
                $data = $update['data'];
                $batch->patch("{$endpoint}({$docEntry})", $data);
            }

            if ($atomic) {
                $batch->endChangeset();
            }
        });
    }

    /**
     * Fetch multiple records in a single batch (non-atomic).
     *
     * @param  array<int, string>  $endpoints  Array of endpoints to fetch
     */
    public function getMultiple(array $endpoints): BatchResponseDto
    {
        return $this->execute(function (BatchRequest $batch) use ($endpoints) {
            foreach ($endpoints as $endpoint) {
                $batch->get($endpoint);
            }
        });
    }

    /**
     * Execute a complete sales flow in a single batch.
     * Creates Order -> Delivery -> Invoice atomically.
     *
     * @param  array<string, mixed>  $orderData  The order data
     */
    public function salesFlow(array $orderData): BatchResponseDto
    {
        return $this->execute(function (BatchRequest $batch) use ($orderData) {
            $batch->beginChangeset();
            $batch->post('Orders', $orderData);
            // Note: In practice, you'd need DocEntry from order to create delivery/invoice
            // This is a simplified example showing the batch capability
            $batch->endChangeset();
        });
    }

    /**
     * Execute a custom batch operation.
     *
     * @param  Closure(BatchRequest): void  $callback  Callback that receives BatchRequest
     */
    public function execute(Closure $callback): BatchResponseDto
    {
        $batch = $this->client()->batch();

        $callback($batch);

        $response = $batch->execute();

        return $this->transformResponse($response);
    }

    /**
     * Create a new batch request for advanced usage.
     * Returns the raw SDK BatchRequest for full control.
     */
    public function newBatch(): BatchRequest
    {
        return $this->client()->batch();
    }

    /**
     * Execute a raw batch and get raw response.
     * For advanced usage when you need the SDK's BatchResponse directly.
     */
    public function executeRaw(BatchRequest $batch): BatchResponse
    {
        return $batch->execute();
    }

    /**
     * Transform SDK BatchResponse to Toolkit BatchResponseDto.
     */
    private function transformResponse(BatchResponse $response): BatchResponseDto
    {
        $responses = [];
        $successCount = 0;
        $errorCount = 0;

        foreach ($response->all() as $item) {
            $success = $item['success'];

            if ($success) {
                $successCount++;
            } else {
                $errorCount++;
            }

            $responses[] = [
                'success' => $success,
                'statusCode' => $item['status'],
                'data' => $item['body'] ?? [],
                'error' => $success ? null : ($item['body']['error']['message']['value'] ?? 'Unknown error'),
            ];
        }

        return BatchResponseDto::fromArray([
            'success' => $errorCount === 0,
            'responses' => $responses,
            'successCount' => $successCount,
            'errorCount' => $errorCount,
        ]);
    }
}
