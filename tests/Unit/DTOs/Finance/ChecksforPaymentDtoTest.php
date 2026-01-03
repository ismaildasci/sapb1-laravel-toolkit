<?php

declare(strict_types=1);

use SapB1\Toolkit\DTOs\Finance\ChecksforPaymentDto;

it('creates from array', function () {
    $data = [
        'CheckKey' => 1,
        'CheckNumber' => 12345,
        'BankCode' => 'BANK01',
        'Branch' => 'MAIN',
        'AccountNumber' => '123456789',
        'CheckDate' => '2024-01-15',
    ];

    $dto = ChecksforPaymentDto::fromArray($data);

    expect($dto->checkKey)->toBe(1);
    expect($dto->checkNumber)->toBe(12345);
    expect($dto->bankCode)->toBe('BANK01');
    expect($dto->branch)->toBe('MAIN');
    expect($dto->accountNumber)->toBe('123456789');
    expect($dto->checkDate)->toBe('2024-01-15');
});

it('creates from response', function () {
    $response = [
        'CheckKey' => 2,
        'CheckAmount' => 1000.00,
        'CheckCurrency' => 'TRY',
        'CountryCode' => 90,
    ];

    $dto = ChecksforPaymentDto::fromResponse($response);

    expect($dto->checkKey)->toBe(2);
    expect($dto->checkAmount)->toBe(1000.00);
    expect($dto->checkCurrency)->toBe('TRY');
    expect($dto->countryCode)->toBe(90);
});

it('converts to array', function () {
    $dto = new ChecksforPaymentDto(
        checkKey: 1,
        checkNumber: 12345,
        checkAmount: 500.00,
    );

    $array = $dto->toArray();

    expect($array['CheckKey'])->toBe(1);
    expect($array['CheckNumber'])->toBe(12345);
    expect($array['CheckAmount'])->toBe(500.00);
});

it('excludes null values in toArray', function () {
    $dto = new ChecksforPaymentDto(
        checkKey: 1,
        checkNumber: 12345,
    );

    $array = $dto->toArray();

    expect($array)->toHaveKey('CheckKey');
    expect($array)->toHaveKey('CheckNumber');
    expect($array)->not->toHaveKey('CheckAmount');
    expect($array)->not->toHaveKey('BankCode');
});
