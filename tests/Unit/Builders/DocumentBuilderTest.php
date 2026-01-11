<?php

declare(strict_types=1);

use SapB1\Toolkit\Builders\Sales\OrderBuilder;
use SapB1\Toolkit\DTOs\DocumentLineDto;

it('creates builder with static method', function () {
    $builder = OrderBuilder::create();

    expect($builder)->toBeInstanceOf(OrderBuilder::class);
});

it('sets card code', function () {
    $builder = OrderBuilder::create()
        ->cardCode('C001');

    $data = $builder->build();

    expect($data['CardCode'])->toBe('C001');
});

it('sets card name', function () {
    $builder = OrderBuilder::create()
        ->cardName('Test Customer');

    $data = $builder->build();

    expect($data['CardName'])->toBe('Test Customer');
});

it('sets dates', function () {
    $builder = OrderBuilder::create()
        ->docDate('2024-01-15')
        ->docDueDate('2024-02-15')
        ->taxDate('2024-01-15');

    $data = $builder->build();

    expect($data['DocDate'])->toBe('2024-01-15');
    expect($data['DocDueDate'])->toBe('2024-02-15');
    expect($data['TaxDate'])->toBe('2024-01-15');
});

it('sets currency and rate', function () {
    $builder = OrderBuilder::create()
        ->docCurrency('USD')
        ->docRate(32.50);

    $data = $builder->build();

    expect($data['DocCurrency'])->toBe('USD');
    expect($data['DocRate'])->toBe(32.50);
});

it('sets comments', function () {
    $builder = OrderBuilder::create()
        ->comments('Test order comment');

    $data = $builder->build();

    expect($data['Comments'])->toBe('Test order comment');
});

it('sets num at card', function () {
    $builder = OrderBuilder::create()
        ->numAtCard('PO-2024-001');

    $data = $builder->build();

    expect($data['NumAtCard'])->toBe('PO-2024-001');
});

it('sets address codes', function () {
    $builder = OrderBuilder::create()
        ->payToCode('BILLING')
        ->shipToCode('SHIPPING');

    $data = $builder->build();

    expect($data['PayToCode'])->toBe('BILLING');
    expect($data['ShipToCode'])->toBe('SHIPPING');
});

it('sets sales person code', function () {
    $builder = OrderBuilder::create()
        ->salesPersonCode(5);

    $data = $builder->build();

    expect($data['SalesPersonCode'])->toBe(5);
});

it('sets series', function () {
    $builder = OrderBuilder::create()
        ->series(10);

    $data = $builder->build();

    expect($data['Series'])->toBe(10);
});

it('sets discount percent', function () {
    $builder = OrderBuilder::create()
        ->discountPercent(10.0);

    $data = $builder->build();

    expect($data['DiscountPercent'])->toBe(10.0);
});

it('sets project', function () {
    $builder = OrderBuilder::create()
        ->project('PRJ001');

    $data = $builder->build();

    expect($data['Project'])->toBe('PRJ001');
});

it('adds document lines from array', function () {
    $builder = OrderBuilder::create()
        ->documentLines([
            ['ItemCode' => 'ITEM001', 'Quantity' => 10],
            ['ItemCode' => 'ITEM002', 'Quantity' => 5],
        ]);

    $data = $builder->build();

    expect($data['DocumentLines'])->toHaveCount(2);
    expect($data['DocumentLines'][0]['ItemCode'])->toBe('ITEM001');
    expect($data['DocumentLines'][1]['ItemCode'])->toBe('ITEM002');
});

it('adds document lines from DTO', function () {
    $line = new DocumentLineDto(
        itemCode: 'ITEM001',
        quantity: 10.0,
        price: 100.00,
    );

    $builder = OrderBuilder::create()
        ->documentLines([$line]);

    $data = $builder->build();

    expect($data['DocumentLines'])->toHaveCount(1);
    expect($data['DocumentLines'][0]['ItemCode'])->toBe('ITEM001');
});

it('adds single line', function () {
    $builder = OrderBuilder::create()
        ->addLine(['ItemCode' => 'ITEM001', 'Quantity' => 10])
        ->addLine(['ItemCode' => 'ITEM002', 'Quantity' => 5]);

    $data = $builder->build();

    expect($data['DocumentLines'])->toHaveCount(2);
});

it('resets builder data', function () {
    $builder = OrderBuilder::create()
        ->cardCode('C001')
        ->comments('Test');

    $builder->reset();
    $data = $builder->build();

    expect($data)->toBeEmpty();
});

it('chains methods fluently', function () {
    $data = OrderBuilder::create()
        ->cardCode('C001')
        ->docDate('2024-01-15')
        ->docCurrency('TRY')
        ->comments('Fluent test')
        ->addLine(['ItemCode' => 'ITEM001', 'Quantity' => 1])
        ->build();

    expect($data['CardCode'])->toBe('C001');
    expect($data['DocDate'])->toBe('2024-01-15');
    expect($data['DocCurrency'])->toBe('TRY');
    expect($data['Comments'])->toBe('Fluent test');
    expect($data['DocumentLines'])->toHaveCount(1);
});

it('converts to array via toArray method', function () {
    $builder = OrderBuilder::create()
        ->cardCode('C001');

    expect($builder->toArray())->toBe($builder->build());
});

it('excludes null values from build', function () {
    $data = OrderBuilder::create()
        ->cardCode('C001')
        ->build();

    expect($data)->toHaveKey('CardCode');
    expect($data)->not->toHaveKey('Comments');
    expect($data)->not->toHaveKey('DocDate');
});

// ==================== UDF SUPPORT ====================

it('sets single UDF without prefix', function () {
    $data = OrderBuilder::create()
        ->udf('CustomField', 'test value')
        ->build();

    expect($data['U_CustomField'])->toBe('test value');
});

it('sets single UDF with prefix', function () {
    $data = OrderBuilder::create()
        ->udf('U_CustomField', 'test value')
        ->build();

    expect($data['U_CustomField'])->toBe('test value');
});

it('sets multiple UDFs at once', function () {
    $data = OrderBuilder::create()
        ->udfs([
            'CustomField' => 'value1',
            'AnotherField' => 123,
            'DateField' => '2024-01-15',
        ])
        ->build();

    expect($data['U_CustomField'])->toBe('value1');
    expect($data['U_AnotherField'])->toBe(123);
    expect($data['U_DateField'])->toBe('2024-01-15');
});

it('gets UDF value from builder', function () {
    $builder = OrderBuilder::create()
        ->udf('CustomField', 'test value');

    expect($builder->getUdf('CustomField'))->toBe('test value');
    expect($builder->getUdf('U_CustomField'))->toBe('test value');
});

it('checks if UDF is set in builder', function () {
    $builder = OrderBuilder::create()
        ->udf('CustomField', 'test value');

    expect($builder->hasUdf('CustomField'))->toBeTrue();
    expect($builder->hasUdf('U_CustomField'))->toBeTrue();
    expect($builder->hasUdf('NonExistent'))->toBeFalse();
});

it('chains UDF methods with other methods', function () {
    $data = OrderBuilder::create()
        ->cardCode('C001')
        ->docDate('2024-01-15')
        ->udf('CustomField', 'custom value')
        ->udf('Priority', 'High')
        ->addLine(['ItemCode' => 'ITEM001', 'Quantity' => 1])
        ->build();

    expect($data['CardCode'])->toBe('C001');
    expect($data['DocDate'])->toBe('2024-01-15');
    expect($data['U_CustomField'])->toBe('custom value');
    expect($data['U_Priority'])->toBe('High');
    expect($data['DocumentLines'])->toHaveCount(1);
});
