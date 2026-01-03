<?php

declare(strict_types=1);

use SapB1\Toolkit\DTOs\Finance\CashFlowLineItemDto;

it('creates from array', function () {
    $data = [
        'LineItemID' => 1,
        'LineItemName' => 'Operating Cash Flow',
        'ActiveLineItem' => 'tYES',
        'ParentArticle' => 0,
    ];

    $dto = CashFlowLineItemDto::fromArray($data);

    expect($dto->lineItemID)->toBe(1);
    expect($dto->lineItemName)->toBe('Operating Cash Flow');
    expect($dto->activeLineItem)->toBe('tYES');
    expect($dto->parentArticle)->toBe(0);
});

it('creates from response', function () {
    $response = [
        'LineItemID' => 2,
        'LineItemName' => 'Investment Cash Flow',
        'Level' => 1,
        'Drawer' => 'Main',
    ];

    $dto = CashFlowLineItemDto::fromResponse($response);

    expect($dto->lineItemID)->toBe(2);
    expect($dto->lineItemName)->toBe('Investment Cash Flow');
    expect($dto->level)->toBe(1);
    expect($dto->drawer)->toBe('Main');
});

it('converts to array', function () {
    $dto = new CashFlowLineItemDto(
        lineItemID: 1,
        lineItemName: 'Test Item',
        activeLineItem: 'tYES',
    );

    $array = $dto->toArray();

    expect($array['LineItemID'])->toBe(1);
    expect($array['LineItemName'])->toBe('Test Item');
    expect($array['ActiveLineItem'])->toBe('tYES');
});

it('excludes null values in toArray', function () {
    $dto = new CashFlowLineItemDto(
        lineItemID: 1,
        lineItemName: 'Test',
    );

    $array = $dto->toArray();

    expect($array)->toHaveKey('LineItemID');
    expect($array)->toHaveKey('LineItemName');
    expect($array)->not->toHaveKey('ActiveLineItem');
    expect($array)->not->toHaveKey('ParentArticle');
});
