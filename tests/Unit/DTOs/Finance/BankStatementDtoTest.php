<?php

declare(strict_types=1);

use SapB1\Toolkit\DTOs\Finance\BankStatementDto;
use SapB1\Toolkit\DTOs\Finance\BankStatementRowDto;

it('creates from array', function () {
    $data = [
        'InternalNumber' => 1,
        'BankAccountKey' => 'ACCT01',
        'StatementNumber' => 'STM001',
        'StatementDate' => '2024-01-15',
        'Currency' => 'TRY',
    ];

    $dto = BankStatementDto::fromArray($data);

    expect($dto->internalNumber)->toBe(1);
    expect($dto->bankAccountKey)->toBe('ACCT01');
    expect($dto->statementNumber)->toBe('STM001');
    expect($dto->statementDate)->toBe('2024-01-15');
    expect($dto->currency)->toBe('TRY');
});

it('creates from response', function () {
    $response = [
        'InternalNumber' => 2,
        'Status' => 'bsst_Executed',
        'StartingBalanceF' => 1000.00,
        'EndingBalanceF' => 2500.00,
    ];

    $dto = BankStatementDto::fromResponse($response);

    expect($dto->internalNumber)->toBe(2);
    expect($dto->status)->toBe('bsst_Executed');
    expect($dto->startingBalanceF)->toBe(1000.00);
    expect($dto->endingBalanceF)->toBe(2500.00);
});

it('converts to array', function () {
    $dto = new BankStatementDto(
        internalNumber: 1,
        bankAccountKey: 'ACCT01',
        statementDate: '2024-01-15',
        currency: 'TRY',
    );

    $array = $dto->toArray();

    expect($array['InternalNumber'])->toBe(1);
    expect($array['BankAccountKey'])->toBe('ACCT01');
    expect($array['StatementDate'])->toBe('2024-01-15');
    expect($array['Currency'])->toBe('TRY');
});

it('handles bank statement rows', function () {
    $data = [
        'InternalNumber' => 1,
        'BankStatementRows' => [
            [
                'ExternalCode' => 'EXT001',
                'DueDate' => '2024-01-15',
                'DebitAmountFC' => 500.00,
            ],
            [
                'ExternalCode' => 'EXT002',
                'DueDate' => '2024-01-16',
                'CreditAmountFC' => 1000.00,
            ],
        ],
    ];

    $dto = BankStatementDto::fromArray($data);

    expect($dto->bankStatementRows)->toHaveCount(2);
    expect($dto->bankStatementRows[0])->toBeInstanceOf(BankStatementRowDto::class);
});

it('excludes null values in toArray', function () {
    $dto = new BankStatementDto(
        internalNumber: 1,
        statementDate: '2024-01-15',
    );

    $array = $dto->toArray();

    expect($array)->toHaveKey('InternalNumber');
    expect($array)->toHaveKey('StatementDate');
    expect($array)->not->toHaveKey('Currency');
    expect($array)->not->toHaveKey('Status');
});
