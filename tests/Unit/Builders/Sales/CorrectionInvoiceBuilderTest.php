<?php

declare(strict_types=1);

use SapB1\Toolkit\Builders\Sales\CorrectionInvoiceBuilder;
use SapB1\Toolkit\Enums\CorrectionInvoiceItem;

it('builds correction invoice data', function () {
    $builder = CorrectionInvoiceBuilder::create()
        ->cardCode('C001')
        ->correctionInvoiceItem(CorrectionInvoiceItem::ShouldBe)
        ->correctedDocEntry(50);

    $data = $builder->build();

    expect($data['CardCode'])->toBe('C001');
    expect($data['CorrectionInvoiceItem'])->toBe('ciis_ShouldBe');
    expect($data['CorrectedDocEntry'])->toBe(50);
});

it('can set as should be type', function () {
    $builder = CorrectionInvoiceBuilder::create()
        ->cardCode('C001')
        ->asShouldBe();

    $data = $builder->build();

    expect($data['CorrectionInvoiceItem'])->toBe('ciis_ShouldBe');
});

it('can set as was type', function () {
    $builder = CorrectionInvoiceBuilder::create()
        ->cardCode('C001')
        ->asWas();

    $data = $builder->build();

    expect($data['CorrectionInvoiceItem'])->toBe('ciis_Was');
});

it('can be reset', function () {
    $builder = CorrectionInvoiceBuilder::create()
        ->cardCode('C001')
        ->asShouldBe();

    $builder->reset();
    $data = $builder->build();

    expect($data)->toBeEmpty();
});
