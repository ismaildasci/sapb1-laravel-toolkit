<?php

declare(strict_types=1);

use SapB1\Toolkit\DTOs\BusinessPartner\SalesStageDto;
use SapB1\Toolkit\Enums\BoYesNo;

it('creates from array', function () {
    $data = [
        'SequenceNo' => 1,
        'Name' => 'Initial Contact',
        'Stageno' => 1,
        'ClosingPercentage' => 10.0,
        'Cancelled' => 'tNO',
    ];

    $dto = SalesStageDto::fromArray($data);

    expect($dto->sequenceNo)->toBe(1);
    expect($dto->name)->toBe('Initial Contact');
    expect($dto->stageno)->toBe(1);
    expect($dto->closingPercentage)->toBe(10.0);
    expect($dto->cancelled)->toBe(BoYesNo::No);
});

it('creates from response', function () {
    $response = [
        'SequenceNo' => 2,
        'Name' => 'Negotiation',
        'Stageno' => 2,
        'ClosingPercentage' => 50.0,
        'Cancelled' => 'tNO',
    ];

    $dto = SalesStageDto::fromResponse($response);

    expect($dto->sequenceNo)->toBe(2);
    expect($dto->name)->toBe('Negotiation');
    expect($dto->closingPercentage)->toBe(50.0);
});

it('converts to array', function () {
    $dto = new SalesStageDto(
        sequenceNo: 1,
        name: 'Initial Contact',
        stageno: 1,
        closingPercentage: 10.0,
        cancelled: BoYesNo::No,
    );

    $array = $dto->toArray();

    expect($array['SequenceNo'])->toBe(1);
    expect($array['Name'])->toBe('Initial Contact');
    expect($array['Stageno'])->toBe(1);
    expect($array['ClosingPercentage'])->toBe(10.0);
    expect($array['Cancelled'])->toBe('tNO');
});

it('excludes null values in toArray', function () {
    $dto = new SalesStageDto(
        sequenceNo: 1,
        name: 'Initial Contact',
    );

    $array = $dto->toArray();

    expect($array)->toHaveKey('SequenceNo');
    expect($array)->toHaveKey('Name');
    expect($array)->not->toHaveKey('Stageno');
    expect($array)->not->toHaveKey('ClosingPercentage');
});

it('handles cancelled stage', function () {
    $data = [
        'SequenceNo' => 1,
        'Name' => 'Old Stage',
        'Cancelled' => 'tYES',
    ];

    $dto = SalesStageDto::fromArray($data);

    expect($dto->cancelled)->toBe(BoYesNo::Yes);
});

it('handles high closing percentage', function () {
    $data = [
        'SequenceNo' => 5,
        'Name' => 'Closing',
        'ClosingPercentage' => 90.5,
    ];

    $dto = SalesStageDto::fromArray($data);

    expect($dto->closingPercentage)->toBe(90.5);
});
