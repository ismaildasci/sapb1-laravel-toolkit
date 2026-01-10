<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Services;

use SapB1\Client\BatchRequest;
use SapB1\Toolkit\DTOs\Response\BatchResponseDto;
use SapB1\Toolkit\Enums\DocumentType;
use SapB1\Toolkit\Exceptions\DocumentActionException;

/**
 * Service for executing document actions in SAP B1.
 *
 * Provides methods to Close, Cancel, and Reopen documents through
 * the Service Layer action endpoints. Supports both single and bulk
 * operations using batch requests.
 *
 * Service Layer Actions:
 * - POST {endpoint}({DocEntry})/Close - Close a document
 * - POST {endpoint}({DocEntry})/Cancel - Cancel a document
 * - POST {endpoint}({DocEntry})/Reopen - Reopen a closed document
 *
 * @example
 * ```php
 * $service = app(DocumentActionService::class);
 *
 * // Close a single order
 * $service->closeOrder(123);
 *
 * // Close multiple orders
 * $service->closeOrders([123, 124, 125]);
 *
 * // Cancel an invoice
 * $service->cancelInvoice(456);
 *
 * // Generic action
 * $service->close('Orders', 123);
 * $service->cancel('Invoices', 456);
 * ```
 */
final class DocumentActionService extends BaseService
{
    // ==================== GENERIC ACTIONS ====================

    /**
     * Close a document.
     *
     * @param  string  $endpoint  The entity endpoint (e.g., 'Orders')
     * @param  int  $docEntry  The document entry
     *
     * @throws DocumentActionException
     */
    public function close(string $endpoint, int $docEntry): void
    {
        $this->executeAction($endpoint, $docEntry, 'Close');
    }

    /**
     * Cancel a document.
     *
     * @param  string  $endpoint  The entity endpoint (e.g., 'Invoices')
     * @param  int  $docEntry  The document entry
     *
     * @throws DocumentActionException
     */
    public function cancel(string $endpoint, int $docEntry): void
    {
        $this->executeAction($endpoint, $docEntry, 'Cancel');
    }

    /**
     * Reopen a closed document.
     *
     * Note: Not all document types support Reopen action.
     *
     * @param  string  $endpoint  The entity endpoint
     * @param  int  $docEntry  The document entry
     *
     * @throws DocumentActionException
     */
    public function reopen(string $endpoint, int $docEntry): void
    {
        $this->executeAction($endpoint, $docEntry, 'Reopen');
    }

    /**
     * Execute a custom action on a document.
     *
     * @param  string  $endpoint  The entity endpoint
     * @param  int  $docEntry  The document entry
     * @param  string  $action  The action name
     * @param  array<string, mixed>  $params  Optional action parameters
     *
     * @throws DocumentActionException
     */
    public function action(string $endpoint, int $docEntry, string $action, array $params = []): void
    {
        $this->executeAction($endpoint, $docEntry, $action, $params);
    }

    // ==================== SALES DOCUMENT ACTIONS ====================

    /**
     * Close a sales order.
     *
     * @throws DocumentActionException
     */
    public function closeOrder(int $docEntry): void
    {
        $this->close('Orders', $docEntry);
    }

    /**
     * Close multiple sales orders.
     *
     * @param  array<int>  $docEntries
     */
    public function closeOrders(array $docEntries): BatchResponseDto
    {
        return $this->bulkAction('Orders', $docEntries, 'Close');
    }

    /**
     * Close a sales quotation.
     *
     * @throws DocumentActionException
     */
    public function closeQuotation(int $docEntry): void
    {
        $this->close('Quotations', $docEntry);
    }

    /**
     * Close multiple sales quotations.
     *
     * @param  array<int>  $docEntries
     */
    public function closeQuotations(array $docEntries): BatchResponseDto
    {
        return $this->bulkAction('Quotations', $docEntries, 'Close');
    }

