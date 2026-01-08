<?php

declare(strict_types=1);

use SapB1\Toolkit\Services\BatchService;

it('can be instantiated', function () {
    $service = new BatchService;

    expect($service)->toBeInstanceOf(BatchService::class);
});

it('can set connection', function () {
    $service = new BatchService;
    $result = $service->connection('secondary');

    expect($result)->toBeInstanceOf(BatchService::class);
});

it('has create methods for common document types', function () {
    $service = new BatchService;

    expect(method_exists($service, 'createOrders'))->toBeTrue();
    expect(method_exists($service, 'createInvoices'))->toBeTrue();
    expect(method_exists($service, 'createDeliveries'))->toBeTrue();
    expect(method_exists($service, 'createQuotations'))->toBeTrue();
    expect(method_exists($service, 'createPurchaseOrders'))->toBeTrue();
    expect(method_exists($service, 'createPurchaseInvoices'))->toBeTrue();
    expect(method_exists($service, 'createPartners'))->toBeTrue();
    expect(method_exists($service, 'createItems'))->toBeTrue();
    expect(method_exists($service, 'createJournalEntries'))->toBeTrue();
    expect(method_exists($service, 'createPayments'))->toBeTrue();
});

it('has generic createDocuments method', function () {
    $service = new BatchService;

    expect(method_exists($service, 'createDocuments'))->toBeTrue();
});

it('has update methods', function () {
    $service = new BatchService;

    expect(method_exists($service, 'updateItems'))->toBeTrue();
    expect(method_exists($service, 'updatePartners'))->toBeTrue();
    expect(method_exists($service, 'updateOrders'))->toBeTrue();
    expect(method_exists($service, 'updateDocuments'))->toBeTrue();
});

it('has getMultiple method for batch fetching', function () {
    $service = new BatchService;

    expect(method_exists($service, 'getMultiple'))->toBeTrue();
});

it('has execute method for custom batches', function () {
    $service = new BatchService;

    expect(method_exists($service, 'execute'))->toBeTrue();
});

it('has newBatch method for advanced usage', function () {
    $service = new BatchService;

    expect(method_exists($service, 'newBatch'))->toBeTrue();
});

it('has executeRaw method for raw batch operations', function () {
    $service = new BatchService;

    expect(method_exists($service, 'executeRaw'))->toBeTrue();
});

it('has salesFlow method for complete flow', function () {
    $service = new BatchService;

    expect(method_exists($service, 'salesFlow'))->toBeTrue();
});
