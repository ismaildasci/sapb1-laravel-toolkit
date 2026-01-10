<?php

declare(strict_types=1);

use SapB1\Toolkit\Services\DocumentActionService;

it('can be instantiated', function () {
    $service = new DocumentActionService;

    expect($service)->toBeInstanceOf(DocumentActionService::class);
});

it('can set connection', function () {
    $service = new DocumentActionService;
    $result = $service->connection('secondary');

    expect($result)->toBeInstanceOf(DocumentActionService::class);
});

// ==================== GENERIC ACTIONS ====================

it('has close method', function () {
    $service = new DocumentActionService;

    expect(method_exists($service, 'close'))->toBeTrue();
});

it('has cancel method', function () {
    $service = new DocumentActionService;

    expect(method_exists($service, 'cancel'))->toBeTrue();
});

it('has reopen method', function () {
    $service = new DocumentActionService;

    expect(method_exists($service, 'reopen'))->toBeTrue();
});

it('has action method for custom actions', function () {
    $service = new DocumentActionService;

    expect(method_exists($service, 'action'))->toBeTrue();
});

// ==================== SALES DOCUMENT ACTIONS ====================

it('has closeOrder method', function () {
    $service = new DocumentActionService;

    expect(method_exists($service, 'closeOrder'))->toBeTrue();
});

it('has closeOrders method for bulk', function () {
    $service = new DocumentActionService;

    expect(method_exists($service, 'closeOrders'))->toBeTrue();
});

it('has closeQuotation method', function () {
    $service = new DocumentActionService;

    expect(method_exists($service, 'closeQuotation'))->toBeTrue();
});

it('has closeQuotations method for bulk', function () {
    $service = new DocumentActionService;

    expect(method_exists($service, 'closeQuotations'))->toBeTrue();
});

it('has cancelDelivery method', function () {
    $service = new DocumentActionService;

    expect(method_exists($service, 'cancelDelivery'))->toBeTrue();
});

it('has cancelDeliveries method for bulk', function () {
    $service = new DocumentActionService;

    expect(method_exists($service, 'cancelDeliveries'))->toBeTrue();
});

it('has cancelInvoice method', function () {
    $service = new DocumentActionService;

    expect(method_exists($service, 'cancelInvoice'))->toBeTrue();
});

it('has cancelInvoices method for bulk', function () {
    $service = new DocumentActionService;

    expect(method_exists($service, 'cancelInvoices'))->toBeTrue();
});

it('has cancelCreditNote method', function () {
    $service = new DocumentActionService;

    expect(method_exists($service, 'cancelCreditNote'))->toBeTrue();
});

it('has cancelCreditNotes method for bulk', function () {
    $service = new DocumentActionService;

    expect(method_exists($service, 'cancelCreditNotes'))->toBeTrue();
});

it('has cancelReturn method', function () {
    $service = new DocumentActionService;

    expect(method_exists($service, 'cancelReturn'))->toBeTrue();
});

// ==================== PURCHASE DOCUMENT ACTIONS ====================

it('has closePurchaseOrder method', function () {
    $service = new DocumentActionService;

    expect(method_exists($service, 'closePurchaseOrder'))->toBeTrue();
});

it('has closePurchaseOrders method for bulk', function () {
    $service = new DocumentActionService;

    expect(method_exists($service, 'closePurchaseOrders'))->toBeTrue();
});

it('has closePurchaseQuotation method', function () {
    $service = new DocumentActionService;

    expect(method_exists($service, 'closePurchaseQuotation'))->toBeTrue();
});

it('has cancelGoodsReceipt method', function () {
    $service = new DocumentActionService;

    expect(method_exists($service, 'cancelGoodsReceipt'))->toBeTrue();
});

it('has cancelGoodsReceipts method for bulk', function () {
    $service = new DocumentActionService;

    expect(method_exists($service, 'cancelGoodsReceipts'))->toBeTrue();
});

it('has cancelPurchaseInvoice method', function () {
    $service = new DocumentActionService;

    expect(method_exists($service, 'cancelPurchaseInvoice'))->toBeTrue();
});

it('has cancelPurchaseInvoices method for bulk', function () {
    $service = new DocumentActionService;

    expect(method_exists($service, 'cancelPurchaseInvoices'))->toBeTrue();
});

it('has cancelPurchaseCreditNote method', function () {
    $service = new DocumentActionService;

    expect(method_exists($service, 'cancelPurchaseCreditNote'))->toBeTrue();
});

// ==================== INVENTORY DOCUMENT ACTIONS ====================

it('has cancelInventoryEntry method', function () {
    $service = new DocumentActionService;

    expect(method_exists($service, 'cancelInventoryEntry'))->toBeTrue();
});

it('has cancelInventoryExit method', function () {
    $service = new DocumentActionService;

    expect(method_exists($service, 'cancelInventoryExit'))->toBeTrue();
});

it('has cancelStockTransfer method', function () {
    $service = new DocumentActionService;

    expect(method_exists($service, 'cancelStockTransfer'))->toBeTrue();
});

// ==================== DOCUMENT TYPE ENUM ACTIONS ====================

it('has closeByType method', function () {
    $service = new DocumentActionService;

    expect(method_exists($service, 'closeByType'))->toBeTrue();
});

it('has cancelByType method', function () {
    $service = new DocumentActionService;

    expect(method_exists($service, 'cancelByType'))->toBeTrue();
});

// ==================== BULK OPERATIONS ====================

it('has bulkAction method', function () {
    $service = new DocumentActionService;

    expect(method_exists($service, 'bulkAction'))->toBeTrue();
});

it('has closeMultiple method', function () {
    $service = new DocumentActionService;

    expect(method_exists($service, 'closeMultiple'))->toBeTrue();
});

it('has cancelMultiple method', function () {
    $service = new DocumentActionService;

    expect(method_exists($service, 'cancelMultiple'))->toBeTrue();
});
