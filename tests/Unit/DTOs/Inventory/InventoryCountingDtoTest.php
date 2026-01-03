<?php

declare(strict_types=1);

use SapB1\Toolkit\DTOs\Inventory\InventoryCountingDto;
use SapB1\Toolkit\DTOs\Inventory\InventoryCountingLineDto;

it('creates from array', function () {
    $data = [
        'DocEntry' => 1,
        'DocNum' => 1001,
        'DocDate' => '2024-01-15',
        'CountDate' => '2024-01-15',
        'CountTime' => '10:30:00',
        'Reference1' => 'REF001',
        'Remarks' => 'Test counting',
    ];

    $dto = InventoryCountingDto::fromArray($data);

    expect($dto->docEntry)->toBe(1);
    expect($dto->docNum)->toBe(1001);
    expect($dto->docDate)->toBe('2024-01-15');
    expect($dto->countDate)->toBe('2024-01-15');
    expect($dto->countTime)->toBe('10:30:00');
    expect($dto->reference1)->toBe('REF001');
    expect($dto->remarks)->toBe('Test counting');
});

it('creates from response', function () {
    $response = [
        'DocEntry' => 2,
        'DocNum' => 1002,
        'Series' => 10,
        'CountingType' => 'ctItemCode',
        'DocumentStatus' => 'bost_Open',
    ];

    $dto = InventoryCountingDto::fromResponse($response);

    expect($dto->docEntry)->toBe(2);
    expect($dto->docNum)->toBe(1002);
    expect($dto->series)->toBe(10);
    expect($dto->countingType)->toBe('ctItemCode');
    expect($dto->documentStatus)->toBe('bost_Open');
});

it('converts to array', function () {
    $dto = new InventoryCountingDto(
        docEntry: 1,
        docNum: 1001,
        docDate: '2024-01-15',
        remarks: 'Test',
        countingType: 'ctItemCode',
    );

    $array = $dto->toArray();

    expect($array['DocEntry'])->toBe(1);
    expect($array['DocNum'])->toBe(1001);
    expect($array['DocDate'])->toBe('2024-01-15');
    expect($array['Remarks'])->toBe('Test');
    expect($array['CountingType'])->toBe('ctItemCode');
});

it('handles inventory counting lines', function () {
    $data = [
        'DocEntry' => 1,
        'InventoryCountingLines' => [
            [
                'LineNum' => 0,
                'ItemCode' => 'ITEM001',
                'CountedQuantity' => 10.0,
                'InWarehouseQuantity' => 12.0,
                'Variance' => -2.0,
            ],
            [
                'LineNum' => 1,
                'ItemCode' => 'ITEM002',
                'CountedQuantity' => 5.0,
                'InWarehouseQuantity' => 5.0,
                'Variance' => 0.0,
            ],
        ],
    ];

    $dto = InventoryCountingDto::fromArray($data);

    expect($dto->inventoryCountingLines)->toHaveCount(2);
    expect($dto->inventoryCountingLines[0])->toBeInstanceOf(InventoryCountingLineDto::class);
    expect($dto->inventoryCountingLines[0]->itemCode)->toBe('ITEM001');
    expect($dto->inventoryCountingLines[0]->variance)->toBe(-2.0);
    expect($dto->inventoryCountingLines[1]->itemCode)->toBe('ITEM002');
});

it('excludes null values in toArray', function () {
    $dto = new InventoryCountingDto(
        docEntry: 1,
        docDate: '2024-01-15',
    );

    $array = $dto->toArray();

    expect($array)->toHaveKey('DocEntry');
    expect($array)->toHaveKey('DocDate');
    expect($array)->not->toHaveKey('Remarks');
    expect($array)->not->toHaveKey('CountingType');
});

it('includes lines in toArray', function () {
    $line = new InventoryCountingLineDto(
        lineNum: 0,
        itemCode: 'ITEM001',
        countedQuantity: 10.0,
        inWarehouseQuantity: 12.0,
        variance: -2.0,
    );

    $dto = new InventoryCountingDto(
        docEntry: 1,
        inventoryCountingLines: [$line],
    );

    $array = $dto->toArray();

    expect($array)->toHaveKey('InventoryCountingLines');
    expect($array['InventoryCountingLines'])->toHaveCount(1);
    expect($array['InventoryCountingLines'][0]['ItemCode'])->toBe('ITEM001');
    expect($array['InventoryCountingLines'][0]['Variance'])->toBe(-2.0);
});
