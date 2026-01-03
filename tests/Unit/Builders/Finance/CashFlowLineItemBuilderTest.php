<?php

declare(strict_types=1);

use SapB1\Toolkit\Builders\Finance\CashFlowLineItemBuilder;

it('creates builder with static method', function () {
    $builder = CashFlowLineItemBuilder::create();
    expect($builder)->toBeInstanceOf(CashFlowLineItemBuilder::class);
});

it('sets line item name', function () {
    $data = CashFlowLineItemBuilder::create()
        ->lineItemName('Operating Cash Flow')
        ->build();

    expect($data['LineItemName'])->toBe('Operating Cash Flow');
});

it('sets active line item', function () {
    $data = CashFlowLineItemBuilder::create()
        ->activeLineItem('tYES')
        ->parentArticle(0)
        ->build();

    expect($data['ActiveLineItem'])->toBe('tYES');
    expect($data['ParentArticle'])->toBe(0);
});

it('sets level and drawer', function () {
    $data = CashFlowLineItemBuilder::create()
        ->level(1)
        ->drawer('Main')
        ->build();

    expect($data['Level'])->toBe(1);
    expect($data['Drawer'])->toBe('Main');
});

it('chains methods fluently', function () {
    $data = CashFlowLineItemBuilder::create()
        ->lineItemName('Test')
        ->activeLineItem('tYES')
        ->level(1)
        ->build();

    expect($data)->toHaveCount(3);
});

it('excludes null values from build', function () {
    $data = CashFlowLineItemBuilder::create()
        ->lineItemName('Test')
        ->build();

    expect($data)->toHaveKey('LineItemName');
    expect($data)->not->toHaveKey('ActiveLineItem');
});
