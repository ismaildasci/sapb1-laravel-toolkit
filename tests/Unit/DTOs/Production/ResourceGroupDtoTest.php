<?php

declare(strict_types=1);

use SapB1\Toolkit\DTOs\Production\ResourceGroupDto;
use SapB1\Toolkit\Enums\ResourceType;

it('creates from array', function () {
    $data = [
        'Code' => 1,
        'Name' => 'Machines',
        'Type' => 'rtMachine',
        'CostName1' => 50.0,
        'CostName2' => 25.0,
        'NumOfUnitsText' => 10,
    ];

    $dto = ResourceGroupDto::fromArray($data);

    expect($dto->code)->toBe(1);
    expect($dto->name)->toBe('Machines');
    expect($dto->type)->toBe(ResourceType::Machine);
    expect($dto->costName1)->toBe(50.0);
    expect($dto->costName2)->toBe(25.0);
    expect($dto->numOfUnitsText)->toBe(10);
});

it('converts to array', function () {
    $dto = new ResourceGroupDto(
        code: 1,
        name: 'Labor Force',
        type: ResourceType::Labor,
        costName1: 30.0,
    );

    $array = $dto->toArray();

    expect($array['Code'])->toBe(1);
    expect($array['Name'])->toBe('Labor Force');
    expect($array['Type'])->toBe('rtLabor');
    expect($array['CostName1'])->toBe(30.0);
});

it('excludes null values in toArray', function () {
    $dto = new ResourceGroupDto(
        code: 1,
        name: 'Machines',
    );

    $array = $dto->toArray();

    expect($array)->toHaveKey('Code');
    expect($array)->toHaveKey('Name');
    expect($array)->not->toHaveKey('Type');
    expect($array)->not->toHaveKey('CostName1');
});
