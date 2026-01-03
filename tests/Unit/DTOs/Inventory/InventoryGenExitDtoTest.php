<?php

declare(strict_types=1);

use SapB1\Toolkit\DTOs\Inventory\InventoryGenExitDto;
use SapB1\Toolkit\DTOs\Inventory\InventoryGenExitLineDto;

it('creates from array', function () {
    $data = [
        'DocEntry' => 1,
        'DocNum' => 1001,
        'DocDate' => '2024-01-15',
        'Reference1' => 'REF001',
        'Comments' => 'Test exit',
    ];

    $dto = InventoryGenExitDto::fromArray($data);

    expect($dto->docEntry)->toBe(1);
    expect($dto->docNum)->toBe(1001);
    expect($dto->docDate)->toBe('2024-01-15');
    expect($dto->reference1)->toBe('REF001');
    expect($dto->comments)->toBe('Test exit');
});

it('creates from response', function () {
    $response = [
        'DocEntry' => 2,
        'DocNum' => 1002,
        'DocTotal' => 3000.00,
        'DocCurrency' => 'USD',
    ];

    $dto = InventoryGenExitDto::fromResponse($response);

    expect($dto->docEntry)->toBe(2);
    expect($dto->docNum)->toBe(1002);
    expect($dto->docTotal)->toBe(3000.00);
    expect($dto->docCurrency)->toBe('USD');
});

it('converts to array', function () {
    $dto = new InventoryGenExitDto(
        docEntry: 1,
        docNum: 1001,
        docDate: '2024-01-15',
        comments: 'Test',
    );

    $array = $dto->toArray();

    expect($array['DocEntry'])->toBe(1);
    expect($array['DocNum'])->toBe(1001);
    expect($array['DocDate'])->toBe('2024-01-15');
    expect($array['Comments'])->toBe('Test');
});

it('handles document lines', function () {
    $data = [
        'DocEntry' => 1,
        'DocumentLines' => [
            [
                'LineNum' => 0,
                'ItemCode' => 'ITEM001',
                'Quantity' => 10.0,
                'WarehouseCode' => 'WH01',
            ],
        ],
    ];

    $dto = InventoryGenExitDto::fromArray($data);

    expect($dto->documentLines)->toHaveCount(1);
    expect($dto->documentLines[0])->toBeInstanceOf(InventoryGenExitLineDto::class);
    expect($dto->documentLines[0]->itemCode)->toBe('ITEM001');
    expect($dto->documentLines[0]->warehouseCode)->toBe('WH01');
});

it('excludes null values in toArray', function () {
    $dto = new InventoryGenExitDto(
        docEntry: 1,
        docDate: '2024-01-15',
    );

    $array = $dto->toArray();

    expect($array)->toHaveKey('DocEntry');
    expect($array)->toHaveKey('DocDate');
    expect($array)->not->toHaveKey('Comments');
    expect($array)->not->toHaveKey('Reference1');
});

it('includes lines in toArray', function () {
    $line = new InventoryGenExitLineDto(
        lineNum: 0,
        itemCode: 'ITEM001',
        quantity: 5.0,
    );

    $dto = new InventoryGenExitDto(
        docEntry: 1,
        documentLines: [$line],
    );

    $array = $dto->toArray();

    expect($array)->toHaveKey('DocumentLines');
    expect($array['DocumentLines'])->toHaveCount(1);
});
