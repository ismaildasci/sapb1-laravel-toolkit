<?php

declare(strict_types=1);

use SapB1\Toolkit\Builders\Finance\BankPageBuilder;

it('creates builder with static method', function () {
    $builder = BankPageBuilder::create();
    expect($builder)->toBeInstanceOf(BankPageBuilder::class);
});

it('sets account code', function () {
    $data = BankPageBuilder::create()
        ->accountCode(1001)
        ->accountName('Main Account')
        ->build();

    expect($data['AccountCode'])->toBe(1001);
    expect($data['AccountName'])->toBe('Main Account');
});

it('sets amounts', function () {
    $data = BankPageBuilder::create()
        ->debitAmount(1000.00)
        ->creditAmount(500.00)
        ->build();

    expect($data['DebitAmount'])->toBe(1000.00);
    expect($data['CreditAmount'])->toBe(500.00);
});

it('chains methods fluently', function () {
    $data = BankPageBuilder::create()
        ->accountCode(1001)
        ->dueDate('2024-01-15')
        ->reference('REF001')
        ->build();

    expect($data)->toHaveCount(3);
});

it('excludes null values from build', function () {
    $data = BankPageBuilder::create()
        ->accountCode(1001)
        ->build();

    expect($data)->toHaveKey('AccountCode');
    expect($data)->not->toHaveKey('DebitAmount');
});
