<?php

declare(strict_types=1);

use SapB1\Toolkit\Builders\Production\ProductionOrderLineBuilder;
use SapB1\Toolkit\Enums\IssueMethod;

it('creates builder with static method', function () {
    $builder = ProductionOrderLineBuilder::create();
    expect($builder)->toBeInstanceOf(ProductionOrderLineBuilder::class);
});

it('sets line number', function () {
    $data = ProductionOrderLineBuilder::create()
        ->lineNumber(1)
        ->build();

    expect($data['LineNumber'])->toBe(1);
});

it('sets item number', function () {
    $data = ProductionOrderLineBuilder::create()
        ->itemNo('RAW001')
        ->build();

    expect($data['ItemNo'])->toBe('RAW001');
});

it('sets quantities', function () {
    $data = ProductionOrderLineBuilder::create()
        ->baseQuantity(2.0)
        ->plannedQuantity(20.0)
        ->additionalQuantity(1.0)
        ->build();

    expect($data['BaseQuantity'])->toBe(2.0);
    expect($data['PlannedQuantity'])->toBe(20.0);
    expect($data['AdditionalQuantity'])->toBe(1.0);
});

it('sets warehouse', function () {
    $data = ProductionOrderLineBuilder::create()
        ->warehouse('WH01')
        ->build();

    expect($data['Warehouse'])->toBe('WH01');
});

it('sets issue method', function () {
    $data = ProductionOrderLineBuilder::create()
        ->issueMethod(IssueMethod::Backflush)
        ->build();

    expect($data['IssueMethod'])->toBe('bomimBackflush');
});

it('sets dates', function () {
    $data = ProductionOrderLineBuilder::create()
        ->startDate('2026-01-15')
        ->endDate('2026-01-20')
        ->build();

    expect($data['StartDate'])->toBe('2026-01-15');
    expect($data['EndDate'])->toBe('2026-01-20');
});

it('chains methods fluently', function () {
    $data = ProductionOrderLineBuilder::create()
        ->lineNumber(1)
        ->itemNo('RAW001')
        ->plannedQuantity(20.0)
        ->warehouse('WH01')
        ->issueMethod(IssueMethod::Manual)
        ->build();

    expect($data)->toHaveCount(5);
});

it('excludes null values from build', function () {
    $data = ProductionOrderLineBuilder::create()
        ->itemNo('RAW001')
        ->plannedQuantity(20.0)
        ->build();

    expect($data)->toHaveKey('ItemNo');
    expect($data)->toHaveKey('PlannedQuantity');
    expect($data)->not->toHaveKey('Warehouse');
    expect($data)->not->toHaveKey('IssueMethod');
});
