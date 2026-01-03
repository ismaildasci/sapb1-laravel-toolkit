<?php

declare(strict_types=1);

use SapB1\Toolkit\DTOs\Finance\BankPageDto;

it('creates from array', function () {
    $data = [
        'AccountCode' => 1001,
        'Sequence' => 1,
        'AccountName' => 'Main Account',
        'Reference' => 'REF001',
        'DueDate' => '2024-01-15',
    ];

    $dto = BankPageDto::fromArray($data);

    expect($dto->accountCode)->toBe(1001);
    expect($dto->sequence)->toBe(1);
    expect($dto->accountName)->toBe('Main Account');
    expect($dto->reference)->toBe('REF001');
    expect($dto->dueDate)->toBe('2024-01-15');
});

it('creates from response', function () {
    $response = [
        'AccountCode' => 1002,
        'DebitAmount' => 1000.00,
        'CreditAmount' => 0.00,
        'ExternalCode' => 'EXT001',
    ];

    $dto = BankPageDto::fromResponse($response);

    expect($dto->accountCode)->toBe(1002);
    expect($dto->debitAmount)->toBe(1000.00);
    expect($dto->creditAmount)->toBe(0.00);
    expect($dto->externalCode)->toBe('EXT001');
});

it('converts to array', function () {
    $dto = new BankPageDto(
        accountCode: 1001,
        sequence: 1,
        accountName: 'Test Account',
    );

    $array = $dto->toArray();

    expect($array['AccountCode'])->toBe(1001);
    expect($array['Sequence'])->toBe(1);
    expect($array['AccountName'])->toBe('Test Account');
});

it('excludes null values in toArray', function () {
    $dto = new BankPageDto(
        accountCode: 1001,
        sequence: 1,
    );

    $array = $dto->toArray();

    expect($array)->toHaveKey('AccountCode');
    expect($array)->toHaveKey('Sequence');
    expect($array)->not->toHaveKey('DebitAmount');
    expect($array)->not->toHaveKey('CreditAmount');
});
