<?php

declare(strict_types=1);

use SapB1\Toolkit\Enums\DocumentType;

it('has correct values for sales documents', function () {
    expect(DocumentType::SalesQuotation->value)->toBe(23);
    expect(DocumentType::SalesOrder->value)->toBe(17);
    expect(DocumentType::DeliveryNote->value)->toBe(15);
    expect(DocumentType::ARInvoice->value)->toBe(13);
    expect(DocumentType::ARCreditNote->value)->toBe(14);
    expect(DocumentType::ARDownPayment->value)->toBe(203);
});

it('has correct values for purchase documents', function () {
    expect(DocumentType::PurchaseOrder->value)->toBe(22);
    expect(DocumentType::GoodsReceiptPO->value)->toBe(20);
    expect(DocumentType::APInvoice->value)->toBe(18);
    expect(DocumentType::APCreditNote->value)->toBe(19);
    expect(DocumentType::APDownPayment->value)->toBe(204);
});

it('has correct labels', function () {
    expect(DocumentType::SalesOrder->label())->toBe('Sales Order');
    expect(DocumentType::ARInvoice->label())->toBe('A/R Invoice');
    expect(DocumentType::APInvoice->label())->toBe('A/P Invoice');
});

it('identifies sales documents', function () {
    expect(DocumentType::SalesOrder->isSalesDocument())->toBeTrue();
    expect(DocumentType::ARInvoice->isSalesDocument())->toBeTrue();
    expect(DocumentType::ARDownPayment->isSalesDocument())->toBeTrue();
    expect(DocumentType::PurchaseOrder->isSalesDocument())->toBeFalse();
});

it('identifies purchase documents', function () {
    expect(DocumentType::PurchaseOrder->isPurchaseDocument())->toBeTrue();
    expect(DocumentType::APInvoice->isPurchaseDocument())->toBeTrue();
    expect(DocumentType::APDownPayment->isPurchaseDocument())->toBeTrue();
    expect(DocumentType::SalesOrder->isPurchaseDocument())->toBeFalse();
});

it('identifies inventory documents', function () {
    expect(DocumentType::StockTransfer->isInventoryDocument())->toBeTrue();
    expect(DocumentType::InventoryGenEntry->isInventoryDocument())->toBeTrue();
    expect(DocumentType::SalesOrder->isInventoryDocument())->toBeFalse();
});

// ==================== v2.3.0 - Endpoint and Action Support ====================

it('returns correct Service Layer endpoint names', function () {
    expect(DocumentType::SalesQuotation->endpoint())->toBe('Quotations');
    expect(DocumentType::SalesOrder->endpoint())->toBe('Orders');
    expect(DocumentType::DeliveryNote->endpoint())->toBe('DeliveryNotes');
    expect(DocumentType::Return->endpoint())->toBe('Returns');
    expect(DocumentType::ARInvoice->endpoint())->toBe('Invoices');
    expect(DocumentType::ARCreditNote->endpoint())->toBe('CreditNotes');
    expect(DocumentType::ARDownPayment->endpoint())->toBe('DownPayments');
    expect(DocumentType::PurchaseQuotation->endpoint())->toBe('PurchaseQuotations');
    expect(DocumentType::PurchaseOrder->endpoint())->toBe('PurchaseOrders');
    expect(DocumentType::GoodsReceiptPO->endpoint())->toBe('PurchaseDeliveryNotes');
    expect(DocumentType::PurchaseReturn->endpoint())->toBe('PurchaseReturns');
    expect(DocumentType::APInvoice->endpoint())->toBe('PurchaseInvoices');
    expect(DocumentType::APCreditNote->endpoint())->toBe('PurchaseCreditNotes');
    expect(DocumentType::APDownPayment->endpoint())->toBe('PurchaseDownPayments');
    expect(DocumentType::InventoryGenEntry->endpoint())->toBe('InventoryGenEntries');
    expect(DocumentType::InventoryGenExit->endpoint())->toBe('InventoryGenExits');
    expect(DocumentType::StockTransfer->endpoint())->toBe('StockTransfers');
});

it('identifies documents that support Close action', function () {
    expect(DocumentType::SalesQuotation->supportsClose())->toBeTrue();
    expect(DocumentType::SalesOrder->supportsClose())->toBeTrue();
    expect(DocumentType::PurchaseQuotation->supportsClose())->toBeTrue();
    expect(DocumentType::PurchaseOrder->supportsClose())->toBeTrue();

    expect(DocumentType::ARInvoice->supportsClose())->toBeFalse();
    expect(DocumentType::DeliveryNote->supportsClose())->toBeFalse();
    expect(DocumentType::StockTransfer->supportsClose())->toBeFalse();
});

it('identifies documents that support Cancel action', function () {
    expect(DocumentType::DeliveryNote->supportsCancel())->toBeTrue();
    expect(DocumentType::Return->supportsCancel())->toBeTrue();
    expect(DocumentType::ARInvoice->supportsCancel())->toBeTrue();
    expect(DocumentType::ARCreditNote->supportsCancel())->toBeTrue();
    expect(DocumentType::GoodsReceiptPO->supportsCancel())->toBeTrue();
    expect(DocumentType::PurchaseReturn->supportsCancel())->toBeTrue();
    expect(DocumentType::APInvoice->supportsCancel())->toBeTrue();
    expect(DocumentType::APCreditNote->supportsCancel())->toBeTrue();
    expect(DocumentType::InventoryGenEntry->supportsCancel())->toBeTrue();
    expect(DocumentType::InventoryGenExit->supportsCancel())->toBeTrue();
    expect(DocumentType::StockTransfer->supportsCancel())->toBeTrue();

    expect(DocumentType::SalesOrder->supportsCancel())->toBeFalse();
    expect(DocumentType::SalesQuotation->supportsCancel())->toBeFalse();
    expect(DocumentType::PurchaseOrder->supportsCancel())->toBeFalse();
});

it('can get DocumentType from endpoint name', function () {
    expect(DocumentType::fromEndpoint('Orders'))->toBe(DocumentType::SalesOrder);
    expect(DocumentType::fromEndpoint('Invoices'))->toBe(DocumentType::ARInvoice);
    expect(DocumentType::fromEndpoint('PurchaseOrders'))->toBe(DocumentType::PurchaseOrder);
    expect(DocumentType::fromEndpoint('StockTransfers'))->toBe(DocumentType::StockTransfer);
    expect(DocumentType::fromEndpoint('InvalidEndpoint'))->toBeNull();
});
