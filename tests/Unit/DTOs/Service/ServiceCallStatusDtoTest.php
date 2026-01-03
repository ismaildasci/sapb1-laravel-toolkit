<?php

declare(strict_types=1);

use SapB1\Toolkit\DTOs\Service\ServiceCallStatusDto;

it('creates from array', function () {
    $data = [
        'StatusId' => 1,
        'Name' => 'Open',
        'Description' => 'Open service call',
    ];

    $dto = ServiceCallStatusDto::fromArray($data);

    expect($dto->statusId)->toBe(1);
    expect($dto->name)->toBe('Open');
    expect($dto->description)->toBe('Open service call');
});

it('creates from response', function () {
    $response = [
        'StatusId' => 2,
        'Name' => 'Closed',
        'Description' => 'Closed service call',
    ];

    $dto = ServiceCallStatusDto::fromResponse($response);

    expect($dto->statusId)->toBe(2);
    expect($dto->name)->toBe('Closed');
});

it('converts to array', function () {
    $dto = new ServiceCallStatusDto(
        statusId: 1,
        name: 'Open',
        description: 'Open service call',
    );

    $array = $dto->toArray();

    expect($array['StatusId'])->toBe(1);
    expect($array['Name'])->toBe('Open');
    expect($array['Description'])->toBe('Open service call');
});

it('excludes null values in toArray', function () {
    $dto = new ServiceCallStatusDto(
        statusId: 1,
        name: 'Open',
    );

    $array = $dto->toArray();

    expect($array)->toHaveKey('StatusId');
    expect($array)->toHaveKey('Name');
    expect($array)->not->toHaveKey('Description');
});
