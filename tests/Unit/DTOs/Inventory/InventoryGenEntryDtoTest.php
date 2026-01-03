<?php

declare(strict_types=1);

use SapB1\Toolkit\DTOs\Inventory\InventoryGenEntryDto;
use SapB1\Toolkit\DTOs\Inventory\InventoryGenEntryLineDto;

it('creates from array', function () {
    $data = [
        'DocEntry' => 1,
        'DocNum' => 1001,
        'DocDate' => '2024-01-15',
        'Reference1' => 'REF001',
        'Comments' => 'Test entry',
    ];

    $dto = InventoryGenEntryDto::fromArray($data);

    expect($dto->docEntry)->toBe(1);
    expect($dto->docNum)->toBe(1001);
    expect($dto->docDate)->toBe('2024-01-15');
    expect($dto->reference1)->toBe('REF001');
    expect($dto->comments)->toBe('Test entry');
});

it('creates from response', function () {
    $response = [
        'DocEntry' => 2,
        'DocNum' => 1002,
        'DocTotal' => 5000.00,
        'DocCurrency' => 'TRY',
    ];

    $dto = InventoryGenEntryDto::fromResponse($response);

    expect($dto->docEntry)->toBe(2);
    expect($dto->docNum)->toBe(1002);
    expect($dto->docTotal)->toBe(5000.00);
    expect($dto->docCurrency)->toBe('TRY');
});

it('converts to array', function () {
    $dto = new InventoryGenEntryDto(
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
            [
                'LineNum' => 1,
                'ItemCode' => 'ITEM002',
                'Quantity' => 5.0,
                'WarehouseCode' => 'WH01',
            ],
        ],
    ];

    $dto = InventoryGenEntryDto::fromArray($data);

    expect($dto->documentLines)->toHaveCount(2);
    expect($dto->documentLines[0])->toBeInstanceOf(InventoryGenEntryLineDto::class);
    expect($dto->documentLines[0]->itemCode)->toBe('ITEM001');
    expect($dto->documentLines[1]->itemCode)->toBe('ITEM002');
});

it('excludes null values in toArray', function () {
    $dto = new InventoryGenEntryDto(
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
    $line = new InventoryGenEntryLineDto(
        lineNum: 0,
        itemCode: 'ITEM001',
        quantity: 10.0,
    );

    $dto = new InventoryGenEntryDto(
        docEntry: 1,
        documentLines: [$line],
    );

    $array = $dto->toArray();

    expect($array)->toHaveKey('DocumentLines');
    expect($array['DocumentLines'])->toHaveCount(1);
    expect($array['DocumentLines'][0]['ItemCode'])->toBe('ITEM001');
});
