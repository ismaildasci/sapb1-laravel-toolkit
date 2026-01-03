<?php

declare(strict_types=1);

use SapB1\Toolkit\DTOs\Service\ServiceCallProblemSubTypeDto;

it('creates from array', function () {
    $data = [
        'ProblemSubTypeID' => 1,
        'Name' => 'Keyboard',
        'Description' => 'Keyboard related issue',
    ];

    $dto = ServiceCallProblemSubTypeDto::fromArray($data);

    expect($dto->problemSubTypeID)->toBe(1);
    expect($dto->name)->toBe('Keyboard');
    expect($dto->description)->toBe('Keyboard related issue');
});

it('creates from response', function () {
    $response = [
        'ProblemSubTypeID' => 2,
        'Name' => 'Monitor',
        'Description' => 'Monitor related issue',
    ];

    $dto = ServiceCallProblemSubTypeDto::fromResponse($response);

    expect($dto->problemSubTypeID)->toBe(2);
    expect($dto->name)->toBe('Monitor');
});

it('converts to array', function () {
    $dto = new ServiceCallProblemSubTypeDto(
        problemSubTypeID: 1,
        name: 'Keyboard',
        description: 'Keyboard related issue',
    );

    $array = $dto->toArray();

    expect($array['ProblemSubTypeID'])->toBe(1);
    expect($array['Name'])->toBe('Keyboard');
    expect($array['Description'])->toBe('Keyboard related issue');
});

it('excludes null values in toArray', function () {
    $dto = new ServiceCallProblemSubTypeDto(
        problemSubTypeID: 1,
        name: 'Keyboard',
    );

    $array = $dto->toArray();

    expect($array)->toHaveKey('ProblemSubTypeID');
    expect($array)->toHaveKey('Name');
    expect($array)->not->toHaveKey('Description');
});
