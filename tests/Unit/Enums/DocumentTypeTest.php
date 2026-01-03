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
