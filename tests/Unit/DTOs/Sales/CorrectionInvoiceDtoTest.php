<?php

declare(strict_types=1);

use SapB1\Toolkit\DTOs\Sales\CorrectionInvoiceDto;
use SapB1\Toolkit\Enums\CorrectionInvoiceItem;

it('creates from array', function () {
    $data = [
        'DocEntry' => 1,
        'DocNum' => 100,
        'CardCode' => 'C001',
        'DocTotal' => 1000.00,
        'CorrectionInvoiceItem' => 'ciis_ShouldBe',
        'CorrectedDocEntry' => 50,
    ];

    $dto = CorrectionInvoiceDto::fromArray($data);

    expect($dto->docEntry)->toBe(1);
    expect($dto->cardCode)->toBe('C001');
    expect($dto->correctionInvoiceItem)->toBe(CorrectionInvoiceItem::ShouldBe);
    expect($dto->correctedDocEntry)->toBe(50);
});

it('converts to array', function () {
    $dto = new CorrectionInvoiceDto(
        docEntry: 1,
        cardCode: 'C001',
        correctionInvoiceItem: CorrectionInvoiceItem::ShouldBe,
        correctedDocEntry: 50,
    );

    $data = $dto->toArray();

    expect($data['DocEntry'])->toBe(1);
    expect($data['CorrectionInvoiceItem'])->toBe('ciis_ShouldBe');
    expect($data['CorrectedDocEntry'])->toBe(50);
});

it('handles Was correction type', function () {
    $data = [
        'DocEntry' => 1,
        'CorrectionInvoiceItem' => 'ciis_Was',
    ];

    $dto = CorrectionInvoiceDto::fromArray($data);

    expect($dto->correctionInvoiceItem)->toBe(CorrectionInvoiceItem::Was);
});
