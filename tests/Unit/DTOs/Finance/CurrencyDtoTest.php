<?php

declare(strict_types=1);

use SapB1\Toolkit\DTOs\Finance\CurrencyDto;

it('creates from array', function () {
    $data = [
        'Code' => 'USD',
        'Name' => 'US Dollar',
        'DocumentsCode' => '$',
        'InternationalDescription' => 'United States Dollar',
    ];

    $dto = CurrencyDto::fromArray($data);

    expect($dto->code)->toBe('USD');
    expect($dto->name)->toBe('US Dollar');
    expect($dto->documentsCode)->toBe('$');
    expect($dto->internationalDescription)->toBe('United States Dollar');
});

it('creates from response', function () {
    $response = [
        'Code' => 'EUR',
        'Name' => 'Euro',
        'Decimals' => '2',
        'RoundingInPayment' => 2,
    ];

    $dto = CurrencyDto::fromResponse($response);

    expect($dto->code)->toBe('EUR');
    expect($dto->name)->toBe('Euro');
    expect($dto->decimals)->toBe('2');
    expect($dto->roundingInPayment)->toBe(2);
});

it('converts to array', function () {
    $dto = new CurrencyDto(
        code: 'TRY',
        name: 'Turkish Lira',
        documentsCode: '₺',
    );

    $array = $dto->toArray();

    expect($array['Code'])->toBe('TRY');
    expect($array['Name'])->toBe('Turkish Lira');
    expect($array['DocumentsCode'])->toBe('₺');
});

it('handles amount differences', function () {
    $dto = new CurrencyDto(
        code: 'USD',
        maxIncomingAmtDiff: 5,
        maxOutgoingAmtDiff: 5,
        maxIncomingAmtDiffPercent: 1,
        maxOutgoingAmtDiffPercent: 1,
    );

    $array = $dto->toArray();

    expect($array['MaxIncomingAmtDiff'])->toBe(5);
    expect($array['MaxOutgoingAmtDiff'])->toBe(5);
    expect($array['MaxIncomingAmtDiffPercent'])->toBe(1);
    expect($array['MaxOutgoingAmtDiffPercent'])->toBe(1);
});

it('excludes null values in toArray', function () {
    $dto = new CurrencyDto(
        code: 'USD',
        name: 'US Dollar',
    );

    $array = $dto->toArray();

    expect($array)->toHaveKey('Code');
    expect($array)->toHaveKey('Name');
    expect($array)->not->toHaveKey('Decimals');
    expect($array)->not->toHaveKey('Rounding');
});
