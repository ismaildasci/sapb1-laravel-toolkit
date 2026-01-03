<?php

declare(strict_types=1);

use SapB1\Toolkit\DTOs\Finance\PaymentDraftDto;

it('creates from array', function () {
    $data = [
        'DocEntry' => 1,
        'DocNum' => 1001,
        'DocType' => 'rCustomer',
        'DocDate' => '2024-01-15',
        'CardCode' => 'C001',
        'CardName' => 'Test Customer',
    ];

    $dto = PaymentDraftDto::fromArray($data);

    expect($dto->docEntry)->toBe(1);
    expect($dto->docNum)->toBe(1001);
    expect($dto->docType)->toBe('rCustomer');
    expect($dto->docDate)->toBe('2024-01-15');
    expect($dto->cardCode)->toBe('C001');
    expect($dto->cardName)->toBe('Test Customer');
});

it('creates from response', function () {
    $response = [
        'DocEntry' => 2,
        'DocNum' => 1002,
        'DocTotal' => 5000.00,
        'DocCurrency' => 'TRY',
        'Status' => 'bost_Open',
    ];

    $dto = PaymentDraftDto::fromResponse($response);

    expect($dto->docEntry)->toBe(2);
    expect($dto->docNum)->toBe(1002);
    expect($dto->docTotal)->toBe(5000.00);
    expect($dto->docCurrency)->toBe('TRY');
    expect($dto->status)->toBe('bost_Open');
});

it('converts to array', function () {
    $dto = new PaymentDraftDto(
        docEntry: 1,
        docDate: '2024-01-15',
        cardCode: 'C001',
        cashSum: 1000.00,
    );

    $array = $dto->toArray();

    expect($array['DocEntry'])->toBe(1);
    expect($array['DocDate'])->toBe('2024-01-15');
    expect($array['CardCode'])->toBe('C001');
    expect($array['CashSum'])->toBe(1000.00);
});

it('handles payment amounts', function () {
    $dto = new PaymentDraftDto(
        docEntry: 1,
        cashSum: 500.00,
        checkSum: 300.00,
        transferSum: 200.00,
        docTotal: 1000.00,
    );

    $array = $dto->toArray();

    expect($array['CashSum'])->toBe(500.00);
    expect($array['CheckSum'])->toBe(300.00);
    expect($array['TransferSum'])->toBe(200.00);
    expect($array['DocTotal'])->toBe(1000.00);
});

it('excludes null values in toArray', function () {
    $dto = new PaymentDraftDto(
        docEntry: 1,
        docDate: '2024-01-15',
    );

    $array = $dto->toArray();

    expect($array)->toHaveKey('DocEntry');
    expect($array)->toHaveKey('DocDate');
    expect($array)->not->toHaveKey('CardCode');
    expect($array)->not->toHaveKey('CashSum');
});
