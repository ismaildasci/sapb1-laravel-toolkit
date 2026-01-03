<?php

declare(strict_types=1);

use SapB1\Toolkit\DTOs\Inventory\PickListDto;
use SapB1\Toolkit\DTOs\Inventory\PickListLineDto;

it('creates from array', function () {
    $data = [
        'AbsoluteEntry' => 1,
        'Name' => 'Pick List 001',
        'OwnerCode' => 'USER01',
        'OwnerName' => 'John Doe',
        'PickDate' => '2024-01-15',
        'Remarks' => 'Urgent picking',
    ];

    $dto = PickListDto::fromArray($data);

    expect($dto->absoluteEntry)->toBe(1);
    expect($dto->name)->toBe('Pick List 001');
    expect($dto->ownerCode)->toBe('USER01');
    expect($dto->ownerName)->toBe('John Doe');
    expect($dto->pickDate)->toBe('2024-01-15');
    expect($dto->remarks)->toBe('Urgent picking');
});

it('creates from response', function () {
    $response = [
        'AbsoluteEntry' => 2,
        'Name' => 'Pick List 002',
        'Status' => 'ps_Open',
        'ObjectType' => '156',
    ];

    $dto = PickListDto::fromResponse($response);

    expect($dto->absoluteEntry)->toBe(2);
    expect($dto->name)->toBe('Pick List 002');
    expect($dto->status)->toBe('ps_Open');
    expect($dto->objectType)->toBe('156');
});

it('converts to array', function () {
    $dto = new PickListDto(
        absoluteEntry: 1,
        name: 'Test Pick List',
        pickDate: '2024-01-15',
        status: 'ps_Open',
    );

    $array = $dto->toArray();

    expect($array['AbsoluteEntry'])->toBe(1);
    expect($array['Name'])->toBe('Test Pick List');
    expect($array['PickDate'])->toBe('2024-01-15');
    expect($array['Status'])->toBe('ps_Open');
});

it('handles pick list lines', function () {
    $data = [
        'AbsoluteEntry' => 1,
        'PickListsLines' => [
            [
                'LineNumber' => 0,
                'ItemCode' => 'ITEM001',
                'ItemDescription' => 'Item 001',
                'ReleasedQuantity' => 10.0,
                'WarehouseCode' => 'WH01',
            ],
            [
                'LineNumber' => 1,
                'ItemCode' => 'ITEM002',
                'ItemDescription' => 'Item 002',
                'ReleasedQuantity' => 5.0,
                'WarehouseCode' => 'WH01',
            ],
        ],
    ];

    $dto = PickListDto::fromArray($data);

    expect($dto->pickListsLines)->toHaveCount(2);
    expect($dto->pickListsLines[0])->toBeInstanceOf(PickListLineDto::class);
    expect($dto->pickListsLines[0]->itemCode)->toBe('ITEM001');
    expect($dto->pickListsLines[1]->itemCode)->toBe('ITEM002');
});

it('excludes null values in toArray', function () {
    $dto = new PickListDto(
        absoluteEntry: 1,
        name: 'Test',
    );

    $array = $dto->toArray();

    expect($array)->toHaveKey('AbsoluteEntry');
    expect($array)->toHaveKey('Name');
    expect($array)->not->toHaveKey('Remarks');
    expect($array)->not->toHaveKey('OwnerCode');
});

it('includes lines in toArray', function () {
    $line = new PickListLineDto(
        lineNumber: 0,
        itemCode: 'ITEM001',
        releasedQuantity: 10.0,
    );

    $dto = new PickListDto(
        absoluteEntry: 1,
        pickListsLines: [$line],
    );

    $array = $dto->toArray();

    expect($array)->toHaveKey('PickListsLines');
    expect($array['PickListsLines'])->toHaveCount(1);
    expect($array['PickListsLines'][0]['ItemCode'])->toBe('ITEM001');
});
