<?php

declare(strict_types=1);

use SapB1\Toolkit\DTOs\BusinessPartner\TerritoryDto;
use SapB1\Toolkit\Enums\BoYesNo;

it('creates from array', function () {
    $data = [
        'TerritoryID' => 1,
        'Description' => 'North Region',
        'LocationIndex' => 10,
        'Inactive' => 'tNO',
        'Parent' => 0,
    ];

    $dto = TerritoryDto::fromArray($data);

    expect($dto->territoryID)->toBe(1);
    expect($dto->description)->toBe('North Region');
    expect($dto->locationIndex)->toBe(10);
    expect($dto->inactive)->toBe(BoYesNo::No);
    expect($dto->parent)->toBe(0);
});

it('creates from response', function () {
    $response = [
        'TerritoryID' => 2,
        'Description' => 'South Region',
        'LocationIndex' => 20,
        'Inactive' => 'tNO',
    ];

    $dto = TerritoryDto::fromResponse($response);

    expect($dto->territoryID)->toBe(2);
    expect($dto->description)->toBe('South Region');
    expect($dto->locationIndex)->toBe(20);
});

it('converts to array', function () {
    $dto = new TerritoryDto(
        territoryID: 1,
        description: 'North Region',
        locationIndex: 10,
        inactive: BoYesNo::No,
    );

    $array = $dto->toArray();

    expect($array['TerritoryID'])->toBe(1);
    expect($array['Description'])->toBe('North Region');
    expect($array['LocationIndex'])->toBe(10);
    expect($array['Inactive'])->toBe('tNO');
});

it('excludes null values in toArray', function () {
    $dto = new TerritoryDto(
        territoryID: 1,
        description: 'North Region',
    );

    $array = $dto->toArray();

    expect($array)->toHaveKey('TerritoryID');
    expect($array)->toHaveKey('Description');
    expect($array)->not->toHaveKey('LocationIndex');
    expect($array)->not->toHaveKey('Inactive');
});

it('handles inactive territory', function () {
    $data = [
        'TerritoryID' => 1,
        'Description' => 'Inactive Region',
        'Inactive' => 'tYES',
    ];

    $dto = TerritoryDto::fromArray($data);

    expect($dto->inactive)->toBe(BoYesNo::Yes);
});

it('handles parent territory', function () {
    $data = [
        'TerritoryID' => 3,
        'Description' => 'Sub Region',
        'Parent' => 1,
    ];

    $dto = TerritoryDto::fromArray($data);

    expect($dto->parent)->toBe(1);
});
