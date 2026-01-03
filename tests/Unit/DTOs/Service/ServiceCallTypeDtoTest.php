<?php

declare(strict_types=1);

use SapB1\Toolkit\DTOs\Service\ServiceCallTypeDto;

it('creates from array', function () {
    $data = [
        'CallTypeID' => 1,
        'Name' => 'Installation',
        'Description' => 'Product installation service',
    ];

    $dto = ServiceCallTypeDto::fromArray($data);

    expect($dto->callTypeID)->toBe(1);
    expect($dto->name)->toBe('Installation');
    expect($dto->description)->toBe('Product installation service');
});

it('creates from response', function () {
    $response = [
        'CallTypeID' => 2,
        'Name' => 'Repair',
        'Description' => 'Product repair service',
    ];

    $dto = ServiceCallTypeDto::fromResponse($response);

    expect($dto->callTypeID)->toBe(2);
    expect($dto->name)->toBe('Repair');
});

it('converts to array', function () {
    $dto = new ServiceCallTypeDto(
        callTypeID: 1,
        name: 'Installation',
        description: 'Product installation service',
    );

    $array = $dto->toArray();

    expect($array['CallTypeID'])->toBe(1);
    expect($array['Name'])->toBe('Installation');
    expect($array['Description'])->toBe('Product installation service');
});

it('excludes null values in toArray', function () {
    $dto = new ServiceCallTypeDto(
        callTypeID: 1,
        name: 'Installation',
    );

    $array = $dto->toArray();

    expect($array)->toHaveKey('CallTypeID');
    expect($array)->toHaveKey('Name');
    expect($array)->not->toHaveKey('Description');
});
