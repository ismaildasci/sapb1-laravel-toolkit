<?php

declare(strict_types=1);

use SapB1\Toolkit\DTOs\DocumentLineDto;

it('creates from array', function () {
    $data = [
        'LineNum' => 0,
        'ItemCode' => 'ITEM001',
        'ItemDescription' => 'Test Product',
        'Quantity' => 10.0,
        'Price' => 100.00,
        'Currency' => 'TRY',
        'LineTotal' => 1000.00,
        'TaxCode' => 'KDV18',
        'WarehouseCode' => 'WH01',
    ];

    $dto = DocumentLineDto::fromArray($data);

    expect($dto->lineNum)->toBe(0);
    expect($dto->itemCode)->toBe('ITEM001');
    expect($dto->itemDescription)->toBe('Test Product');
    expect($dto->quantity)->toBe(10.0);
    expect($dto->price)->toBe(100.00);
    expect($dto->currency)->toBe('TRY');
    expect($dto->lineTotal)->toBe(1000.00);
    expect($dto->taxCode)->toBe('KDV18');
    expect($dto->warehouseCode)->toBe('WH01');
});

it('converts to array', function () {
    $dto = new DocumentLineDto(
        lineNum: 0,
        itemCode: 'ITEM001',
        quantity: 5.0,
        price: 200.00,
    );

    $array = $dto->toArray();

    expect($array['LineNum'])->toBe(0);
    expect($array['ItemCode'])->toBe('ITEM001');
    expect($array['Quantity'])->toBe(5.0);
    expect($array['Price'])->toBe(200.00);
});

it('excludes null values in toArray', function () {
    $dto = new DocumentLineDto(
        itemCode: 'ITEM001',
        quantity: 1.0,
    );

    $array = $dto->toArray();

    expect($array)->toHaveKey('ItemCode');
    expect($array)->toHaveKey('Quantity');
    expect($array)->not->toHaveKey('LineNum');
    expect($array)->not->toHaveKey('Price');
});

it('handles base document reference', function () {
    $data = [
        'LineNum' => 0,
        'ItemCode' => 'ITEM001',
        'Quantity' => 10.0,
        'BaseType' => 17,
        'BaseEntry' => 5,
        'BaseLine' => 0,
    ];

    $dto = DocumentLineDto::fromArray($data);

    expect($dto->baseType)->toBe(17);
    expect($dto->baseEntry)->toBe(5);
    expect($dto->baseLine)->toBe(0);
});

it('handles discount fields', function () {
    $data = [
        'ItemCode' => 'ITEM001',
        'Quantity' => 10.0,
        'UnitPrice' => 100.00,
        'DiscountPercent' => 10.0,
        'LineTotal' => 900.00,
    ];

    $dto = DocumentLineDto::fromArray($data);

    expect($dto->discountPercent)->toBe(10.0);
    expect($dto->lineTotal)->toBe(900.00);
});

it('handles costing codes', function () {
    $data = [
        'ItemCode' => 'ITEM001',
        'Quantity' => 1.0,
        'CostingCode' => 'DEPT01',
        'CostingCode2' => 'PRJ01',
        'CostingCode3' => 'REG01',
    ];

    $dto = DocumentLineDto::fromArray($data);

    expect($dto->costingCode)->toBe('DEPT01');
    expect($dto->costingCode2)->toBe('PRJ01');
    expect($dto->costingCode3)->toBe('REG01');
});
