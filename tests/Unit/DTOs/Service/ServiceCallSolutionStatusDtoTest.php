<?php

declare(strict_types=1);

use SapB1\Toolkit\DTOs\Service\ServiceCallSolutionStatusDto;

it('creates from array', function () {
    $data = [
        'StatusId' => 1,
        'Name' => 'Pending',
        'Description' => 'Solution pending',
    ];

    $dto = ServiceCallSolutionStatusDto::fromArray($data);

    expect($dto->statusId)->toBe(1);
    expect($dto->name)->toBe('Pending');
    expect($dto->description)->toBe('Solution pending');
});

it('creates from response', function () {
    $response = [
        'StatusId' => 2,
        'Name' => 'Resolved',
        'Description' => 'Solution resolved',
    ];

    $dto = ServiceCallSolutionStatusDto::fromResponse($response);

    expect($dto->statusId)->toBe(2);
    expect($dto->name)->toBe('Resolved');
});

it('converts to array', function () {
    $dto = new ServiceCallSolutionStatusDto(
        statusId: 1,
        name: 'Pending',
        description: 'Solution pending',
    );

    $array = $dto->toArray();

    expect($array['StatusId'])->toBe(1);
    expect($array['Name'])->toBe('Pending');
    expect($array['Description'])->toBe('Solution pending');
});

it('excludes null values in toArray', function () {
    $dto = new ServiceCallSolutionStatusDto(
        statusId: 1,
        name: 'Pending',
    );

    $array = $dto->toArray();

    expect($array)->toHaveKey('StatusId');
    expect($array)->toHaveKey('Name');
    expect($array)->not->toHaveKey('Description');
});
