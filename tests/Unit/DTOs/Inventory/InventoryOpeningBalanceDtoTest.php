<?php

declare(strict_types=1);

use SapB1\Toolkit\DTOs\Inventory\InventoryOpeningBalanceDto;
use SapB1\Toolkit\DTOs\Inventory\InventoryOpeningBalanceLineDto;

it('creates from array', function () {
    $data = [
        'DocEntry' => 1,
        'DocNum' => 1001,
        'DocDate' => '2024-01-15',
        'PostingDate' => '2024-01-15',
        'Remarks' => 'Opening balance',
        'Reference1' => 'REF001',
    ];

    $dto = InventoryOpeningBalanceDto::fromArray($data);

    expect($dto->docEntry)->toBe(1);
    expect($dto->docNum)->toBe(1001);
    expect($dto->docDate)->toBe('2024-01-15');
    expect($dto->postingDate)->toBe('2024-01-15');
    expect($dto->remarks)->toBe('Opening balance');
    expect($dto->reference1)->toBe('REF001');
});

it('creates from response', function () {
    $response = [
        'DocEntry' => 2,
        'DocNum' => 1002,
        'Series' => 10,
        'PriceSource' => 'LastEvaluatedPrice',
        'DocumentStatus' => 'bost_Open',
    ];

    $dto = InventoryOpeningBalanceDto::fromResponse($response);

    expect($dto->docEntry)->toBe(2);
    expect($dto->docNum)->toBe(1002);
    expect($dto->series)->toBe(10);
    expect($dto->priceSource)->toBe('LastEvaluatedPrice');
    expect($dto->documentStatus)->toBe('bost_Open');
});

it('converts to array', function () {
    $dto = new InventoryOpeningBalanceDto(
        docEntry: 1,
        docNum: 1001,
        docDate: '2024-01-15',
        remarks: 'Test',
    );

    $array = $dto->toArray();

    expect($array['DocEntry'])->toBe(1);
    expect($array['DocNum'])->toBe(1001);
    expect($array['DocDate'])->toBe('2024-01-15');
    expect($array['Remarks'])->toBe('Test');
});

it('handles inventory opening balance lines', function () {
    $data = [
        'DocEntry' => 1,
        'InventoryOpeningBalanceLines' => [
            [
                'LineNum' => 0,
                'ItemCode' => 'ITEM001',
                'Quantity' => 100.0,
                'WarehouseCode' => 'WH01',
                'Price' => 50.00,
            ],
            [
                'LineNum' => 1,
                'ItemCode' => 'ITEM002',
                'Quantity' => 200.0,
                'WarehouseCode' => 'WH01',
                'Price' => 25.00,
            ],
        ],
    ];

    $dto = InventoryOpeningBalanceDto::fromArray($data);

    expect($dto->inventoryOpeningBalanceLines)->toHaveCount(2);
    expect($dto->inventoryOpeningBalanceLines[0])->toBeInstanceOf(InventoryOpeningBalanceLineDto::class);
    expect($dto->inventoryOpeningBalanceLines[0]->itemCode)->toBe('ITEM001');
    expect($dto->inventoryOpeningBalanceLines[0]->quantity)->toBe(100.0);
    expect($dto->inventoryOpeningBalanceLines[1]->itemCode)->toBe('ITEM002');
});

it('excludes null values in toArray', function () {
    $dto = new InventoryOpeningBalanceDto(
        docEntry: 1,
        docDate: '2024-01-15',
    );

    $array = $dto->toArray();

    expect($array)->toHaveKey('DocEntry');
    expect($array)->toHaveKey('DocDate');
    expect($array)->not->toHaveKey('Remarks');
    expect($array)->not->toHaveKey('Reference1');
});

it('includes lines in toArray', function () {
    $line = new InventoryOpeningBalanceLineDto(
        lineNum: 0,
        itemCode: 'ITEM001',
        quantity: 100.0,
        price: 50.00,
    );

    $dto = new InventoryOpeningBalanceDto(
        docEntry: 1,
        inventoryOpeningBalanceLines: [$line],
    );

    $array = $dto->toArray();

    expect($array)->toHaveKey('InventoryOpeningBalanceLines');
    expect($array['InventoryOpeningBalanceLines'])->toHaveCount(1);
    expect($array['InventoryOpeningBalanceLines'][0]['ItemCode'])->toBe('ITEM001');
});
