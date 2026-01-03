<?php

declare(strict_types=1);

use SapB1\Toolkit\Builders\Production\ProductionOrderBuilder;
use SapB1\Toolkit\Builders\Production\ProductionOrderLineBuilder;
use SapB1\Toolkit\Enums\ProductionOrderStatus;
use SapB1\Toolkit\Enums\ProductionOrderType;

it('creates builder with static method', function () {
    $builder = ProductionOrderBuilder::create();
    expect($builder)->toBeInstanceOf(ProductionOrderBuilder::class);
});

it('sets item number', function () {
    $data = ProductionOrderBuilder::create()
        ->itemNo('A001')
        ->build();

    expect($data['ItemNo'])->toBe('A001');
});

it('sets planned quantity', function () {
    $data = ProductionOrderBuilder::create()
        ->plannedQuantity(100.0)
        ->build();

    expect($data['PlannedQuantity'])->toBe(100.0);
});

it('sets production order status', function () {
    $data = ProductionOrderBuilder::create()
        ->productionOrderStatus(ProductionOrderStatus::Planned)
        ->build();

    expect($data['ProductionOrderStatus'])->toBe('boposPlanned');
});

it('sets production order type', function () {
    $data = ProductionOrderBuilder::create()
        ->productionOrderType(ProductionOrderType::Standard)
        ->build();

    expect($data['ProductionOrderType'])->toBe('bopotStandard');
});

it('sets warehouse', function () {
    $data = ProductionOrderBuilder::create()
        ->warehouse('WH01')
        ->build();

    expect($data['Warehouse'])->toBe('WH01');
});

it('sets dates', function () {
    $data = ProductionOrderBuilder::create()
        ->postingDate('2026-01-15')
        ->dueDate('2026-01-20')
        ->startDate('2026-01-15')
        ->build();

    expect($data['PostingDate'])->toBe('2026-01-15');
    expect($data['DueDate'])->toBe('2026-01-20');
    expect($data['StartDate'])->toBe('2026-01-15');
});

it('sets remarks and customer', function () {
    $data = ProductionOrderBuilder::create()
        ->remarks('Production order for customer')
        ->customer('C001')
        ->build();

    expect($data['Remarks'])->toBe('Production order for customer');
    expect($data['Customer'])->toBe('C001');
});

it('adds production order lines', function () {
    $data = ProductionOrderBuilder::create()
        ->itemNo('FG001')
        ->productionOrderLines([
            ProductionOrderLineBuilder::create()
                ->itemNo('RAW001')
                ->plannedQuantity(20.0),
            ['ItemNo' => 'RAW002', 'PlannedQuantity' => 10.0],
        ])
        ->build();

    expect($data['ProductionOrderLines'])->toHaveCount(2);
    expect($data['ProductionOrderLines'][0]['ItemNo'])->toBe('RAW001');
    expect($data['ProductionOrderLines'][1]['ItemNo'])->toBe('RAW002');
});

it('adds single line fluently', function () {
    $data = ProductionOrderBuilder::create()
        ->itemNo('FG001')
        ->addLine(ProductionOrderLineBuilder::create()->itemNo('RAW001')->plannedQuantity(10.0))
        ->addLine(['ItemNo' => 'RAW002', 'PlannedQuantity' => 5.0])
        ->build();

    expect($data['ProductionOrderLines'])->toHaveCount(2);
});

it('chains methods fluently', function () {
    $data = ProductionOrderBuilder::create()
        ->series(1)
        ->itemNo('FG001')
        ->plannedQuantity(100.0)
        ->warehouse('WH01')
        ->priority(1)
        ->project('PRJ001')
        ->build();

    expect($data)->toHaveCount(6);
});

it('excludes null values from build', function () {
    $data = ProductionOrderBuilder::create()
        ->itemNo('FG001')
        ->plannedQuantity(100.0)
        ->build();

    expect($data)->toHaveKey('ItemNo');
    expect($data)->toHaveKey('PlannedQuantity');
    expect($data)->not->toHaveKey('Warehouse');
    expect($data)->not->toHaveKey('Remarks');
});
