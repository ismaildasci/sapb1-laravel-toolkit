<?php

declare(strict_types=1);

use SapB1\Toolkit\DTOs\Finance\HouseBankAccountDto;

it('creates from array', function () {
    $data = [
        'BankCode' => 'BANK01',
        'AccNo' => '123456789',
        'Branch' => 'MAIN',
        'AccountName' => 'Main Account',
        'GLAccount' => '1000',
    ];

    $dto = HouseBankAccountDto::fromArray($data);

    expect($dto->bankCode)->toBe('BANK01');
    expect($dto->accNo)->toBe('123456789');
    expect($dto->branch)->toBe('MAIN');
    expect($dto->accountName)->toBe('Main Account');
    expect($dto->glAccount)->toBe('1000');
});

it('creates from response', function () {
    $response = [
        'BankCode' => 'BANK02',
        'AccNo' => '987654321',
        'Country' => 'TR',
        'IbanOfHouseBankAccount' => 'TR000000000000000000000001',
    ];

    $dto = HouseBankAccountDto::fromResponse($response);

    expect($dto->bankCode)->toBe('BANK02');
    expect($dto->accNo)->toBe('987654321');
    expect($dto->country)->toBe('TR');
    expect($dto->ibanOfHouseBankAccount)->toBe('TR000000000000000000000001');
});

it('converts to array', function () {
    $dto = new HouseBankAccountDto(
        bankCode: 'BANK01',
        accNo: '123456789',
        accountName: 'Test Account',
    );

    $array = $dto->toArray();

    expect($array['BankCode'])->toBe('BANK01');
    expect($array['AccNo'])->toBe('123456789');
    expect($array['AccountName'])->toBe('Test Account');
});

it('excludes null values in toArray', function () {
    $dto = new HouseBankAccountDto(
        bankCode: 'BANK01',
        accNo: '123456789',
    );

    $array = $dto->toArray();

    expect($array)->toHaveKey('BankCode');
    expect($array)->toHaveKey('AccNo');
    expect($array)->not->toHaveKey('Branch');
    expect($array)->not->toHaveKey('IbanOfHouseBankAccount');
});
