<?php

declare(strict_types=1);

use SapB1\Toolkit\DTOs\Inventory\BatchNumberDetailDto;

it('creates from array', function () {
    $data = [
        'DocEntry' => 1,
        'ItemCode' => 'ITEM001',
        'Batch' => 'BATCH001',
        'Quantity' => 100.0,
        'ExpiryDate' => '2025-12-31',
        'Location' => 'A1',
    ];

    $dto = BatchNumberDetailDto::fromArray($data);

    expect($dto->docEntry)->toBe(1);
    expect($dto->itemCode)->toBe('ITEM001');
    expect($dto->batchNumber)->toBe('BATCH001');
    expect($dto->quantity)->toBe(100.0);
    expect($dto->expiryDate)->toBe('2025-12-31');
    expect($dto->location)->toBe('A1');
});

it('creates from response', function () {
    $response = [
        'DocEntry' => 2,
        'ItemCode' => 'ITEM002',
        'Batch' => 'BATCH002',
        'Status' => 'bdsStatus_Released',
    ];

    $dto = BatchNumberDetailDto::fromResponse($response);

    expect($dto->docEntry)->toBe(2);
    expect($dto->itemCode)->toBe('ITEM002');
    expect($dto->batchNumber)->toBe('BATCH002');
    expect($dto->status)->toBe('bdsStatus_Released');
});

it('converts to array', function () {
    $dto = new BatchNumberDetailDto(
        docEntry: 1,
        itemCode: 'ITEM001',
        batchNumber: 'BATCH001',
        quantity: 50.0,
    );

    $array = $dto->toArray();

    expect($array['DocEntry'])->toBe(1);
    expect($array['ItemCode'])->toBe('ITEM001');
    expect($array['Batch'])->toBe('BATCH001');
    expect($array['Quantity'])->toBe(50.0);
});

it('excludes null values in toArray', function () {
    $dto = new BatchNumberDetailDto(
        docEntry: 1,
        itemCode: 'ITEM001',
    );

    $array = $dto->toArray();

    expect($array)->toHaveKey('DocEntry');
    expect($array)->toHaveKey('ItemCode');
    expect($array)->not->toHaveKey('Batch');
    expect($array)->not->toHaveKey('Location');
});

it('handles all standard fields', function () {
    $data = [
        'DocEntry' => 1,
        'ItemCode' => 'ITEM001',
        'ItemDescription' => 'Test Item',
        'Batch' => 'BATCH001',
        'Status' => 'bdsStatus_Released',
        'Quantity' => 100.0,
        'ManufacturerSerialNumber' => 'MSN001',
        'InternalSerialNumber' => 'ISN001',
        'ExpiryDate' => '2025-12-31',
        'ManufactureDate' => '2024-01-01',
        'AdmissionDate' => '2024-01-15',
        'Location' => 'WH01-A1',
        'Notes' => 'Test notes',
        'SystemNumber' => 12345,
        'Attribute1' => 'Attr1',
        'Attribute2' => 'Attr2',
    ];

    $dto = BatchNumberDetailDto::fromArray($data);

    expect($dto->docEntry)->toBe(1);
    expect($dto->itemCode)->toBe('ITEM001');
    expect($dto->itemDescription)->toBe('Test Item');
    expect($dto->batchNumber)->toBe('BATCH001');
    expect($dto->status)->toBe('bdsStatus_Released');
    expect($dto->quantity)->toBe(100.0);
    expect($dto->manufacturerSerialNumber)->toBe('MSN001');
    expect($dto->expiryDate)->toBe('2025-12-31');
    expect($dto->location)->toBe('WH01-A1');
    expect($dto->notes)->toBe('Test notes');
});
