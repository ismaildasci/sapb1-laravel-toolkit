<?php

declare(strict_types=1);

use SapB1\Toolkit\Builders\Purchase\CorrectionPurchaseInvoiceBuilder;
use SapB1\Toolkit\Enums\CorrectionInvoiceItem;

it('builds correction purchase invoice data', function () {
    $builder = CorrectionPurchaseInvoiceBuilder::create()
        ->cardCode('V001')
        ->correctionInvoiceItem(CorrectionInvoiceItem::ShouldBe)
        ->correctedDocEntry(50);

    $data = $builder->build();

    expect($data['CardCode'])->toBe('V001');
    expect($data['CorrectionInvoiceItem'])->toBe('ciis_ShouldBe');
    expect($data['CorrectedDocEntry'])->toBe(50);
});

it('can set as should be type', function () {
    $builder = CorrectionPurchaseInvoiceBuilder::create()
        ->cardCode('V001')
        ->asShouldBe();

    $data = $builder->build();

    expect($data['CorrectionInvoiceItem'])->toBe('ciis_ShouldBe');
});

it('can set as was type', function () {
    $builder = CorrectionPurchaseInvoiceBuilder::create()
        ->cardCode('V001')
        ->asWas();

    $data = $builder->build();

    expect($data['CorrectionInvoiceItem'])->toBe('ciis_Was');
});

it('can be reset', function () {
    $builder = CorrectionPurchaseInvoiceBuilder::create()
        ->cardCode('V001')
        ->asShouldBe();

    $builder->reset();
    $data = $builder->build();

    expect($data)->toBeEmpty();
});
