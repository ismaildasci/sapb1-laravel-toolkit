<?php

declare(strict_types=1);

use SapB1\Toolkit\DTOs\Production\ProductTreeLineDto;
use SapB1\Toolkit\Enums\IssueMethod;

it('creates from array', function () {
    $data = [
        'ItemCode' => 'RAW001',
        'Quantity' => 2.0,
        'Warehouse' => 'WH01',
        'PriceList' => 1,
        'IssueMethod' => 'bomimBackflush',
        'ItemDescription' => 'Raw Material 001',
        'AdditionalQuantity' => 0.5,
        'LineNumber' => 1,
    ];

    $dto = ProductTreeLineDto::fromArray($data);

    expect($dto->itemCode)->toBe('RAW001');
    expect($dto->quantity)->toBe(2.0);
    expect($dto->warehouse)->toBe('WH01');
    expect($dto->priceList)->toBe(1);
    expect($dto->issueMethod)->toBe(IssueMethod::Backflush);
    expect($dto->itemDescription)->toBe('Raw Material 001');
    expect($dto->additionalQuantity)->toBe(0.5);
    expect($dto->lineNumber)->toBe(1);
});

it('converts to array', function () {
    $dto = new ProductTreeLineDto(
        itemCode: 'RAW001',
        quantity: 2.0,
        warehouse: 'WH01',
        issueMethod: IssueMethod::Manual,
    );

    $array = $dto->toArray();

    expect($array['ItemCode'])->toBe('RAW001');
    expect($array['Quantity'])->toBe(2.0);
    expect($array['Warehouse'])->toBe('WH01');
    expect($array['IssueMethod'])->toBe('bomimManual');
});

it('excludes null values in toArray', function () {
    $dto = new ProductTreeLineDto(
        itemCode: 'RAW001',
        quantity: 2.0,
    );

    $array = $dto->toArray();

    expect($array)->toHaveKey('ItemCode');
    expect($array)->toHaveKey('Quantity');
    expect($array)->not->toHaveKey('Warehouse');
    expect($array)->not->toHaveKey('IssueMethod');
});
