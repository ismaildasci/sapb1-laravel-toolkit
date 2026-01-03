<?php

declare(strict_types=1);

use SapB1\Toolkit\DTOs\Inventory\InventoryPostingDto;
use SapB1\Toolkit\DTOs\Inventory\InventoryPostingLineDto;

it('creates from array', function () {
    $data = [
        'DocEntry' => 1,
        'DocNum' => 1001,
        'DocDate' => '2024-01-15',
        'CountDate' => '2024-01-15',
        'CountTime' => '10:30:00',
        'Reference1' => 'REF001',
        'Remarks' => 'Test posting',
    ];

    $dto = InventoryPostingDto::fromArray($data);

    expect($dto->docEntry)->toBe(1);
    expect($dto->docNum)->toBe(1001);
    expect($dto->docDate)->toBe('2024-01-15');
    expect($dto->countDate)->toBe('2024-01-15');
    expect($dto->countTime)->toBe('10:30:00');
    expect($dto->reference1)->toBe('REF001');
    expect($dto->remarks)->toBe('Test posting');
});

it('creates from response', function () {
    $response = [
        'DocEntry' => 2,
        'DocNum' => 1002,
        'Series' => 10,
        'PriceSource' => 'LastEvaluatedPrice',
    ];

    $dto = InventoryPostingDto::fromResponse($response);

    expect($dto->docEntry)->toBe(2);
    expect($dto->docNum)->toBe(1002);
    expect($dto->series)->toBe(10);
    expect($dto->priceSource)->toBe('LastEvaluatedPrice');
});

it('converts to array', function () {
    $dto = new InventoryPostingDto(
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

it('handles inventory posting lines', function () {
    $data = [
        'DocEntry' => 1,
        'InventoryPostingLines' => [
            [
                'LineNum' => 0,
                'ItemCode' => 'ITEM001',
                'CountedQuantity' => 10.0,
                'WarehouseCode' => 'WH01',
            ],
            [
                'LineNum' => 1,
                'ItemCode' => 'ITEM002',
                'CountedQuantity' => 5.0,
                'WarehouseCode' => 'WH01',
            ],
        ],
    ];

    $dto = InventoryPostingDto::fromArray($data);

    expect($dto->inventoryPostingLines)->toHaveCount(2);
    expect($dto->inventoryPostingLines[0])->toBeInstanceOf(InventoryPostingLineDto::class);
    expect($dto->inventoryPostingLines[0]->itemCode)->toBe('ITEM001');
    expect($dto->inventoryPostingLines[1]->itemCode)->toBe('ITEM002');
});

it('excludes null values in toArray', function () {
    $dto = new InventoryPostingDto(
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
    $line = new InventoryPostingLineDto(
        lineNum: 0,
        itemCode: 'ITEM001',
        countedQuantity: 10.0,
    );

    $dto = new InventoryPostingDto(
        docEntry: 1,
        inventoryPostingLines: [$line],
    );

    $array = $dto->toArray();

    expect($array)->toHaveKey('InventoryPostingLines');
    expect($array['InventoryPostingLines'])->toHaveCount(1);
    expect($array['InventoryPostingLines'][0]['ItemCode'])->toBe('ITEM001');
});
