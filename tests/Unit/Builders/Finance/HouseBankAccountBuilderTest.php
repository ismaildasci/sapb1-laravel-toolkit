<?php

declare(strict_types=1);

use SapB1\Toolkit\Builders\Finance\HouseBankAccountBuilder;

it('creates builder with static method', function () {
    $builder = HouseBankAccountBuilder::create();
    expect($builder)->toBeInstanceOf(HouseBankAccountBuilder::class);
});

it('sets bank code and account number', function () {
    $data = HouseBankAccountBuilder::create()
        ->bankCode('BANK01')
        ->accNo('123456789')
        ->build();

    expect($data['BankCode'])->toBe('BANK01');
    expect($data['AccNo'])->toBe('123456789');
});

it('sets account name and IBAN', function () {
    $data = HouseBankAccountBuilder::create()
        ->accountName('Main Account')
        ->ibanOfHouseBankAccount('TR000000000000000000000001')
        ->build();

    expect($data['AccountName'])->toBe('Main Account');
    expect($data['IbanOfHouseBankAccount'])->toBe('TR000000000000000000000001');
});

it('sets GL accounts', function () {
    $data = HouseBankAccountBuilder::create()
        ->glAccount('1000')
        ->glInterimAccount('1100')
        ->build();

    expect($data['GLAccount'])->toBe('1000');
    expect($data['GLInterimAccount'])->toBe('1100');
});

it('chains methods fluently', function () {
    $data = HouseBankAccountBuilder::create()
        ->bankCode('BANK01')
        ->accNo('123456789')
        ->accountName('Test Account')
        ->build();

    expect($data)->toHaveCount(3);
});

it('excludes null values from build', function () {
    $data = HouseBankAccountBuilder::create()
        ->bankCode('BANK01')
        ->build();

    expect($data)->toHaveKey('BankCode');
    expect($data)->not->toHaveKey('AccNo');
});
