<?php

declare(strict_types=1);

use SapB1\Toolkit\DTOs\Service\ServiceContractLineDto;

it('creates from array', function () {
    $data = [
        'LineNum' => 0,
        'ManufacturerSerialNum' => 'MSN001',
        'InternalSerialNum' => 'ISN001',
        'ItemCode' => 'ITEM001',
        'ItemDescription' => 'Product A',
        'ItemGroup' => 1,
    ];

    $dto = ServiceContractLineDto::fromArray($data);

    expect($dto->lineNum)->toBe(0);
    expect($dto->manufacturerSerialNum)->toBe('MSN001');
    expect($dto->internalSerialNum)->toBe('ISN001');
    expect($dto->itemCode)->toBe('ITEM001');
    expect($dto->itemDescription)->toBe('Product A');
    expect($dto->itemGroup)->toBe(1);
});

it('creates from response', function () {
    $response = [
        'LineNum' => 1,
        'ItemCode' => 'ITEM002',
        'ItemDescription' => 'Product B',
        'ManufacturerSerialNum' => 'MSN002',
    ];

    $dto = ServiceContractLineDto::fromResponse($response);

    expect($dto->lineNum)->toBe(1);
    expect($dto->itemCode)->toBe('ITEM002');
    expect($dto->manufacturerSerialNum)->toBe('MSN002');
});

it('converts to array', function () {
    $dto = new ServiceContractLineDto(
        lineNum: 0,
        itemCode: 'ITEM001',
        itemDescription: 'Product A',
        manufacturerSerialNum: 'MSN001',
    );

    $array = $dto->toArray();

    expect($array['LineNum'])->toBe(0);
    expect($array['ItemCode'])->toBe('ITEM001');
    expect($array['ItemDescription'])->toBe('Product A');
    expect($array['ManufacturerSerialNum'])->toBe('MSN001');
});

it('excludes null values in toArray', function () {
    $dto = new ServiceContractLineDto(
        lineNum: 0,
        itemCode: 'ITEM001',
    );

    $array = $dto->toArray();

    expect($array)->toHaveKey('LineNum');
    expect($array)->toHaveKey('ItemCode');
    expect($array)->not->toHaveKey('ItemDescription');
    expect($array)->not->toHaveKey('ManufacturerSerialNum');
});

it('handles serial numbers', function () {
    $data = [
        'LineNum' => 0,
        'ItemCode' => 'ITEM001',
        'ManufacturerSerialNum' => 'MSN123456',
        'InternalSerialNum' => 'ISN789012',
    ];

    $dto = ServiceContractLineDto::fromArray($data);

    expect($dto->manufacturerSerialNum)->toBe('MSN123456');
    expect($dto->internalSerialNum)->toBe('ISN789012');
});

it('handles minimal data', function () {
    $data = [
        'LineNum' => 0,
    ];

    $dto = ServiceContractLineDto::fromArray($data);

    expect($dto->lineNum)->toBe(0);
    expect($dto->itemCode)->toBeNull();
    expect($dto->itemDescription)->toBeNull();
});
