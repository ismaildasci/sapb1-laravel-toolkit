<?php

declare(strict_types=1);

use SapB1\Toolkit\Builders\Finance\BankBuilder;

it('creates builder with static method', function () {
    $builder = BankBuilder::create();
    expect($builder)->toBeInstanceOf(BankBuilder::class);
});

it('sets bank code and name', function () {
    $data = BankBuilder::create()
        ->bankCode('BANK01')
        ->bankName('Test Bank')
        ->build();

    expect($data['BankCode'])->toBe('BANK01');
    expect($data['BankName'])->toBe('Test Bank');
});

it('sets swift number', function () {
    $data = BankBuilder::create()
        ->swiftNo('TESTSWIFT')
        ->countryCode('TR')
        ->build();

    expect($data['SwiftNo'])->toBe('TESTSWIFT');
    expect($data['CountryCode'])->toBe('TR');
});

it('chains methods fluently', function () {
    $data = BankBuilder::create()
        ->bankCode('BANK01')
        ->bankName('Test Bank')
        ->swiftNo('TESTSWIFT')
        ->countryCode('TR')
        ->build();

    expect($data)->toHaveCount(4);
});

it('excludes null values from build', function () {
    $data = BankBuilder::create()
        ->bankCode('BANK01')
        ->build();

    expect($data)->toHaveKey('BankCode');
    expect($data)->not->toHaveKey('BankName');
});
