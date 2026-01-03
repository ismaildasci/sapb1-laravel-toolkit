<?php

declare(strict_types=1);

use SapB1\Toolkit\DTOs\Finance\DepositCheckDto;
use SapB1\Toolkit\DTOs\Finance\DepositCreditCardDto;
use SapB1\Toolkit\DTOs\Finance\DepositDto;

it('creates from array', function () {
    $data = [
        'AbsEntry' => 1,
        'DepositType' => 'dtChecks',
        'DepositDate' => '2024-01-15',
        'DepositCurrency' => 'TRY',
        'DepositAccount' => '1000',
    ];

    $dto = DepositDto::fromArray($data);

    expect($dto->absEntry)->toBe(1);
    expect($dto->depositType)->toBe('dtChecks');
    expect($dto->depositDate)->toBe('2024-01-15');
    expect($dto->depositCurrency)->toBe('TRY');
    expect($dto->depositAccount)->toBe('1000');
});

it('creates from response', function () {
    $response = [
        'AbsEntry' => 2,
        'TotalLC' => 5000.00,
        'TotalFC' => 5000.00,
        'Reconciled' => 'tNO',
        'Canceled' => 'tNO',
    ];

    $dto = DepositDto::fromResponse($response);

    expect($dto->absEntry)->toBe(2);
    expect($dto->totalLC)->toBe(5000.00);
    expect($dto->totalFC)->toBe(5000.00);
    expect($dto->reconciled)->toBe('tNO');
    expect($dto->canceled)->toBe('tNO');
});

it('converts to array', function () {
    $dto = new DepositDto(
        absEntry: 1,
        depositType: 'dtChecks',
        depositDate: '2024-01-15',
        totalLC: 1000.00,
    );

    $array = $dto->toArray();

    expect($array['AbsEntry'])->toBe(1);
    expect($array['DepositType'])->toBe('dtChecks');
    expect($array['DepositDate'])->toBe('2024-01-15');
    expect($array['TotalLC'])->toBe(1000.00);
});

it('handles checks', function () {
    $data = [
        'AbsEntry' => 1,
        'Checks' => [
            ['CheckKey' => 'CK001', 'CheckNumber' => 12345],
            ['CheckKey' => 'CK002', 'CheckNumber' => 12346],
        ],
    ];

    $dto = DepositDto::fromArray($data);

    expect($dto->checks)->toHaveCount(2);
    expect($dto->checks[0])->toBeInstanceOf(DepositCheckDto::class);
});

it('handles credit cards', function () {
    $data = [
        'AbsEntry' => 1,
        'CreditCards' => [
            ['CreditCardCode' => 'VISA', 'CardNumber' => '**** 1234'],
        ],
    ];

    $dto = DepositDto::fromArray($data);

    expect($dto->creditCards)->toHaveCount(1);
    expect($dto->creditCards[0])->toBeInstanceOf(DepositCreditCardDto::class);
});

it('excludes null values in toArray', function () {
    $dto = new DepositDto(
        absEntry: 1,
        depositDate: '2024-01-15',
    );

    $array = $dto->toArray();

    expect($array)->toHaveKey('AbsEntry');
    expect($array)->toHaveKey('DepositDate');
    expect($array)->not->toHaveKey('DepositType');
    expect($array)->not->toHaveKey('TotalLC');
});
