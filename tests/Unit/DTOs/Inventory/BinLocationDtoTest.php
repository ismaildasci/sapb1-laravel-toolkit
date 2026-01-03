<?php

declare(strict_types=1);

use SapB1\Toolkit\DTOs\Inventory\BinLocationDto;
use SapB1\Toolkit\Enums\BoYesNo;

it('creates from array', function () {
    $data = [
        'AbsEntry' => 1,
        'BinCode' => 'WH01-A01-R01-S01',
        'Warehouse' => 'WH01',
        'MinimumQty' => 10.0,
        'MaximumQty' => 100.0,
        'Inactive' => 'tNO',
        'Description' => 'Shelf 1',
    ];

    $dto = BinLocationDto::fromArray($data);

    expect($dto->absEntry)->toBe(1);
    expect($dto->binCode)->toBe('WH01-A01-R01-S01');
    expect($dto->warehouseCode)->toBe('WH01');
    expect($dto->minimumQty)->toBe(10.0);
    expect($dto->maximumQty)->toBe(100.0);
    expect($dto->inactive)->toBe(BoYesNo::No);
    expect($dto->description)->toBe('Shelf 1');
});

it('creates from response', function () {
    $response = [
        'AbsEntry' => 2,
        'BinCode' => 'WH01-A02',
        'Warehouse' => 'WH01',
        'SL1Code' => 1,
        'SL2Code' => 2,
    ];

    $dto = BinLocationDto::fromResponse($response);

    expect($dto->absEntry)->toBe(2);
    expect($dto->binCode)->toBe('WH01-A02');
    expect($dto->warehouseCode)->toBe('WH01');
    expect($dto->sublevelOne)->toBe(1);
    expect($dto->sublevelTwo)->toBe(2);
});

it('converts to array', function () {
    $dto = new BinLocationDto(
        absEntry: 1,
        binCode: 'WH01-A01',
        warehouseCode: 'WH01',
        minimumQty: 5.0,
        inactive: BoYesNo::No,
    );

    $array = $dto->toArray();

    expect($array['AbsEntry'])->toBe(1);
    expect($array['BinCode'])->toBe('WH01-A01');
    expect($array['Warehouse'])->toBe('WH01');
    expect($array['MinimumQty'])->toBe(5.0);
    expect($array['Inactive'])->toBe('tNO');
});

it('excludes null values in toArray', function () {
    $dto = new BinLocationDto(
        absEntry: 1,
        binCode: 'WH01-A01',
    );

    $array = $dto->toArray();

    expect($array)->toHaveKey('AbsEntry');
    expect($array)->toHaveKey('BinCode');
    expect($array)->not->toHaveKey('Warehouse');
    expect($array)->not->toHaveKey('Description');
});

it('maps sublevel codes', function () {
    $data = [
        'AbsEntry' => 1,
        'BinCode' => 'WH01-A01-R01-S01-L01',
        'SL1Code' => 1,
        'SL2Code' => 2,
        'SL3Code' => 3,
        'SL4Code' => 4,
    ];

    $dto = BinLocationDto::fromArray($data);

    expect($dto->sublevelOne)->toBe(1);
    expect($dto->sublevelTwo)->toBe(2);
    expect($dto->sublevelThree)->toBe(3);
    expect($dto->sublevelFour)->toBe(4);
});

it('maps attribute values', function () {
    $data = [
        'AbsEntry' => 1,
        'BinCode' => 'WH01-A01',
        'Attr1Val' => 10,
        'Attr2Val' => 20,
        'Attr3Val' => 30,
    ];

    $dto = BinLocationDto::fromArray($data);

    expect($dto->attribute1)->toBe(10);
    expect($dto->attribute2)->toBe(20);
    expect($dto->attribute3)->toBe(30);
});

it('handles all standard fields', function () {
    $data = [
        'AbsEntry' => 1,
        'BinCode' => 'WH01-A01',
        'Warehouse' => 'WH01',
        'MinimumQty' => 5.0,
        'MaximumQty' => 500.0,
        'Description' => 'Main storage bin',
        'AlternativeSortCode' => 'ALT001',
        'BarCode' => '1234567890',
        'ReceivingBinLocation' => 'tYES',
        'ExcludeAutoAllocOnIssue' => 'tNO',
    ];

    $dto = BinLocationDto::fromArray($data);

    expect($dto->absEntry)->toBe(1);
    expect($dto->binCode)->toBe('WH01-A01');
    expect($dto->warehouseCode)->toBe('WH01');
    expect($dto->minimumQty)->toBe(5.0);
    expect($dto->maximumQty)->toBe(500.0);
    expect($dto->description)->toBe('Main storage bin');
    expect($dto->alternativeSortCode)->toBe('ALT001');
    expect($dto->barCode)->toBe('1234567890');
    expect($dto->receivingBinLocation)->toBe(BoYesNo::Yes);
    expect($dto->excludeAutoAllocOnIssue)->toBe(BoYesNo::No);
});
