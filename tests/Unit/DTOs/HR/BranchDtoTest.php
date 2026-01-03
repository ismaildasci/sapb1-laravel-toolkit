<?php

declare(strict_types=1);

use SapB1\Toolkit\DTOs\HR\BranchDto;

it('creates from array', function () {
    $data = [
        'Code' => 1,
        'Name' => 'Main Branch',
        'Description' => 'Headquarters',
    ];

    $dto = BranchDto::fromArray($data);

    expect($dto->code)->toBe(1);
    expect($dto->name)->toBe('Main Branch');
    expect($dto->description)->toBe('Headquarters');
});

it('converts to array', function () {
    $dto = new BranchDto(
        code: 1,
        name: 'Main Branch',
        description: 'Headquarters',
    );

    $array = $dto->toArray();

    expect($array['Code'])->toBe(1);
    expect($array['Name'])->toBe('Main Branch');
    expect($array['Description'])->toBe('Headquarters');
});

it('excludes null values in toArray', function () {
    $dto = new BranchDto(
        code: 1,
        name: 'Main Branch',
    );

    $array = $dto->toArray();

    expect($array)->toHaveKey('Code');
    expect($array)->toHaveKey('Name');
    expect($array)->not->toHaveKey('Description');
});
