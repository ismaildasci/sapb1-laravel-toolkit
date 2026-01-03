<?php

declare(strict_types=1);

use SapB1\Toolkit\Builders\Finance\SalesTaxCodeBuilder;

it('creates builder with static method', function () {
    $builder = SalesTaxCodeBuilder::create();
    expect($builder)->toBeInstanceOf(SalesTaxCodeBuilder::class);
});

it('sets code and name', function () {
    $data = SalesTaxCodeBuilder::create()
        ->code('ST01')
        ->name('Sales Tax 8%')
        ->build();

    expect($data['Code'])->toBe('ST01');
    expect($data['Name'])->toBe('Sales Tax 8%');
});

it('sets rate and tax type', function () {
    $data = SalesTaxCodeBuilder::create()
        ->rate('8.00')
        ->taxType('bovcOutputTax')
        ->build();

    expect($data['Rate'])->toBe('8.00');
    expect($data['TaxType'])->toBe('bovcOutputTax');
});

it('chains methods fluently', function () {
    $data = SalesTaxCodeBuilder::create()
        ->code('ST01')
        ->name('Test Tax')
        ->rate('10.00')
        ->build();

    expect($data)->toHaveCount(3);
});

it('excludes null values from build', function () {
    $data = SalesTaxCodeBuilder::create()
        ->code('ST01')
        ->build();

    expect($data)->toHaveKey('Code');
    expect($data)->not->toHaveKey('Rate');
});
