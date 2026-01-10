<?php

declare(strict_types=1);

use SapB1\Toolkit\Services\DraftService;

it('can be instantiated', function () {
    $service = new DraftService;

    expect($service)->toBeInstanceOf(DraftService::class);
});

it('can set connection', function () {
    $service = new DraftService;
    $result = $service->connection('secondary');

    expect($result)->toBeInstanceOf(DraftService::class);
});

// ==================== CREATE DRAFTS ====================

it('has create method', function () {
    $service = new DraftService;

    expect(method_exists($service, 'create'))->toBeTrue();
});

it('has createOrderDraft method', function () {
    $service = new DraftService;

    expect(method_exists($service, 'createOrderDraft'))->toBeTrue();
});

it('has createQuotationDraft method', function () {
    $service = new DraftService;

    expect(method_exists($service, 'createQuotationDraft'))->toBeTrue();
});

it('has createInvoiceDraft method', function () {
    $service = new DraftService;

    expect(method_exists($service, 'createInvoiceDraft'))->toBeTrue();
});

it('has createDeliveryDraft method', function () {
    $service = new DraftService;

    expect(method_exists($service, 'createDeliveryDraft'))->toBeTrue();
});

it('has createPurchaseOrderDraft method', function () {
    $service = new DraftService;

    expect(method_exists($service, 'createPurchaseOrderDraft'))->toBeTrue();
});

it('has createPurchaseInvoiceDraft method', function () {
    $service = new DraftService;

    expect(method_exists($service, 'createPurchaseInvoiceDraft'))->toBeTrue();
});

// ==================== LIST DRAFTS ====================

it('has listAll method', function () {
    $service = new DraftService;

    expect(method_exists($service, 'listAll'))->toBeTrue();
});

it('has listByType method', function () {
    $service = new DraftService;

    expect(method_exists($service, 'listByType'))->toBeTrue();
});

it('has listOrderDrafts method', function () {
    $service = new DraftService;

    expect(method_exists($service, 'listOrderDrafts'))->toBeTrue();
});

it('has listQuotationDrafts method', function () {
    $service = new DraftService;

    expect(method_exists($service, 'listQuotationDrafts'))->toBeTrue();
});

it('has listInvoiceDrafts method', function () {
    $service = new DraftService;

    expect(method_exists($service, 'listInvoiceDrafts'))->toBeTrue();
});

it('has listDeliveryDrafts method', function () {
    $service = new DraftService;

    expect(method_exists($service, 'listDeliveryDrafts'))->toBeTrue();
});

it('has listPurchaseOrderDrafts method', function () {
    $service = new DraftService;

    expect(method_exists($service, 'listPurchaseOrderDrafts'))->toBeTrue();
});

it('has listPurchaseInvoiceDrafts method', function () {
    $service = new DraftService;

    expect(method_exists($service, 'listPurchaseInvoiceDrafts'))->toBeTrue();
});

it('has listForPartner method', function () {
    $service = new DraftService;

    expect(method_exists($service, 'listForPartner'))->toBeTrue();
});

// ==================== GET DRAFT ====================

it('has find method', function () {
    $service = new DraftService;

    expect(method_exists($service, 'find'))->toBeTrue();
});

it('has exists method', function () {
    $service = new DraftService;

    expect(method_exists($service, 'exists'))->toBeTrue();
});

// ==================== UPDATE DRAFT ====================

it('has update method', function () {
    $service = new DraftService;

    expect(method_exists($service, 'update'))->toBeTrue();
});

// ==================== DELETE DRAFT ====================

it('has delete method', function () {
    $service = new DraftService;

    expect(method_exists($service, 'delete'))->toBeTrue();
});

it('has deleteMultiple method', function () {
    $service = new DraftService;

    expect(method_exists($service, 'deleteMultiple'))->toBeTrue();
});

// ==================== SAVE AS DOCUMENT ====================

it('has saveAsDocument method', function () {
    $service = new DraftService;

    expect(method_exists($service, 'saveAsDocument'))->toBeTrue();
});

// ==================== COUNT DRAFTS ====================

it('has count method', function () {
    $service = new DraftService;

    expect(method_exists($service, 'count'))->toBeTrue();
});

it('has countByType method', function () {
    $service = new DraftService;

    expect(method_exists($service, 'countByType'))->toBeTrue();
});
