<?php

declare(strict_types=1);

use SapB1\Toolkit\DTOs\Service\ServiceCallOriginDto;

it('creates from array', function () {
    $data = [
        'OriginID' => 1,
        'Name' => 'Phone',
        'Description' => 'Phone call origin',
    ];

    $dto = ServiceCallOriginDto::fromArray($data);

    expect($dto->originID)->toBe(1);
    expect($dto->name)->toBe('Phone');
    expect($dto->description)->toBe('Phone call origin');
});

it('creates from response', function () {
    $response = [
        'OriginID' => 2,
        'Name' => 'Email',
        'Description' => 'Email origin',
    ];

    $dto = ServiceCallOriginDto::fromResponse($response);

    expect($dto->originID)->toBe(2);
    expect($dto->name)->toBe('Email');
});

it('converts to array', function () {
    $dto = new ServiceCallOriginDto(
        originID: 1,
        name: 'Phone',
        description: 'Phone call origin',
    );

    $array = $dto->toArray();

    expect($array['OriginID'])->toBe(1);
    expect($array['Name'])->toBe('Phone');
    expect($array['Description'])->toBe('Phone call origin');
});

it('excludes null values in toArray', function () {
    $dto = new ServiceCallOriginDto(
        originID: 1,
        name: 'Phone',
    );

    $array = $dto->toArray();

    expect($array)->toHaveKey('OriginID');
    expect($array)->toHaveKey('Name');
    expect($array)->not->toHaveKey('Description');
});
