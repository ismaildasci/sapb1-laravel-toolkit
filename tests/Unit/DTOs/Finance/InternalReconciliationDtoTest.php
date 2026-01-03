<?php

declare(strict_types=1);

use SapB1\Toolkit\DTOs\Finance\InternalReconciliationDto;

it('creates from array', function () {
    $data = [
        'ReconNum' => 1,
        'ReconDate' => '2024-01-15',
        'CardOrAccount' => 'coaCard',
        'ReconciliationType' => 'rtManual',
        'ReconSum' => 5000.00,
    ];

    $dto = InternalReconciliationDto::fromArray($data);

    expect($dto->reconNum)->toBe(1);
    expect($dto->reconDate)->toBe('2024-01-15');
    expect($dto->cardOrAccount)->toBe('coaCard');
    expect($dto->reconciliationType)->toBe('rtManual');
    expect($dto->reconSum)->toBe(5000.00);
});

it('creates from response', function () {
    $response = [
        'ReconNum' => 2,
        'ReconDate' => '2024-01-20',
        'IsCanceled' => 'tNO',
        'ReconSumFC' => 150.00,
    ];

    $dto = InternalReconciliationDto::fromResponse($response);

    expect($dto->reconNum)->toBe(2);
    expect($dto->reconDate)->toBe('2024-01-20');
    expect($dto->isCanceled)->toBe('tNO');
    expect($dto->reconSumFC)->toBe(150.00);
});

it('converts to array', function () {
    $dto = new InternalReconciliationDto(
        reconNum: 1,
        reconDate: '2024-01-15',
        reconSum: 1000.00,
    );

    $array = $dto->toArray();

    expect($array['ReconNum'])->toBe(1);
    expect($array['ReconDate'])->toBe('2024-01-15');
    expect($array['ReconSum'])->toBe(1000.00);
});

it('excludes null values in toArray', function () {
    $dto = new InternalReconciliationDto(
        reconNum: 1,
        reconDate: '2024-01-15',
    );

    $array = $dto->toArray();

    expect($array)->toHaveKey('ReconNum');
    expect($array)->toHaveKey('ReconDate');
    expect($array)->not->toHaveKey('ReconSum');
    expect($array)->not->toHaveKey('CardOrAccount');
});
