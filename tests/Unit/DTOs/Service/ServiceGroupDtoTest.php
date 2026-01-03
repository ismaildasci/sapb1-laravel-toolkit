<?php

declare(strict_types=1);

use SapB1\Toolkit\DTOs\Service\ServiceGroupDto;

it('creates from array', function () {
    $data = [
        'AbsEntry' => 1,
        'ServiceGroupCode' => 'SG001',
        'Description' => 'Premium Support',
    ];

    $dto = ServiceGroupDto::fromArray($data);

    expect($dto->absEntry)->toBe(1);
    expect($dto->serviceGroupCode)->toBe('SG001');
    expect($dto->description)->toBe('Premium Support');
});

it('creates from response', function () {
    $response = [
        'AbsEntry' => 2,
        'ServiceGroupCode' => 'SG002',
        'Description' => 'Standard Support',
    ];

    $dto = ServiceGroupDto::fromResponse($response);

    expect($dto->absEntry)->toBe(2);
    expect($dto->serviceGroupCode)->toBe('SG002');
});

it('converts to array', function () {
    $dto = new ServiceGroupDto(
        absEntry: 1,
        serviceGroupCode: 'SG001',
        description: 'Premium Support',
    );

    $array = $dto->toArray();

    expect($array['AbsEntry'])->toBe(1);
    expect($array['ServiceGroupCode'])->toBe('SG001');
    expect($array['Description'])->toBe('Premium Support');
});

it('excludes null values in toArray', function () {
    $dto = new ServiceGroupDto(
        absEntry: 1,
        serviceGroupCode: 'SG001',
    );

    $array = $dto->toArray();

    expect($array)->toHaveKey('AbsEntry');
    expect($array)->toHaveKey('ServiceGroupCode');
    expect($array)->not->toHaveKey('Description');
});