    /**
     * Cancel a delivery note.
     *
     * @throws DocumentActionException
     */
    public function cancelDelivery(int $docEntry): void
    {
        $this->cancel('DeliveryNotes', $docEntry);
    }

    /**
     * Cancel multiple delivery notes.
     *
     * @param  array<int>  $docEntries
     */
    public function cancelDeliveries(array $docEntries): BatchResponseDto
    {
        return $this->bulkAction('DeliveryNotes', $docEntries, 'Cancel');
    }

    /**
     * Cancel an A/R invoice.
     *
     * @throws DocumentActionException
     */
    public function cancelInvoice(int $docEntry): void
    {
        $this->cancel('Invoices', $docEntry);
    }

    /**
     * Cancel multiple A/R invoices.
     *
     * @param  array<int>  $docEntries
     */
    public function cancelInvoices(array $docEntries): BatchResponseDto
    {
        return $this->bulkAction('Invoices', $docEntries, 'Cancel');
    }

    /**
     * Cancel an A/R credit note.
     *
     * @throws DocumentActionException
     */
    public function cancelCreditNote(int $docEntry): void
    {
        $this->cancel('CreditNotes', $docEntry);
    }

    /**
     * Cancel multiple A/R credit notes.
     *
     * @param  array<int>  $docEntries
     */
    public function cancelCreditNotes(array $docEntries): BatchResponseDto
    {
        return $this->bulkAction('CreditNotes', $docEntries, 'Cancel');
    }

    /**
     * Cancel a return document.
     *
     * @throws DocumentActionException
     */
    public function cancelReturn(int $docEntry): void
    {
        $this->cancel('Returns', $docEntry);
    }

    // ==================== PURCHASE DOCUMENT ACTIONS ====================

    /**
     * Close a purchase order.
     *
     * @throws DocumentActionException
     */
    public function closePurchaseOrder(int $docEntry): void
    {
        $this->close('PurchaseOrders', $docEntry);
    }

    /**
     * Close multiple purchase orders.
     *
     * @param  array<int>  $docEntries
     */
    public function closePurchaseOrders(array $docEntries): BatchResponseDto
    {
        return $this->bulkAction('PurchaseOrders', $docEntries, 'Close');
    }

    /**
     * Close a purchase quotation.
     *
     * @throws DocumentActionException
     */
    public function closePurchaseQuotation(int $docEntry): void
    {
        $this->close('PurchaseQuotations', $docEntry);
    }

    /**
     * Cancel a goods receipt PO.
     *
     * @throws DocumentActionException
     */
    public function cancelGoodsReceipt(int $docEntry): void
    {
        $this->cancel('PurchaseDeliveryNotes', $docEntry);
    }

    /**
     * Cancel multiple goods receipts.
     *
     * @param  array<int>  $docEntries
     */
    public function cancelGoodsReceipts(array $docEntries): BatchResponseDto
    {
        return $this->bulkAction('PurchaseDeliveryNotes', $docEntries, 'Cancel');
    }

    /**
     * Cancel an A/P invoice.
     *
     * @throws DocumentActionException
     */
    public function cancelPurchaseInvoice(int $docEntry): void
    {
        $this->cancel('PurchaseInvoices', $docEntry);
    }

    /**
     * Cancel multiple A/P invoices.
     *
     * @param  array<int>  $docEntries
     */
    public function cancelPurchaseInvoices(array $docEntries): BatchResponseDto
    {
        return $this->bulkAction('PurchaseInvoices', $docEntries, 'Cancel');
    }

    /**
     * Cancel an A/P credit note.
     *
     * @throws DocumentActionException
     */
    public function cancelPurchaseCreditNote(int $docEntry): void
    {
        $this->cancel('PurchaseCreditNotes', $docEntry);
    }

    // ==================== INVENTORY DOCUMENT ACTIONS ====================

    /**
     * Cancel an inventory goods receipt.
     *
     * @throws DocumentActionException
     */
    public function cancelInventoryEntry(int $docEntry): void
    {
        $this->cancel('InventoryGenEntries', $docEntry);
    }

