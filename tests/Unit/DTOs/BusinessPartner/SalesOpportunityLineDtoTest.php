<?php

declare(strict_types=1);

use SapB1\Toolkit\DTOs\BusinessPartner\SalesOpportunityLineDto;

it('creates from array', function () {
    $data = [
        'LineNum' => 0,
        'SalesPerson' => 1,
        'StartDate' => '2024-01-01',
        'ClosingDate' => '2024-03-01',
        'StageKey' => 1,
        'PercentageRate' => 25.0,
        'MaxLocalTotal' => 10000.00,
        'DocumentType' => 'bodt_Item',
        'DocumentNumber' => 100,
    ];

    $dto = SalesOpportunityLineDto::fromArray($data);

    expect($dto->lineNum)->toBe(0);
    expect($dto->salesPerson)->toBe(1);
    expect($dto->startDate)->toBe('2024-01-01');
    expect($dto->closingDate)->toBe('2024-03-01');
    expect($dto->stageKey)->toBe(1);
    expect($dto->percentageRate)->toBe(25.0);
    expect($dto->maxLocalTotal)->toBe(10000.00);
});

it('creates from response', function () {
    $response = [
        'LineNum' => 1,
        'SalesPerson' => 2,
        'StartDate' => '2024-02-01',
        'ClosingDate' => '2024-04-01',
        'PercentageRate' => 50.0,
    ];

    $dto = SalesOpportunityLineDto::fromResponse($response);

    expect($dto->lineNum)->toBe(1);
    expect($dto->salesPerson)->toBe(2);
    expect($dto->percentageRate)->toBe(50.0);
});

it('converts to array', function () {
    $dto = new SalesOpportunityLineDto(
        lineNum: 0,
        salesPerson: 1,
        startDate: '2024-01-01',
        closingDate: '2024-03-01',
        percentageRate: 25.0,
    );

    $array = $dto->toArray();

    expect($array['LineNum'])->toBe(0);
    expect($array['SalesPerson'])->toBe(1);
    expect($array['StartDate'])->toBe('2024-01-01');
    expect($array['ClosingDate'])->toBe('2024-03-01');
    expect($array['PercentageRate'])->toBe(25.0);
});

it('excludes null values in toArray', function () {
    $dto = new SalesOpportunityLineDto(
        lineNum: 0,
        salesPerson: 1,
    );

    $array = $dto->toArray();

    expect($array)->toHaveKey('LineNum');
    expect($array)->toHaveKey('SalesPerson');
    expect($array)->not->toHaveKey('StartDate');
    expect($array)->not->toHaveKey('ClosingDate');
});

it('handles document reference', function () {
    $data = [
        'LineNum' => 0,
        'DocumentType' => 'bodt_Item',
        'DocumentNumber' => 123,
    ];

    $dto = SalesOpportunityLineDto::fromArray($data);

    expect($dto->documentType)->toBe('bodt_Item');
    expect($dto->documentNumber)->toBe(123);
});
