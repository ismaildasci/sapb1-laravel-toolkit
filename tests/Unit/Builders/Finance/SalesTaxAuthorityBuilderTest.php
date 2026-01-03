<?php

declare(strict_types=1);

use SapB1\Toolkit\Builders\Finance\SalesTaxAuthorityBuilder;

it('creates builder with static method', function () {
    $builder = SalesTaxAuthorityBuilder::create();
    expect($builder)->toBeInstanceOf(SalesTaxAuthorityBuilder::class);
});

it('sets name and user signature', function () {
    $data = SalesTaxAuthorityBuilder::create()
        ->name('Tax Authority 1')
        ->userSignature('admin')
        ->build();

    expect($data['Name'])->toBe('Tax Authority 1');
    expect($data['UserSignature'])->toBe('admin');
});

it('sets tax account and type', function () {
    $data = SalesTaxAuthorityBuilder::create()
        ->taxAccount('3910')
        ->taxType('tt_Yes')
        ->build();

    expect($data['TaxAccount'])->toBe('3910');
    expect($data['TaxType'])->toBe('tt_Yes');
});

it('sets business partner', function () {
    $data = SalesTaxAuthorityBuilder::create()
        ->businessPartner('BP001')
        ->dataExportCode('EXP01')
        ->build();

    expect($data['BusinessPartner'])->toBe('BP001');
    expect($data['DataExportCode'])->toBe('EXP01');
});

it('chains methods fluently', function () {
    $data = SalesTaxAuthorityBuilder::create()
        ->name('Test')
        ->taxAccount('3910')
        ->taxType('tt_Yes')
        ->build();

    expect($data)->toHaveCount(3);
});

it('excludes null values from build', function () {
    $data = SalesTaxAuthorityBuilder::create()
        ->name('Test')
        ->build();

    expect($data)->toHaveKey('Name');
    expect($data)->not->toHaveKey('TaxAccount');
});
