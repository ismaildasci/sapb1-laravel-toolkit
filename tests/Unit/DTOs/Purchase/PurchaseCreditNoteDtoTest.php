<?php

declare(strict_types=1);

use SapB1\Toolkit\DTOs\Purchase\PurchaseCreditNoteDto;

it('creates from array', function () {
    $data = [
        'DocEntry' => 1,
        'DocNum' => 100,
        'CardCode' => 'V001',
        'CardName' => 'Test Vendor',
        'DocDate' => '2024-01-15',
        'DocTotal' => 500.00,
    ];

    $dto = PurchaseCreditNoteDto::fromArray($data);

    expect($dto->docEntry)->toBe(1);
    expect($dto->docNum)->toBe(100);
    expect($dto->cardCode)->toBe('V001');
    expect($dto->docTotal)->toBe(500.00);
});

it('converts to array', function () {
    $dto = new PurchaseCreditNoteDto(
        docEntry: 1,
        cardCode: 'V001',
        docTotal: 500.00,
    );

    $data = $dto->toArray();

    expect($data['DocEntry'])->toBe(1);
    expect($data['CardCode'])->toBe('V001');
    expect($data['DocTotal'])->toBe(500.00);
});
