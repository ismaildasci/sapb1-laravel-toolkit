<?php

declare(strict_types=1);

use SapB1\Toolkit\DTOs\BusinessPartner\CampaignResponseTypeDto;
use SapB1\Toolkit\Enums\BoYesNo;

it('creates from array', function () {
    $data = [
        'ResponseType' => 'Interested',
        'ResponseTypeDescription' => 'Interested in product',
        'IsActive' => 'tYES',
    ];

    $dto = CampaignResponseTypeDto::fromArray($data);

    expect($dto->responseType)->toBe('Interested');
    expect($dto->responseTypeDescription)->toBe('Interested in product');
    expect($dto->isActive)->toBe(BoYesNo::Yes);
});

it('creates from response', function () {
    $response = [
        'ResponseType' => 'Quote',
        'ResponseTypeDescription' => 'Request for quote',
        'IsActive' => 'tYES',
    ];

    $dto = CampaignResponseTypeDto::fromResponse($response);

    expect($dto->responseType)->toBe('Quote');
    expect($dto->responseTypeDescription)->toBe('Request for quote');
    expect($dto->isActive)->toBe(BoYesNo::Yes);
});

it('converts to array', function () {
    $dto = new CampaignResponseTypeDto(
        responseType: 'Interested',
        responseTypeDescription: 'Interested in product',
        isActive: BoYesNo::Yes,
    );

    $array = $dto->toArray();

    expect($array['ResponseType'])->toBe('Interested');
    expect($array['ResponseTypeDescription'])->toBe('Interested in product');
    expect($array['IsActive'])->toBe('tYES');
});

it('excludes null values in toArray', function () {
    $dto = new CampaignResponseTypeDto(
        responseType: 'Interested',
        responseTypeDescription: 'Interested',
    );

    $array = $dto->toArray();

    expect($array)->toHaveKey('ResponseType');
    expect($array)->toHaveKey('ResponseTypeDescription');
    expect($array)->not->toHaveKey('IsActive');
});

it('handles inactive response type', function () {
    $data = [
        'ResponseType' => 'Old',
        'ResponseTypeDescription' => 'Old Response',
        'IsActive' => 'tNO',
    ];

    $dto = CampaignResponseTypeDto::fromArray($data);

    expect($dto->isActive)->toBe(BoYesNo::No);
});
