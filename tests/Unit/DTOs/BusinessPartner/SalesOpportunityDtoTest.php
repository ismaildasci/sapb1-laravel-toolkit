<?php

declare(strict_types=1);

use SapB1\Toolkit\DTOs\BusinessPartner\SalesOpportunityDto;
use SapB1\Toolkit\DTOs\BusinessPartner\SalesOpportunityLineDto;
use SapB1\Toolkit\Enums\OpportunityStatus;

it('creates from array', function () {
    $data = [
        'SequentialNo' => 1,
        'CardCode' => 'C001',
        'SalesPerson' => 1,
        'ContactPerson' => 10,
        'Source' => 1,
        'InterestField1' => 1,
        'InterestField2' => 2,
        'InterestField3' => 3,
        'Status' => 'sos_Open',
        'TotalAmountLocal' => 50000.00,
        'StartDate' => '2024-01-01',
        'ClosingDate' => '2024-06-30',
    ];

    $dto = SalesOpportunityDto::fromArray($data);

    expect($dto->sequentialNo)->toBe(1);
    expect($dto->cardCode)->toBe('C001');
    expect($dto->salesPerson)->toBe(1);
    expect($dto->status)->toBe(OpportunityStatus::Open);
    expect($dto->totalAmountLocal)->toBe(50000.00);
});

it('creates from response with lines', function () {
    $response = [
        'SequentialNo' => 1,
        'CardCode' => 'C001',
        'Status' => 'sos_Open',
        'SalesOpportunitiesLines' => [
            [
                'LineNum' => 0,
                'SalesPerson' => 1,
                'StartDate' => '2024-01-01',
                'ClosingDate' => '2024-03-01',
            ],
            [
                'LineNum' => 1,
                'SalesPerson' => 2,
                'StartDate' => '2024-03-01',
                'ClosingDate' => '2024-06-01',
            ],
        ],
    ];

    $dto = SalesOpportunityDto::fromResponse($response);

    expect($dto->sequentialNo)->toBe(1);
    expect($dto->salesOpportunitiesLines)->toHaveCount(2);
    expect($dto->salesOpportunitiesLines[0])->toBeInstanceOf(SalesOpportunityLineDto::class);
    expect($dto->salesOpportunitiesLines[0]->lineNum)->toBe(0);
    expect($dto->salesOpportunitiesLines[1]->lineNum)->toBe(1);
});

it('converts to array', function () {
    $dto = new SalesOpportunityDto(
        sequentialNo: 1,
        cardCode: 'C001',
        salesPerson: 1,
        status: OpportunityStatus::Open,
        totalAmountLocal: 50000.00,
    );

    $array = $dto->toArray();

    expect($array['SequentialNo'])->toBe(1);
    expect($array['CardCode'])->toBe('C001');
    expect($array['SalesPerson'])->toBe(1);
    expect($array['Status'])->toBe('sos_Open');
    expect($array['TotalAmountLocal'])->toBe(50000.00);
});

it('converts to array with lines', function () {
    $line = new SalesOpportunityLineDto(
        lineNum: 0,
        salesPerson: 1,
        startDate: '2024-01-01',
    );

    $dto = new SalesOpportunityDto(
        sequentialNo: 1,
        cardCode: 'C001',
        salesOpportunitiesLines: [$line],
    );

    $array = $dto->toArray();

    expect($array)->toHaveKey('SalesOpportunitiesLines');
    expect($array['SalesOpportunitiesLines'])->toHaveCount(1);
    expect($array['SalesOpportunitiesLines'][0]['LineNum'])->toBe(0);
});

it('excludes null values in toArray', function () {
    $dto = new SalesOpportunityDto(
        sequentialNo: 1,
        cardCode: 'C001',
    );

    $array = $dto->toArray();

    expect($array)->toHaveKey('SequentialNo');
    expect($array)->toHaveKey('CardCode');
    expect($array)->not->toHaveKey('SalesPerson');
    expect($array)->not->toHaveKey('Status');
});

it('handles won status', function () {
    $data = [
        'SequentialNo' => 1,
        'CardCode' => 'C001',
        'Status' => 'sos_Sold',
    ];

    $dto = SalesOpportunityDto::fromArray($data);

    expect($dto->status)->toBe(OpportunityStatus::Won);
});

it('handles lost status', function () {
    $data = [
        'SequentialNo' => 1,
        'CardCode' => 'C001',
        'Status' => 'sos_Lost',
    ];

    $dto = SalesOpportunityDto::fromArray($data);

    expect($dto->status)->toBe(OpportunityStatus::Lost);
});

it('handles empty lines array', function () {
    $data = [
        'SequentialNo' => 1,
        'CardCode' => 'C001',
    ];

    $dto = SalesOpportunityDto::fromArray($data);

    expect($dto->salesOpportunitiesLines)->toBeArray();
    expect($dto->salesOpportunitiesLines)->toBeEmpty();
});
