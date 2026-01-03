<?php

declare(strict_types=1);

use SapB1\Toolkit\DTOs\Inventory\StockTakingDto;
use SapB1\Toolkit\DTOs\Inventory\StockTakingLineDto;

it('creates from array', function () {
    $data = [
        'DocEntry' => 1,
        'DocNum' => 1001,
        'StockTakingDate' => '2024-01-15',
        'Remarks' => 'Annual stock taking',
        'Reference1' => 'REF001',
    ];

    $dto = StockTakingDto::fromArray($data);

    expect($dto->docEntry)->toBe(1);
    expect($dto->docNum)->toBe(1001);
    expect($dto->stockTakingDate)->toBe('2024-01-15');
    expect($dto->remarks)->toBe('Annual stock taking');
    expect($dto->reference1)->toBe('REF001');
});

it('creates from response', function () {
    $response = [
        'DocEntry' => 2,
        'DocNum' => 1002,
        'Series' => 10,
        'DocumentStatus' => 'bost_Open',
    ];

    $dto = StockTakingDto::fromResponse($response);

    expect($dto->docEntry)->toBe(2);
    expect($dto->docNum)->toBe(1002);
    expect($dto->series)->toBe(10);
    expect($dto->documentStatus)->toBe('bost_Open');
});

it('converts to array', function () {
    $dto = new StockTakingDto(
        docEntry: 1,
        docNum: 1001,
        stockTakingDate: '2024-01-15',
        remarks: 'Test',
    );

    $array = $dto->toArray();

    expect($array['DocEntry'])->toBe(1);
    expect($array['DocNum'])->toBe(1001);
    expect($array['StockTakingDate'])->toBe('2024-01-15');
    expect($array['Remarks'])->toBe('Test');
});

it('handles stock taking lines', function () {
    $data = [
        'DocEntry' => 1,
        'StockTakingLines' => [
            [
                'LineNum' => 0,
                'ItemCode' => 'ITEM001',
                'CountedQuantity' => 100.0,
                'WarehouseCode' => 'WH01',
            ],
            [
                'LineNum' => 1,
                'ItemCode' => 'ITEM002',
                'CountedQuantity' => 50.0,
                'WarehouseCode' => 'WH01',
            ],
        ],
    ];

    $dto = StockTakingDto::fromArray($data);

    expect($dto->stockTakingLines)->toHaveCount(2);
    expect($dto->stockTakingLines[0])->toBeInstanceOf(StockTakingLineDto::class);
    expect($dto->stockTakingLines[0]->itemCode)->toBe('ITEM001');
    expect($dto->stockTakingLines[0]->countedQuantity)->toBe(100.0);
    expect($dto->stockTakingLines[1]->itemCode)->toBe('ITEM002');
});

it('excludes null values in toArray', function () {
    $dto = new StockTakingDto(
        docEntry: 1,
        stockTakingDate: '2024-01-15',
    );

    $array = $dto->toArray();

    expect($array)->toHaveKey('DocEntry');
    expect($array)->toHaveKey('StockTakingDate');
    expect($array)->not->toHaveKey('Remarks');
    expect($array)->not->toHaveKey('Reference1');
});

it('includes lines in toArray', function () {
    $line = new StockTakingLineDto(
        lineNum: 0,
        itemCode: 'ITEM001',
        countedQuantity: 100.0,
    );

    $dto = new StockTakingDto(
        docEntry: 1,
        stockTakingLines: [$line],
    );

    $array = $dto->toArray();

    expect($array)->toHaveKey('StockTakingLines');
    expect($array['StockTakingLines'])->toHaveCount(1);
    expect($array['StockTakingLines'][0]['ItemCode'])->toBe('ITEM001');
});
