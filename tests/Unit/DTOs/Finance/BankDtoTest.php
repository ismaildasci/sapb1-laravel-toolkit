<?php

declare(strict_types=1);

use SapB1\Toolkit\DTOs\Finance\BankDto;

it('creates from array', function () {
    $data = [
        'BankCode' => 'BANK01',
        'BankName' => 'Test Bank',
        'SwiftNo' => 'TESTSWIFT',
        'CountryCode' => 'TR',
    ];

    $dto = BankDto::fromArray($data);

    expect($dto->bankCode)->toBe('BANK01');
    expect($dto->bankName)->toBe('Test Bank');
    expect($dto->swiftNo)->toBe('TESTSWIFT');
    expect($dto->countryCode)->toBe('TR');
});

it('creates from response', function () {
    $response = [
        'BankCode' => 'BANK02',
        'BankName' => 'Another Bank',
        'AccountforOutgoingChecks' => '123456',
        'BranchforOutgoingChecks' => 'MAIN',
    ];

    $dto = BankDto::fromResponse($response);

    expect($dto->bankCode)->toBe('BANK02');
    expect($dto->bankName)->toBe('Another Bank');
    expect($dto->accountforOutgoingChecks)->toBe('123456');
    expect($dto->branchforOutgoingChecks)->toBe('MAIN');
});

it('converts to array', function () {
    $dto = new BankDto(
        bankCode: 'BANK01',
        bankName: 'Test Bank',
        swiftNo: 'TESTSWIFT',
    );

    $array = $dto->toArray();

    expect($array['BankCode'])->toBe('BANK01');
    expect($array['BankName'])->toBe('Test Bank');
    expect($array['SwiftNo'])->toBe('TESTSWIFT');
});

it('excludes null values in toArray', function () {
    $dto = new BankDto(
        bankCode: 'BANK01',
        bankName: 'Test Bank',
    );

    $array = $dto->toArray();

    expect($array)->toHaveKey('BankCode');
    expect($array)->toHaveKey('BankName');
    expect($array)->not->toHaveKey('SwiftNo');
    expect($array)->not->toHaveKey('CountryCode');
});
