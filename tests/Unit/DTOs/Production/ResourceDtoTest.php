<?php

declare(strict_types=1);

use SapB1\Toolkit\DTOs\Production\ResourceDto;
use SapB1\Toolkit\Enums\BoYesNo;
use SapB1\Toolkit\Enums\ResourceType;

it('creates from array', function () {
    $data = [
        'Code' => 'RES001',
        'VisCode' => 'RES001',
        'Name' => 'Machine A',
        'ForeignName' => 'Makine A',
        'Type' => 'rtMachine',
        'Group' => 1,
        'UnitOfMeasure' => 'Hour',
        'Cost1' => 50.0,
        'Active' => 'tYES',
        'DefaultWarehouse' => 'WH01',
        'Remarks' => 'Production machine',
    ];

    $dto = ResourceDto::fromArray($data);

    expect($dto->code)->toBe('RES001');
    expect($dto->name)->toBe('Machine A');
    expect($dto->foreignName)->toBe('Makine A');
    expect($dto->type)->toBe(ResourceType::Machine);
    expect($dto->group)->toBe(1);
    expect($dto->unitOfMeasure)->toBe('Hour');
    expect($dto->cost1)->toBe(50.0);
    expect($dto->active)->toBe(BoYesNo::Yes);
    expect($dto->defaultWarehouse)->toBe('WH01');
});

it('converts to array', function () {
    $dto = new ResourceDto(
        code: 'RES001',
        name: 'Machine A',
        type: ResourceType::Labor,
        group: 1,
        active: BoYesNo::Yes,
    );

    $array = $dto->toArray();

    expect($array['Code'])->toBe('RES001');
    expect($array['Name'])->toBe('Machine A');
    expect($array['Type'])->toBe('rtLabor');
    expect($array['Group'])->toBe(1);
    expect($array['Active'])->toBe('tYES');
});

it('excludes null values in toArray', function () {
    $dto = new ResourceDto(
        code: 'RES001',
        name: 'Machine A',
    );

    $array = $dto->toArray();

    expect($array)->toHaveKey('Code');
    expect($array)->toHaveKey('Name');
    expect($array)->not->toHaveKey('Type');
    expect($array)->not->toHaveKey('Group');
});
