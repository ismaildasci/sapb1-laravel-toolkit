<?php

declare(strict_types=1);

use SapB1\Toolkit\DTOs\HR\DepartmentDto;

it('creates from array', function () {
    $data = [
        'Code' => 1,
        'Name' => 'IT Department',
        'Description' => 'Information Technology',
    ];

    $dto = DepartmentDto::fromArray($data);

    expect($dto->code)->toBe(1);
    expect($dto->name)->toBe('IT Department');
    expect($dto->description)->toBe('Information Technology');
});

it('converts to array', function () {
    $dto = new DepartmentDto(
        code: 1,
        name: 'IT Department',
        description: 'Information Technology',
    );

    $array = $dto->toArray();

    expect($array['Code'])->toBe(1);
    expect($array['Name'])->toBe('IT Department');
    expect($array['Description'])->toBe('Information Technology');
});

it('excludes null values in toArray', function () {
    $dto = new DepartmentDto(
        code: 1,
        name: 'IT Department',
    );

    $array = $dto->toArray();

    expect($array)->toHaveKey('Code');
    expect($array)->toHaveKey('Name');
    expect($array)->not->toHaveKey('Description');
});
