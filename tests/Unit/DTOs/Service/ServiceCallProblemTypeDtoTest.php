<?php

declare(strict_types=1);

use SapB1\Toolkit\DTOs\Service\ServiceCallProblemTypeDto;

it('creates from array', function () {
    $data = [
        'ProblemTypeID' => 1,
        'Name' => 'Hardware',
        'Description' => 'Hardware related issue',
    ];

    $dto = ServiceCallProblemTypeDto::fromArray($data);

    expect($dto->problemTypeID)->toBe(1);
    expect($dto->name)->toBe('Hardware');
    expect($dto->description)->toBe('Hardware related issue');
});

it('creates from response', function () {
    $response = [
        'ProblemTypeID' => 2,
        'Name' => 'Software',
        'Description' => 'Software related issue',
    ];

    $dto = ServiceCallProblemTypeDto::fromResponse($response);

    expect($dto->problemTypeID)->toBe(2);
    expect($dto->name)->toBe('Software');
});

it('converts to array', function () {
    $dto = new ServiceCallProblemTypeDto(
        problemTypeID: 1,
        name: 'Hardware',
        description: 'Hardware related issue',
    );

    $array = $dto->toArray();

    expect($array['ProblemTypeID'])->toBe(1);
    expect($array['Name'])->toBe('Hardware');
    expect($array['Description'])->toBe('Hardware related issue');
});

it('excludes null values in toArray', function () {
    $dto = new ServiceCallProblemTypeDto(
        problemTypeID: 1,
        name: 'Hardware',
    );

    $array = $dto->toArray();

    expect($array)->toHaveKey('ProblemTypeID');
    expect($array)->toHaveKey('Name');
    expect($array)->not->toHaveKey('Description');
});
