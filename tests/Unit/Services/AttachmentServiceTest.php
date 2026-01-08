<?php

declare(strict_types=1);

use SapB1\Toolkit\Services\AttachmentService;

it('can be instantiated', function () {
    $service = new AttachmentService;

    expect($service)->toBeInstanceOf(AttachmentService::class);
});

it('can set connection', function () {
    $service = new AttachmentService;
    $result = $service->connection('secondary');

    expect($result)->toBeInstanceOf(AttachmentService::class);
});

it('has upload methods for common entities', function () {
    $service = new AttachmentService;

    expect(method_exists($service, 'uploadToOrder'))->toBeTrue();
    expect(method_exists($service, 'uploadToInvoice'))->toBeTrue();
    expect(method_exists($service, 'uploadToDelivery'))->toBeTrue();
    expect(method_exists($service, 'uploadToPurchaseOrder'))->toBeTrue();
    expect(method_exists($service, 'uploadToPurchaseInvoice'))->toBeTrue();
    expect(method_exists($service, 'uploadToPartner'))->toBeTrue();
    expect(method_exists($service, 'uploadToItem'))->toBeTrue();
    expect(method_exists($service, 'uploadToQuotation'))->toBeTrue();
    expect(method_exists($service, 'uploadToJournalEntry'))->toBeTrue();
});

it('has generic upload method', function () {
    $service = new AttachmentService;

    expect(method_exists($service, 'upload'))->toBeTrue();
});

it('has download methods', function () {
    $service = new AttachmentService;

    expect(method_exists($service, 'download'))->toBeTrue();
    expect(method_exists($service, 'downloadTo'))->toBeTrue();
});

it('has list methods for common entities', function () {
    $service = new AttachmentService;

    expect(method_exists($service, 'listForOrder'))->toBeTrue();
    expect(method_exists($service, 'listForInvoice'))->toBeTrue();
    expect(method_exists($service, 'listForDelivery'))->toBeTrue();
    expect(method_exists($service, 'listForPartner'))->toBeTrue();
    expect(method_exists($service, 'listForItem'))->toBeTrue();
});

it('has generic list method', function () {
    $service = new AttachmentService;

    expect(method_exists($service, 'listFor'))->toBeTrue();
});

it('has metadata method', function () {
    $service = new AttachmentService;

    expect(method_exists($service, 'metadata'))->toBeTrue();
});

it('has delete method', function () {
    $service = new AttachmentService;

    expect(method_exists($service, 'delete'))->toBeTrue();
});

it('has hasAttachments helper method', function () {
    $service = new AttachmentService;

    expect(method_exists($service, 'hasAttachments'))->toBeTrue();
});

it('has countFor helper method', function () {
    $service = new AttachmentService;

    expect(method_exists($service, 'countFor'))->toBeTrue();
});
