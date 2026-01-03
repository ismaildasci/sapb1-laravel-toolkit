<?php

declare(strict_types=1);

use SapB1\Toolkit\DTOs\Inventory\CycleCountDeterminationDto;

it('creates from array', function () {
    $data = [
        'WarehouseCode' => 'WH01',
        'CycleCode' => 1,
        'Alert' => 'tYES',
        'DestinationUser' => 'manager',
        'NextCountingDate' => '2024-01-22',
    ];

    $dto = CycleCountDeterminationDto::fromArray($data);

    expect($dto->warehouseCode)->toBe('WH01');
    expect($dto->cycleCode)->toBe(1);
    expect($dto->alert)->toBe('tYES');
    expect($dto->destinationUser)->toBe('manager');
    expect($dto->nextCountingDate)->toBe('2024-01-22');
});

it('creates from response', function () {
    $response = [
        'WarehouseCode' => 'WH02',
        'CycleCode' => 2,
        'Time' => 9.5,
        'ExcludeItemsWithZeroQuantity' => 'tYES',
        'ChangeExistingTimeAndAlert' => 'tNO',
    ];

    $dto = CycleCountDeterminationDto::fromResponse($response);

    expect($dto->warehouseCode)->toBe('WH02');
    expect($dto->cycleCode)->toBe(2);
    expect($dto->time)->toBe(9.5);
    expect($dto->excludeItemsWithZeroQuantity)->toBe('tYES');
    expect($dto->changeExistingTimeAndAlert)->toBe('tNO');
});

it('converts to array', function () {
    $dto = new CycleCountDeterminationDto(
        warehouseCode: 'WH01',
        cycleCode: 1,
        alert: 'tYES',
        destinationUser: 'manager',
    );

    $array = $dto->toArray();

    expect($array['WarehouseCode'])->toBe('WH01');
    expect($array['CycleCode'])->toBe(1);
    expect($array['Alert'])->toBe('tYES');
    expect($array['DestinationUser'])->toBe('manager');
});

it('excludes null values in toArray', function () {
    $dto = new CycleCountDeterminationDto(
        warehouseCode: 'WH01',
        cycleCode: 1,
    );

    $array = $dto->toArray();

    expect($array)->toHaveKey('WarehouseCode');
    expect($array)->toHaveKey('CycleCode');
    expect($array)->not->toHaveKey('Alert');
    expect($array)->not->toHaveKey('DestinationUser');
    expect($array)->not->toHaveKey('Time');
});

it('handles time as float', function () {
    $dto = new CycleCountDeterminationDto(
        warehouseCode: 'WH01',
        cycleCode: 1,
        time: 14.25,
    );

    $array = $dto->toArray();

    expect($array['Time'])->toBe(14.25);
});

it('handles zero quantity exclusion setting', function () {
    $dto = new CycleCountDeterminationDto(
        warehouseCode: 'WH01',
        cycleCode: 1,
        excludeItemsWithZeroQuantity: 'tYES',
    );

    $array = $dto->toArray();

    expect($array['ExcludeItemsWithZeroQuantity'])->toBe('tYES');
});
