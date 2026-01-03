<?php

declare(strict_types=1);

use SapB1\Toolkit\DTOs\BusinessPartner\IndustryDto;

it('creates from array', function () {
    $data = [
        'IndustryCode' => 1,
        'IndustryDescription' => 'Manufacturing industry sector',
        'IndustryName' => 'Manufacturing',
    ];

    $dto = IndustryDto::fromArray($data);

    expect($dto->industryCode)->toBe(1);
    expect($dto->industryDescription)->toBe('Manufacturing industry sector');
    expect($dto->industryName)->toBe('Manufacturing');
});

it('creates from response', function () {
    $response = [
        'IndustryCode' => 2,
        'IndustryDescription' => 'Retail sector',
        'IndustryName' => 'Retail',
    ];

    $dto = IndustryDto::fromResponse($response);

    expect($dto->industryCode)->toBe(2);
    expect($dto->industryDescription)->toBe('Retail sector');
    expect($dto->industryName)->toBe('Retail');
});

it('converts to array', function () {
    $dto = new IndustryDto(
        industryCode: 1,
        industryDescription: 'Manufacturing industry sector',
        industryName: 'Manufacturing',
    );

    $array = $dto->toArray();

    expect($array['IndustryCode'])->toBe(1);
    expect($array['IndustryDescription'])->toBe('Manufacturing industry sector');
    expect($array['IndustryName'])->toBe('Manufacturing');
});

it('excludes null values in toArray', function () {
    $dto = new IndustryDto(
        industryCode: 1,
        industryName: 'Manufacturing',
    );

    $array = $dto->toArray();

    expect($array)->toHaveKey('IndustryCode');
    expect($array)->toHaveKey('IndustryName');
    expect($array)->not->toHaveKey('IndustryDescription');
});

it('handles missing optional fields', function () {
    $data = [
        'IndustryCode' => 1,
    ];

    $dto = IndustryDto::fromArray($data);

    expect($dto->industryCode)->toBe(1);
    expect($dto->industryDescription)->toBeNull();
    expect($dto->industryName)->toBeNull();
});
