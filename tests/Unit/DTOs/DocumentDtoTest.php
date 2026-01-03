<?php

declare(strict_types=1);

use SapB1\Toolkit\DTOs\DocumentDto;
use SapB1\Toolkit\Enums\DocumentStatus;

it('creates from array', function () {
    $data = [
        'DocEntry' => 1,
        'DocNum' => 1001,
        'CardCode' => 'C001',
        'CardName' => 'Test Customer',
        'DocDate' => '2024-01-15',
        'DocTotal' => 1000.00,
        'DocumentStatus' => 'bost_Open',
    ];

    $dto = DocumentDto::fromArray($data);

    expect($dto->docEntry)->toBe(1);
    expect($dto->docNum)->toBe(1001);
    expect($dto->cardCode)->toBe('C001');
    expect($dto->cardName)->toBe('Test Customer');
    expect($dto->docDate)->toBe('2024-01-15');
    expect($dto->docTotal)->toBe(1000.00);
    expect($dto->documentStatus)->toBe(DocumentStatus::Open);
});

it('creates from response', function () {
    $response = [
        'DocEntry' => 2,
        'DocNum' => 1002,
        'CardCode' => 'C002',
        'VatSum' => 180.00,
    ];

    $dto = DocumentDto::fromResponse($response);

    expect($dto->docEntry)->toBe(2);
    expect($dto->docNum)->toBe(1002);
    expect($dto->cardCode)->toBe('C002');
    expect($dto->vatSum)->toBe(180.00);
});

it('converts to array', function () {
    $dto = new DocumentDto(
        docEntry: 1,
        docNum: 1001,
        cardCode: 'C001',
        docTotal: 1000.00,
        documentStatus: DocumentStatus::Open,
    );

    $array = $dto->toArray();

    expect($array['DocEntry'])->toBe(1);
    expect($array['DocNum'])->toBe(1001);
    expect($array['CardCode'])->toBe('C001');
    expect($array['DocTotal'])->toBe(1000.00);
    expect($array['DocumentStatus'])->toBe('bost_Open');
});

it('excludes null values in toArray', function () {
    $dto = new DocumentDto(
        docEntry: 1,
        cardCode: 'C001',
    );

    $array = $dto->toArray();

    expect($array)->toHaveKey('DocEntry');
    expect($array)->toHaveKey('CardCode');
    expect($array)->not->toHaveKey('DocNum');
    expect($array)->not->toHaveKey('DocTotal');
});

it('handles document lines', function () {
    $data = [
        'DocEntry' => 1,
        'CardCode' => 'C001',
        'DocumentLines' => [
            [
                'LineNum' => 0,
                'ItemCode' => 'ITEM001',
                'Quantity' => 10.0,
                'UnitPrice' => 100.00,
            ],
        ],
    ];

    $dto = DocumentDto::fromArray($data);

    expect($dto->documentLines)->toHaveCount(1);
    expect($dto->documentLines[0]->itemCode)->toBe('ITEM001');
    expect($dto->documentLines[0]->quantity)->toBe(10.0);
});

it('maps all standard fields', function () {
    $data = [
        'DocEntry' => 1,
        'DocNum' => 1001,
        'CardCode' => 'C001',
        'CardName' => 'Test Customer',
        'DocDate' => '2024-01-15',
        'DocDueDate' => '2024-02-15',
        'TaxDate' => '2024-01-15',
        'DocCurrency' => 'TRY',
        'DocRate' => 1.0,
        'DocTotal' => 1180.00,
        'DocTotalFc' => 1180.00,
        'VatSum' => 180.00,
        'VatSumFc' => 180.00,
        'DiscountPercent' => 5.0,
        'TotalDiscount' => 50.00,
        'NumAtCard' => 'PO-001',
        'Comments' => 'Test comment',
        'PayToCode' => 'BILL',
        'ShipToCode' => 'SHIP',
        'SalesPersonCode' => 1,
        'ContactPersonCode' => 2,
        'Series' => 10,
        'Indicator' => 'IND',
        'FederalTaxID' => '12345',
        'Project' => 'PRJ001',
    ];

    $dto = DocumentDto::fromArray($data);

    expect($dto->docEntry)->toBe(1);
    expect($dto->docDueDate)->toBe('2024-02-15');
    expect($dto->docCurrency)->toBe('TRY');
    expect($dto->discountPercent)->toBe(5.0);
    expect($dto->numAtCard)->toBe('PO-001');
    expect($dto->comments)->toBe('Test comment');
    expect($dto->payToCode)->toBe('BILL');
    expect($dto->shipToCode)->toBe('SHIP');
    expect($dto->project)->toBe('PRJ001');
});
