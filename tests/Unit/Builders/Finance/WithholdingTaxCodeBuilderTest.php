<?php

declare(strict_types=1);

use SapB1\Toolkit\Builders\Finance\WithholdingTaxCodeBuilder;

it('creates builder with static method', function () {
    $builder = WithholdingTaxCodeBuilder::create();
    expect($builder)->toBeInstanceOf(WithholdingTaxCodeBuilder::class);
});

it('sets code and name', function () {
    $data = WithholdingTaxCodeBuilder::create()
        ->wTCode('WT01')
        ->wTName('Withholding Tax 20%')
        ->build();

    expect($data['WTCode'])->toBe('WT01');
    expect($data['WTName'])->toBe('Withholding Tax 20%');
});

it('sets withholding rate', function () {
    $data = WithholdingTaxCodeBuilder::create()
        ->withholdingRate(20.0)
        ->category('bowtcVendorPayment')
        ->build();

    expect($data['WithholdingRate'])->toBe(20.0);
    expect($data['Category'])->toBe('bowtcVendorPayment');
});

it('chains methods fluently', function () {
    $data = WithholdingTaxCodeBuilder::create()
        ->wTCode('WT01')
        ->wTName('Test')
        ->withholdingRate(15.0)
        ->build();

    expect($data)->toHaveCount(3);
});

it('excludes null values from build', function () {
    $data = WithholdingTaxCodeBuilder::create()
        ->wTCode('WT01')
        ->build();

    expect($data)->toHaveKey('WTCode');
    expect($data)->not->toHaveKey('WithholdingRate');
});
