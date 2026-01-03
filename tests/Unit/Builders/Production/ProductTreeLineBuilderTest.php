<?php

declare(strict_types=1);

use SapB1\Toolkit\Builders\Production\ProductTreeLineBuilder;
use SapB1\Toolkit\Enums\IssueMethod;

it('creates builder with static method', function () {
    $builder = ProductTreeLineBuilder::create();
    expect($builder)->toBeInstanceOf(ProductTreeLineBuilder::class);
});

it('sets item code', function () {
    $data = ProductTreeLineBuilder::create()
        ->itemCode('RAW001')
        ->build();

    expect($data['ItemCode'])->toBe('RAW001');
});

it('sets quantity', function () {
    $data = ProductTreeLineBuilder::create()
        ->quantity(2.0)
        ->build();

    expect($data['Quantity'])->toBe(2.0);
});

it('sets warehouse', function () {
    $data = ProductTreeLineBuilder::create()
        ->warehouse('WH01')
        ->build();

    expect($data['Warehouse'])->toBe('WH01');
});

it('sets price list', function () {
    $data = ProductTreeLineBuilder::create()
        ->priceList(1)
        ->build();

    expect($data['PriceList'])->toBe(1);
});

it('sets issue method', function () {
    $data = ProductTreeLineBuilder::create()
        ->issueMethod(IssueMethod::Backflush)
        ->build();

    expect($data['IssueMethod'])->toBe('bomimBackflush');
});

it('sets item description', function () {
    $data = ProductTreeLineBuilder::create()
        ->itemDescription('Raw Material 001')
        ->build();

    expect($data['ItemDescription'])->toBe('Raw Material 001');
});

it('sets additional quantity', function () {
    $data = ProductTreeLineBuilder::create()
        ->additionalQuantity(0.5)
        ->build();

    expect($data['AdditionalQuantity'])->toBe(0.5);
});

it('chains methods fluently', function () {
    $data = ProductTreeLineBuilder::create()
        ->itemCode('RAW001')
        ->quantity(2.0)
        ->warehouse('WH01')
        ->issueMethod(IssueMethod::Manual)
        ->build();

    expect($data)->toHaveCount(4);
});

it('excludes null values from build', function () {
    $data = ProductTreeLineBuilder::create()
        ->itemCode('RAW001')
        ->quantity(2.0)
        ->build();

    expect($data)->toHaveKey('ItemCode');
    expect($data)->toHaveKey('Quantity');
    expect($data)->not->toHaveKey('Warehouse');
    expect($data)->not->toHaveKey('IssueMethod');
});
