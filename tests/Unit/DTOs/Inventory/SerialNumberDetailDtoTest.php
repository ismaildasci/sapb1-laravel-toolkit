<?php

declare(strict_types=1);

use SapB1\Toolkit\DTOs\Inventory\SerialNumberDetailDto;

it('creates from array', function () {
    $data = [
        'DocEntry' => 1,
        'ItemCode' => 'ITEM001',
        'SerialNumber' => 'SN001',
        'WarehouseCode' => 'WH01',
        'Status' => 'bdsStatus_Available',
    ];

    $dto = SerialNumberDetailDto::fromArray($data);

    expect($dto->docEntry)->toBe(1);
    expect($dto->itemCode)->toBe('ITEM001');
    expect($dto->serialNumber)->toBe('SN001');
    expect($dto->warehouseCode)->toBe('WH01');
    expect($dto->status)->toBe('bdsStatus_Available');
});

it('creates from response', function () {
    $response = [
        'DocEntry' => 2,
        'ItemCode' => 'ITEM002',
        'SerialNumber' => 'SN002',
        'Location' => 'A1-R1-S1',
    ];

    $dto = SerialNumberDetailDto::fromResponse($response);

    expect($dto->docEntry)->toBe(2);
    expect($dto->itemCode)->toBe('ITEM002');
    expect($dto->serialNumber)->toBe('SN002');
    expect($dto->location)->toBe('A1-R1-S1');
});

it('converts to array', function () {
    $dto = new SerialNumberDetailDto(
        docEntry: 1,
        itemCode: 'ITEM001',
        serialNumber: 'SN001',
        warehouseCode: 'WH01',
    );

    $array = $dto->toArray();

    expect($array['DocEntry'])->toBe(1);
    expect($array['ItemCode'])->toBe('ITEM001');
    expect($array['SerialNumber'])->toBe('SN001');
    expect($array['WarehouseCode'])->toBe('WH01');
});

it('excludes null values in toArray', function () {
    $dto = new SerialNumberDetailDto(
        docEntry: 1,
        itemCode: 'ITEM001',
    );

    $array = $dto->toArray();

    expect($array)->toHaveKey('DocEntry');
    expect($array)->toHaveKey('ItemCode');
    expect($array)->not->toHaveKey('SerialNumber');
    expect($array)->not->toHaveKey('WarehouseCode');
});

it('handles all standard fields', function () {
    $data = [
        'DocEntry' => 1,
        'ItemCode' => 'ITEM001',
        'ItemDescription' => 'Test Item',
        'SerialNumber' => 'SN001',
        'Status' => 'bdsStatus_Available',
        'ManufacturerSerialNumber' => 'MSN001',
        'InternalSerialNumber' => 'ISN001',
        'ExpiryDate' => '2025-12-31',
        'ManufactureDate' => '2024-01-01',
        'AdmissionDate' => '2024-01-15',
        'WarehouseCode' => 'WH01',
        'Location' => 'A1-R1-S1',
        'Notes' => 'Test notes',
        'SystemNumber' => 12345,
        'Attribute1' => 'Attr1',
        'Attribute2' => 'Attr2',
    ];

    $dto = SerialNumberDetailDto::fromArray($data);

    expect($dto->docEntry)->toBe(1);
    expect($dto->itemCode)->toBe('ITEM001');
    expect($dto->itemDescription)->toBe('Test Item');
    expect($dto->serialNumber)->toBe('SN001');
    expect($dto->status)->toBe('bdsStatus_Available');
    expect($dto->manufacturerSerialNumber)->toBe('MSN001');
    expect($dto->warehouseCode)->toBe('WH01');
    expect($dto->location)->toBe('A1-R1-S1');
    expect($dto->notes)->toBe('Test notes');
});
