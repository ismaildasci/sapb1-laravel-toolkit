<?php

declare(strict_types=1);

use SapB1\Toolkit\DTOs\Production\ResourceCapacityDto;

it('creates from array', function () {
    $data = [
        'Id' => 1,
        'Code' => 'RES001',
        'Warehouse' => 'WH01',
        'Date' => '2026-01-15',
        'Type' => 'rcCapacity',
        'Capacity' => 8.0,
        'Memo' => 'Daily capacity',
    ];

    $dto = ResourceCapacityDto::fromArray($data);

    expect($dto->id)->toBe(1);
    expect($dto->code)->toBe('RES001');
    expect($dto->warehouse)->toBe('WH01');
    expect($dto->date)->toBe('2026-01-15');
    expect($dto->type)->toBe('rcCapacity');
    expect($dto->capacity)->toBe(8.0);
    expect($dto->memo)->toBe('Daily capacity');
});

it('converts to array', function () {
    $dto = new ResourceCapacityDto(
        id: 1,
        code: 'RES001',
        warehouse: 'WH01',
        capacity: 8.0,
    );

    $array = $dto->toArray();

    expect($array['Id'])->toBe(1);
    expect($array['Code'])->toBe('RES001');
    expect($array['Warehouse'])->toBe('WH01');
    expect($array['Capacity'])->toBe(8.0);
});

it('excludes null values in toArray', function () {
    $dto = new ResourceCapacityDto(
        id: 1,
        code: 'RES001',
    );

    $array = $dto->toArray();

    expect($array)->toHaveKey('Id');
    expect($array)->toHaveKey('Code');
    expect($array)->not->toHaveKey('Warehouse');
    expect($array)->not->toHaveKey('Capacity');
});
