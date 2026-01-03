<?php

declare(strict_types=1);

use SapB1\Toolkit\DTOs\Inventory\InventoryTransferRequestDto;
use SapB1\Toolkit\DTOs\Inventory\InventoryTransferRequestLineDto;

it('creates from array', function () {
    $data = [
        'DocEntry' => 1,
        'DocNum' => 1001,
        'DocDate' => '2024-01-15',
        'DueDate' => '2024-01-20',
        'FromWarehouse' => 'WH01',
        'ToWarehouse' => 'WH02',
        'Comments' => 'Test transfer request',
    ];

    $dto = InventoryTransferRequestDto::fromArray($data);

    expect($dto->docEntry)->toBe(1);
    expect($dto->docNum)->toBe(1001);
    expect($dto->docDate)->toBe('2024-01-15');
    expect($dto->dueDate)->toBe('2024-01-20');
    expect($dto->fromWarehouse)->toBe('WH01');
    expect($dto->toWarehouse)->toBe('WH02');
    expect($dto->comments)->toBe('Test transfer request');
});

it('creates from response', function () {
    $response = [
        'DocEntry' => 2,
        'DocNum' => 1002,
        'Series' => 10,
        'PriceList' => '1',
    ];

    $dto = InventoryTransferRequestDto::fromResponse($response);

    expect($dto->docEntry)->toBe(2);
    expect($dto->docNum)->toBe(1002);
    expect($dto->series)->toBe(10);
    expect($dto->priceList)->toBe('1');
});

it('converts to array', function () {
    $dto = new InventoryTransferRequestDto(
        docEntry: 1,
        docNum: 1001,
        docDate: '2024-01-15',
        fromWarehouse: 'WH01',
        toWarehouse: 'WH02',
    );

    $array = $dto->toArray();

    expect($array['DocEntry'])->toBe(1);
    expect($array['DocNum'])->toBe(1001);
    expect($array['DocDate'])->toBe('2024-01-15');
    expect($array['FromWarehouse'])->toBe('WH01');
    expect($array['ToWarehouse'])->toBe('WH02');
});

it('handles stock transfer lines', function () {
    $data = [
        'DocEntry' => 1,
        'StockTransferLines' => [
            [
                'LineNum' => 0,
                'ItemCode' => 'ITEM001',
                'Quantity' => 10.0,
                'FromWarehouseCode' => 'WH01',
                'WarehouseCode' => 'WH02',
            ],
            [
                'LineNum' => 1,
                'ItemCode' => 'ITEM002',
                'Quantity' => 5.0,
                'FromWarehouseCode' => 'WH01',
                'WarehouseCode' => 'WH02',
            ],
        ],
    ];

    $dto = InventoryTransferRequestDto::fromArray($data);

    expect($dto->stockTransferLines)->toHaveCount(2);
    expect($dto->stockTransferLines[0])->toBeInstanceOf(InventoryTransferRequestLineDto::class);
    expect($dto->stockTransferLines[0]->itemCode)->toBe('ITEM001');
    expect($dto->stockTransferLines[1]->itemCode)->toBe('ITEM002');
});

it('excludes null values in toArray', function () {
    $dto = new InventoryTransferRequestDto(
        docEntry: 1,
        docDate: '2024-01-15',
    );

    $array = $dto->toArray();

    expect($array)->toHaveKey('DocEntry');
    expect($array)->toHaveKey('DocDate');
    expect($array)->not->toHaveKey('Comments');
    expect($array)->not->toHaveKey('FromWarehouse');
});

it('includes lines in toArray', function () {
    $line = new InventoryTransferRequestLineDto(
        lineNum: 0,
        itemCode: 'ITEM001',
        quantity: 10.0,
    );

    $dto = new InventoryTransferRequestDto(
        docEntry: 1,
        stockTransferLines: [$line],
    );

    $array = $dto->toArray();

    expect($array)->toHaveKey('StockTransferLines');
    expect($array['StockTransferLines'])->toHaveCount(1);
    expect($array['StockTransferLines'][0]['ItemCode'])->toBe('ITEM001');
});
