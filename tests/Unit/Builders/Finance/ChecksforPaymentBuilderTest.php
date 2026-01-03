<?php

declare(strict_types=1);

use SapB1\Toolkit\Builders\Finance\ChecksforPaymentBuilder;

it('creates builder with static method', function () {
    $builder = ChecksforPaymentBuilder::create();
    expect($builder)->toBeInstanceOf(ChecksforPaymentBuilder::class);
});

it('sets check number and bank code', function () {
    $data = ChecksforPaymentBuilder::create()
        ->checkNumber(12345)
        ->bankCode('BANK01')
        ->build();

    expect($data['CheckNumber'])->toBe(12345);
    expect($data['BankCode'])->toBe('BANK01');
});

it('sets check amount and date', function () {
    $data = ChecksforPaymentBuilder::create()
        ->checkAmount(1000.00)
        ->checkDate('2024-01-15')
        ->build();

    expect($data['CheckAmount'])->toBe(1000.00);
    expect($data['CheckDate'])->toBe('2024-01-15');
});

it('sets vendor information', function () {
    $data = ChecksforPaymentBuilder::create()
        ->vendorCode('V001')
        ->vendorName('Test Vendor')
        ->build();

    expect($data['VendorCode'])->toBe('V001');
    expect($data['VendorName'])->toBe('Test Vendor');
});

it('chains methods fluently', function () {
    $data = ChecksforPaymentBuilder::create()
        ->checkNumber(12345)
        ->bankCode('BANK01')
        ->checkAmount(500.00)
        ->build();

    expect($data)->toHaveCount(3);
});

it('excludes null values from build', function () {
    $data = ChecksforPaymentBuilder::create()
        ->checkNumber(12345)
        ->build();

    expect($data)->toHaveKey('CheckNumber');
    expect($data)->not->toHaveKey('CheckAmount');
});
