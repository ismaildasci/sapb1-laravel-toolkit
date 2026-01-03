<?php

declare(strict_types=1);

use SapB1\Toolkit\Builders\Finance\PaymentDraftBuilder;

it('creates builder with static method', function () {
    $builder = PaymentDraftBuilder::create();
    expect($builder)->toBeInstanceOf(PaymentDraftBuilder::class);
});

it('sets doc type and date', function () {
    $data = PaymentDraftBuilder::create()
        ->docType('rCustomer')
        ->docDate('2024-01-15')
        ->build();

    expect($data['DocType'])->toBe('rCustomer');
    expect($data['DocDate'])->toBe('2024-01-15');
});

it('sets card information', function () {
    $data = PaymentDraftBuilder::create()
        ->cardCode('C001')
        ->cardName('ABC Company')
        ->address('123 Main St')
        ->build();

    expect($data['CardCode'])->toBe('C001');
    expect($data['CardName'])->toBe('ABC Company');
    expect($data['Address'])->toBe('123 Main St');
});

it('sets payment sums', function () {
    $data = PaymentDraftBuilder::create()
        ->cashSum(1000.00)
        ->checkSum(2000.00)
        ->transferSum(3000.00)
        ->build();

    expect($data['CashSum'])->toBe(1000.00);
    expect($data['CheckSum'])->toBe(2000.00);
    expect($data['TransferSum'])->toBe(3000.00);
});

it('sets currency and references', function () {
    $data = PaymentDraftBuilder::create()
        ->docCurrency('USD')
        ->reference1('REF001')
        ->reference2('REF002')
        ->build();

    expect($data['DocCurrency'])->toBe('USD');
    expect($data['Reference1'])->toBe('REF001');
    expect($data['Reference2'])->toBe('REF002');
});

it('sets remarks', function () {
    $data = PaymentDraftBuilder::create()
        ->remarks('Payment remarks')
        ->journalRemarks('Journal entry remarks')
        ->build();

    expect($data['Remarks'])->toBe('Payment remarks');
    expect($data['JournalRemarks'])->toBe('Journal entry remarks');
});

it('sets dates', function () {
    $data = PaymentDraftBuilder::create()
        ->docDate('2024-01-15')
        ->taxDate('2024-01-15')
        ->dueDate('2024-02-15')
        ->build();

    expect($data['DocDate'])->toBe('2024-01-15');
    expect($data['TaxDate'])->toBe('2024-01-15');
    expect($data['DueDate'])->toBe('2024-02-15');
});

it('sets series and project', function () {
    $data = PaymentDraftBuilder::create()
        ->series(10)
        ->project('PRJ001')
        ->build();

    expect($data['Series'])->toBe(10);
    expect($data['Project'])->toBe('PRJ001');
});

it('sets accounts', function () {
    $data = PaymentDraftBuilder::create()
        ->cashAccount('1000')
        ->transferAccount('1100')
        ->build();

    expect($data['CashAccount'])->toBe('1000');
    expect($data['TransferAccount'])->toBe('1100');
});

it('chains methods fluently', function () {
    $data = PaymentDraftBuilder::create()
        ->docType('rCustomer')
        ->cardCode('C001')
        ->docDate('2024-01-15')
        ->cashSum(1000.00)
        ->build();

    expect($data)->toHaveCount(4);
});

it('excludes null values from build', function () {
    $data = PaymentDraftBuilder::create()
        ->cardCode('C001')
        ->build();

    expect($data)->toHaveKey('CardCode');
    expect($data)->not->toHaveKey('CashSum');
});
