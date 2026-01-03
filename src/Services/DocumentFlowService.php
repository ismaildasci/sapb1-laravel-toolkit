<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Services;

use SapB1\Toolkit\Actions\Sales\DeliveryAction;
use SapB1\Toolkit\Actions\Sales\OrderAction;
use SapB1\Toolkit\Actions\Sales\QuotationAction;
use SapB1\Toolkit\DTOs\Sales\DeliveryNoteDto;
use SapB1\Toolkit\DTOs\Sales\InvoiceDto;
use SapB1\Toolkit\DTOs\Sales\OrderDto;

/**
 * Service for managing document flow operations.
 */
final class DocumentFlowService extends BaseService
{
    /**
     * Convert quotation to order.
     */
    public function quotationToOrder(int $quotationDocEntry): OrderDto
    {
        $quotationAction = (new QuotationAction)->connection($this->connection);
        $result = $quotationAction->copyToOrder($quotationDocEntry);

        return OrderDto::fromResponse($result);
    }

    /**
     * Convert order to delivery.
     *
     * @param  array<int>|null  $lineFilter
     */
    public function orderToDelivery(int $orderDocEntry, ?array $lineFilter = null): DeliveryNoteDto
    {
        $orderAction = (new OrderAction)->connection($this->connection);
        $result = $orderAction->copyToDelivery($orderDocEntry, $lineFilter);

        return DeliveryNoteDto::fromResponse($result);
    }

    /**
     * Convert order to invoice.
     *
     * @param  array<int>|null  $lineFilter
     */
    public function orderToInvoice(int $orderDocEntry, ?array $lineFilter = null): InvoiceDto
    {
        $orderAction = (new OrderAction)->connection($this->connection);
        $result = $orderAction->copyToInvoice($orderDocEntry, $lineFilter);

        return InvoiceDto::fromResponse($result);
    }

    /**
     * Convert delivery to invoice.
     *
     * @param  array<int>|null  $lineFilter
     */
    public function deliveryToInvoice(int $deliveryDocEntry, ?array $lineFilter = null): InvoiceDto
    {
        $deliveryAction = (new DeliveryAction)->connection($this->connection);
        $result = $deliveryAction->copyToInvoice($deliveryDocEntry, $lineFilter);

        return InvoiceDto::fromResponse($result);
    }

    /**
     * Full flow: Quotation -> Order -> Delivery -> Invoice.
     *
     * @return array{order: OrderDto, delivery: DeliveryNoteDto, invoice: InvoiceDto}
     */
    public function fullSalesFlow(int $quotationDocEntry): array
    {
        $order = $this->quotationToOrder($quotationDocEntry);

        if ($order->docEntry === null) {
            throw new \RuntimeException('Order creation failed: no DocEntry returned');
        }

        $delivery = $this->orderToDelivery($order->docEntry);

        if ($delivery->docEntry === null) {
            throw new \RuntimeException('Delivery creation failed: no DocEntry returned');
        }

        $invoice = $this->deliveryToInvoice($delivery->docEntry);

        return [
            'order' => $order,
            'delivery' => $delivery,
            'invoice' => $invoice,
        ];
    }

    /**
     * Close multiple orders.
     *
     * @param  array<int>  $docEntries
     * @return array<int, bool>
     */
    public function closeOrders(array $docEntries): array
    {
        $orderAction = (new OrderAction)->connection($this->connection);
        $results = [];

        foreach ($docEntries as $docEntry) {
            $results[$docEntry] = $orderAction->close($docEntry);
        }

        return $results;
    }

    /**
     * Get document flow for an order.
     *
     * @return array<string, mixed>
     */
    public function getOrderFlow(int $orderDocEntry): array
    {
        $order = (new OrderAction)->connection($this->connection)->find($orderDocEntry);

        $deliveries = $this->client()
            ->service('DeliveryNotes')
            ->queryBuilder()
            ->select(['DocEntry', 'DocNum', 'DocDate', 'DocTotal'])
            ->filter("DocumentLines/any(d: d/BaseEntry eq {$orderDocEntry} and d/BaseType eq 17)")
            ->get();

        $invoices = $this->client()
            ->service('Invoices')
            ->queryBuilder()
            ->select(['DocEntry', 'DocNum', 'DocDate', 'DocTotal'])
            ->filter("DocumentLines/any(d: d/BaseEntry eq {$orderDocEntry} and d/BaseType eq 17)")
            ->get();

        return [
            'order' => $order,
            'deliveries' => $deliveries['value'] ?? [],
            'invoices' => $invoices['value'] ?? [],
        ];
    }
}
