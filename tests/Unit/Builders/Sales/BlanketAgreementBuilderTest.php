<?php

declare(strict_types=1);

use SapB1\Toolkit\Builders\Sales\BlanketAgreementBuilder;
use SapB1\Toolkit\Enums\BlanketAgreementMethod;
use SapB1\Toolkit\Enums\BlanketAgreementStatus;

it('builds blanket agreement data', function () {
    $builder = BlanketAgreementBuilder::create()
        ->bpCode('C001')
        ->bpName('Test Customer')
        ->startDate('2024-01-01')
        ->endDate('2024-12-31')
        ->agreementMethod(BlanketAgreementMethod::Monetary)
        ->status(BlanketAgreementStatus::Approved);

    $data = $builder->build();

    expect($data['BPCode'])->toBe('C001');
    expect($data['BPName'])->toBe('Test Customer');
    expect($data['StartDate'])->toBe('2024-01-01');
    expect($data['AgreementMethod'])->toBe('bamMonetary');
    expect($data['Status'])->toBe('asApproved');
});

it('can set as monetary', function () {
    $builder = BlanketAgreementBuilder::create()
        ->bpCode('C001')
        ->asMonetary();

    $data = $builder->build();

    expect($data['AgreementMethod'])->toBe('bamMonetary');
});

it('can set as quantity', function () {
    $builder = BlanketAgreementBuilder::create()
        ->bpCode('C001')
        ->asQuantity();

    $data = $builder->build();

    expect($data['AgreementMethod'])->toBe('bamQuantity');
});

it('can add item lines', function () {
    $builder = BlanketAgreementBuilder::create()
        ->bpCode('C001')
        ->addItemLine(['ItemNo' => 'ITEM001', 'PlannedQuantity' => 100])
        ->addItemLine(['ItemNo' => 'ITEM002', 'PlannedQuantity' => 50]);

    $data = $builder->build();

    expect($data['BlanketAgreementItemsLines'])->toHaveCount(2);
    expect($data['BlanketAgreementItemsLines'][0]['ItemNo'])->toBe('ITEM001');
});

it('can be reset', function () {
    $builder = BlanketAgreementBuilder::create()
        ->bpCode('C001')
        ->asMonetary();

    $builder->reset();
    $data = $builder->build();

    expect($data)->toBeEmpty();
});
