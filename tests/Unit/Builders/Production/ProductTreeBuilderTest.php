<?php

declare(strict_types=1);

use SapB1\Toolkit\Builders\Production\ProductTreeBuilder;
use SapB1\Toolkit\Builders\Production\ProductTreeLineBuilder;
use SapB1\Toolkit\Enums\ProductTreeType;

it('creates builder with static method', function () {
    $builder = ProductTreeBuilder::create();
    expect($builder)->toBeInstanceOf(ProductTreeBuilder::class);
});

it('sets tree code', function () {
    $data = ProductTreeBuilder::create()
        ->treeCode('FG001')
        ->build();

    expect($data['TreeCode'])->toBe('FG001');
});

it('sets tree type', function () {
    $data = ProductTreeBuilder::create()
        ->treeType(ProductTreeType::Assembly)
        ->build();

    expect($data['TreeType'])->toBe('iProductionTree');
});

it('sets quantity', function () {
    $data = ProductTreeBuilder::create()
        ->quantity(1.0)
        ->build();

    expect($data['Quantity'])->toBe(1.0);
});

it('sets warehouse and price list', function () {
    $data = ProductTreeBuilder::create()
        ->warehouse('WH01')
        ->priceList(1)
        ->build();

    expect($data['Warehouse'])->toBe('WH01');
    expect($data['PriceList'])->toBe(1);
});

it('sets distribution rules', function () {
    $data = ProductTreeBuilder::create()
        ->distributionRule('DEPT01')
        ->distributionRule2('PROJECT01')
        ->build();

    expect($data['DistributionRule'])->toBe('DEPT01');
    expect($data['DistributionRule2'])->toBe('PROJECT01');
});

it('adds product tree lines', function () {
    $data = ProductTreeBuilder::create()
        ->treeCode('FG001')
        ->productTreeLines([
            ProductTreeLineBuilder::create()
                ->itemCode('RAW001')
                ->quantity(2.0),
            ['ItemCode' => 'RAW002', 'Quantity' => 3.0],
        ])
        ->build();

    expect($data['ProductTreeLines'])->toHaveCount(2);
    expect($data['ProductTreeLines'][0]['ItemCode'])->toBe('RAW001');
    expect($data['ProductTreeLines'][1]['ItemCode'])->toBe('RAW002');
});

it('adds single line fluently', function () {
    $data = ProductTreeBuilder::create()
        ->treeCode('FG001')
        ->addLine(ProductTreeLineBuilder::create()->itemCode('RAW001')->quantity(2.0))
        ->addLine(['ItemCode' => 'RAW002', 'Quantity' => 3.0])
        ->build();

    expect($data['ProductTreeLines'])->toHaveCount(2);
});

it('chains methods fluently', function () {
    $data = ProductTreeBuilder::create()
        ->treeCode('FG001')
        ->treeType(ProductTreeType::Assembly)
        ->quantity(1.0)
        ->warehouse('WH01')
        ->project('PRJ001')
        ->remarks('Finished goods BOM')
        ->build();

    expect($data)->toHaveCount(6);
});

it('excludes null values from build', function () {
    $data = ProductTreeBuilder::create()
        ->treeCode('FG001')
        ->quantity(1.0)
        ->build();

    expect($data)->toHaveKey('TreeCode');
    expect($data)->toHaveKey('Quantity');
    expect($data)->not->toHaveKey('Warehouse');
    expect($data)->not->toHaveKey('TreeType');
});
