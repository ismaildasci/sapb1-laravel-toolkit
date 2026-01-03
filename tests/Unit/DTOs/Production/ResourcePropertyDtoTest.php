<?php

declare(strict_types=1);

use SapB1\Toolkit\DTOs\Production\ResourcePropertyDto;

it('creates from array', function () {
    $data = [
        'Code' => 1,
        'Name' => 'Speed',
    ];

    $dto = ResourcePropertyDto::fromArray($data);

    expect($dto->code)->toBe(1);
    expect($dto->name)->toBe('Speed');
});

it('creates from response', function () {
    $response = [
        'Code' => 2,
        'Name' => 'Capacity',
    ];

    $dto = ResourcePropertyDto::fromResponse($response);

    expect($dto->code)->toBe(2);
    expect($dto->name)->toBe('Capacity');
});

it('converts to array', function () {
    $dto = new ResourcePropertyDto(
        code: 1,
        name: 'Speed',
    );

    $array = $dto->toArray();

    expect($array['Code'])->toBe(1);
    expect($array['Name'])->toBe('Speed');
});

it('excludes null values in toArray', function () {
    $dto = new ResourcePropertyDto(
        code: 1,
    );

    $array = $dto->toArray();

    expect($array)->toHaveKey('Code');
    expect($array)->not->toHaveKey('Name');
});
