<?php

declare(strict_types=1);

use SapB1\Toolkit\DTOs\Purchase\PurchaseQuotationDto;

it('creates from array', function () {
    $data = [
        'DocEntry' => 1,
        'DocNum' => 100,
        'CardCode' => 'V001',
        'CardName' => 'Test Vendor',
        'DocDate' => '2024-01-15',
        'DocTotal' => 1000.00,
        'RequiredDate' => '2024-02-01',
        'ValidUntil' => '2024-01-31',
    ];

    $dto = PurchaseQuotationDto::fromArray($data);

    expect($dto->docEntry)->toBe(1);
    expect($dto->docNum)->toBe(100);
    expect($dto->cardCode)->toBe('V001');
    expect($dto->requiredDate)->toBe('2024-02-01');
    expect($dto->validUntil)->toBe('2024-01-31');
});

it('converts to array', function () {
    $dto = new PurchaseQuotationDto(
        docEntry: 1,
        cardCode: 'V001',
        requiredDate: '2024-02-01',
        validUntil: '2024-01-31',
    );

    $data = $dto->toArray();

    expect($data['DocEntry'])->toBe(1);
    expect($data['CardCode'])->toBe('V001');
    expect($data['RequiredDate'])->toBe('2024-02-01');
    expect($data['ValidUntil'])->toBe('2024-01-31');
});

it('excludes null values in toArray', function () {
    $dto = new PurchaseQuotationDto(
        docEntry: 1,
        cardCode: 'V001',
    );

    $data = $dto->toArray();

    expect($data)->not->toHaveKey('RequiredDate');
    expect($data)->not->toHaveKey('ValidUntil');
});
