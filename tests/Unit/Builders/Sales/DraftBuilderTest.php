<?php

declare(strict_types=1);

use SapB1\Toolkit\Builders\Sales\DraftBuilder;
use SapB1\Toolkit\Enums\DocumentType;

it('builds draft data', function () {
    $builder = DraftBuilder::create()
        ->cardCode('C001')
        ->docDate('2024-01-15')
        ->docObjectCode(DocumentType::SalesOrder);

    $data = $builder->build();

    expect($data['CardCode'])->toBe('C001');
    expect($data['DocDate'])->toBe('2024-01-15');
    expect($data['DocObjectCode'])->toBe(17);
});

it('can set as sales order', function () {
    $builder = DraftBuilder::create()
        ->cardCode('C001')
        ->asSalesOrder();

    $data = $builder->build();

    expect($data['DocObjectCode'])->toBe(17);
});

it('can set as invoice', function () {
    $builder = DraftBuilder::create()
        ->cardCode('C001')
        ->asInvoice();

    $data = $builder->build();

    expect($data['DocObjectCode'])->toBe(13);
});

it('can set as quotation', function () {
    $builder = DraftBuilder::create()
        ->cardCode('C001')
        ->asQuotation();

    $data = $builder->build();

    expect($data['DocObjectCode'])->toBe(23);
});

it('can be reset', function () {
    $builder = DraftBuilder::create()
        ->cardCode('C001')
        ->asSalesOrder();

    $builder->reset();
    $data = $builder->build();

    expect($data)->toBeEmpty();
});
