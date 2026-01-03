<?php

declare(strict_types=1);

use SapB1\Toolkit\Builders\Finance\BankStatementBuilder;

it('creates builder with static method', function () {
    $builder = BankStatementBuilder::create();
    expect($builder)->toBeInstanceOf(BankStatementBuilder::class);
});

it('sets bank account key', function () {
    $data = BankStatementBuilder::create()
        ->bankAccountKey('ACCT01')
        ->statementNumber('STM001')
        ->build();

    expect($data['BankAccountKey'])->toBe('ACCT01');
    expect($data['StatementNumber'])->toBe('STM001');
});

it('sets statement date and currency', function () {
    $data = BankStatementBuilder::create()
        ->statementDate('2024-01-15')
        ->currency('TRY')
        ->build();

    expect($data['StatementDate'])->toBe('2024-01-15');
    expect($data['Currency'])->toBe('TRY');
});

it('chains methods fluently', function () {
    $data = BankStatementBuilder::create()
        ->bankAccountKey('ACCT01')
        ->statementDate('2024-01-15')
        ->currency('TRY')
        ->build();

    expect($data)->toHaveCount(3);
});

it('excludes null values from build', function () {
    $data = BankStatementBuilder::create()
        ->bankAccountKey('ACCT01')
        ->build();

    expect($data)->toHaveKey('BankAccountKey');
    expect($data)->not->toHaveKey('Currency');
});
