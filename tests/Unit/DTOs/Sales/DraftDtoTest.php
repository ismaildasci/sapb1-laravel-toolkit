<?php

declare(strict_types=1);

use SapB1\Toolkit\DTOs\Sales\DraftDto;
use SapB1\Toolkit\Enums\DocumentType;

it('creates from array', function () {
    $data = [
        'DocEntry' => 1,
        'DocNum' => 100,
        'CardCode' => 'C001',
        'DocTotal' => 1000.00,
        'DocObjectCode' => '17',
    ];

    $dto = DraftDto::fromArray($data);

    expect($dto->docEntry)->toBe(1);
    expect($dto->cardCode)->toBe('C001');
    expect($dto->docObjectCode)->toBe(DocumentType::SalesOrder);
});

it('converts to array', function () {
    $dto = new DraftDto(
        docEntry: 1,
        cardCode: 'C001',
        docObjectCode: DocumentType::SalesOrder,
    );

    $data = $dto->toArray();

    expect($data['DocEntry'])->toBe(1);
    expect($data['CardCode'])->toBe('C001');
    expect($data['DocObjectCode'])->toBe(17);
});

it('excludes null values in toArray', function () {
    $dto = new DraftDto(
        docEntry: 1,
        cardCode: 'C001',
    );

    $data = $dto->toArray();

    expect($data)->not->toHaveKey('DocObjectCode');
});
