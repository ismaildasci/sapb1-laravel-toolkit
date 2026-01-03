<?php

declare(strict_types=1);

use SapB1\Toolkit\Builders\Purchase\PurchaseQuotationBuilder;

it('builds purchase quotation data', function () {
    $builder = PurchaseQuotationBuilder::create()
        ->cardCode('V001')
        ->docDate('2024-01-15')
        ->docDueDate('2024-02-15')
        ->requiredDate('2024-02-01')
        ->validUntil('2024-01-31');

    $data = $builder->build();

    expect($data['CardCode'])->toBe('V001');
    expect($data['DocDate'])->toBe('2024-01-15');
    expect($data['RequiredDate'])->toBe('2024-02-01');
    expect($data['ValidUntil'])->toBe('2024-01-31');
});

it('can add document lines', function () {
    $builder = PurchaseQuotationBuilder::create()
        ->cardCode('V001')
        ->addLine(['ItemCode' => 'ITEM001', 'Quantity' => 10]);

    $data = $builder->build();

    expect($data['DocumentLines'])->toHaveCount(1);
    expect($data['DocumentLines'][0]['ItemCode'])->toBe('ITEM001');
});

it('can be reset', function () {
    $builder = PurchaseQuotationBuilder::create()
        ->cardCode('V001')
        ->requiredDate('2024-02-01');

    $builder->reset();
    $data = $builder->build();

    expect($data)->toBeEmpty();
});