    /**
     * Cancel an inventory goods issue.
     *
     * @throws DocumentActionException
     */
    public function cancelInventoryExit(int $docEntry): void
    {
        $this->cancel('InventoryGenExits', $docEntry);
    }

    /**
     * Cancel a stock transfer.
     *
     * @throws DocumentActionException
     */
    public function cancelStockTransfer(int $docEntry): void
    {
        $this->cancel('StockTransfers', $docEntry);
    }

    // ==================== USING DOCUMENT TYPE ENUM ====================

    /**
     * Close a document using DocumentType enum.
     *
     * @throws DocumentActionException
     */
    public function closeByType(DocumentType $type, int $docEntry): void
    {
        if (! $type->supportsClose()) {
            throw DocumentActionException::actionNotSupported($type->endpoint(), 'Close');
        }

        $this->close($type->endpoint(), $docEntry);
    }

    /**
     * Cancel a document using DocumentType enum.
     *
     * @throws DocumentActionException
     */
    public function cancelByType(DocumentType $type, int $docEntry): void
    {
        if (! $type->supportsCancel()) {
            throw DocumentActionException::actionNotSupported($type->endpoint(), 'Cancel');
        }

        $this->cancel($type->endpoint(), $docEntry);
    }

    // ==================== BULK OPERATIONS ====================

    /**
     * Execute bulk action on multiple documents.
     *
     * @param  string  $endpoint  The entity endpoint
     * @param  array<int>  $docEntries  Document entries
     * @param  string  $action  The action to execute
     * @param  bool  $atomic  Whether to use changeset (all-or-nothing)
     */
    public function bulkAction(
        string $endpoint,
        array $docEntries,
        string $action,
        bool $atomic = false
    ): BatchResponseDto {
        return $this->executeBatch(function (BatchRequest $batch) use ($endpoint, $docEntries, $action, $atomic) {
            if ($atomic) {
                $batch->beginChangeset();
            }

            foreach ($docEntries as $docEntry) {
                $batch->post("{$endpoint}({$docEntry})/{$action}", []);
            }

            if ($atomic) {
                $batch->endChangeset();
            }
        });
    }

    /**
     * Close multiple documents of any type.
     *
     * @param  string  $endpoint  The entity endpoint
     * @param  array<int>  $docEntries  Document entries
     * @param  bool  $atomic  Whether to use changeset
     */
    public function closeMultiple(string $endpoint, array $docEntries, bool $atomic = false): BatchResponseDto
    {
        return $this->bulkAction($endpoint, $docEntries, 'Close', $atomic);
    }

    /**
     * Cancel multiple documents of any type.
     *
     * @param  string  $endpoint  The entity endpoint
     * @param  array<int>  $docEntries  Document entries
     * @param  bool  $atomic  Whether to use changeset
     */
    public function cancelMultiple(string $endpoint, array $docEntries, bool $atomic = false): BatchResponseDto
    {
        return $this->bulkAction($endpoint, $docEntries, 'Cancel', $atomic);
    }

    // ==================== INTERNAL METHODS ====================

    /**
     * Execute a single action on a document.
     *
     * @param  array<string, mixed>  $params
     *
     * @throws DocumentActionException
     */
    private function executeAction(string $endpoint, int $docEntry, string $action, array $params = []): void
    {
        $response = $this->client()->action($endpoint, $docEntry, $action, $params);

        if (! $response->successful()) {
            throw DocumentActionException::actionFailed(
                $endpoint,
                $docEntry,
                $action,
                $response->error() ?? 'Unknown error'
            );
        }
    }

    /**
     * Execute a batch operation.
     *
     * @param  callable(BatchRequest): void  $callback
     */
    private function executeBatch(callable $callback): BatchResponseDto
    {
        $batch = $this->client()->batch();

        $callback($batch);

        $response = $batch->execute();

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
