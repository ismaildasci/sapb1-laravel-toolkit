<?php

declare(strict_types=1);

use SapB1\Toolkit\Builders\BusinessPartner\SalesOpportunityBuilder;
use SapB1\Toolkit\DTOs\BusinessPartner\SalesOpportunityLineDto;
use SapB1\Toolkit\Enums\OpportunityStatus;

it('creates builder with static method', function () {
    $builder = SalesOpportunityBuilder::create();
    expect($builder)->toBeInstanceOf(SalesOpportunityBuilder::class);
});

it('sets card code and sales person', function () {
    $data = SalesOpportunityBuilder::create()
        ->cardCode('C001')
        ->salesPerson(1)
        ->build();

    expect($data['CardCode'])->toBe('C001');
    expect($data['SalesPerson'])->toBe(1);
});

it('sets contact person', function () {
    $data = SalesOpportunityBuilder::create()
        ->contactPerson(10)
        ->build();

    expect($data['ContactPerson'])->toBe(10);
});

it('sets source and interest fields', function () {
    $data = SalesOpportunityBuilder::create()
        ->source(1)
        ->interestField1(1)
        ->interestField2(2)
        ->interestField3(3)
        ->build();

    expect($data['Source'])->toBe(1);
    expect($data['InterestField1'])->toBe(1);
    expect($data['InterestField2'])->toBe(2);
    expect($data['InterestField3'])->toBe(3);
});

it('sets status', function () {
    $data = SalesOpportunityBuilder::create()
        ->status(OpportunityStatus::Open)
        ->build();

    expect($data['Status'])->toBe('sos_Open');
});

it('sets opportunity name', function () {
    $data = SalesOpportunityBuilder::create()
        ->opportunityName('Big Deal')
        ->build();

    expect($data['OpportunityName'])->toBe('Big Deal');
});

it('sets dates', function () {
    $data = SalesOpportunityBuilder::create()
        ->startDate('2024-01-01')
        ->closingDate('2024-06-30')
        ->build();

    expect($data['StartDate'])->toBe('2024-01-01');
    expect($data['ClosingDate'])->toBe('2024-06-30');
});

it('adds lines with array', function () {
    $data = SalesOpportunityBuilder::create()
        ->cardCode('C001')
        ->addLine([
            'LineNum' => 0,
            'SalesPerson' => 1,
            'StartDate' => '2024-01-01',
        ])
        ->build();

    expect($data['SalesOpportunitiesLines'])->toHaveCount(1);
    expect($data['SalesOpportunitiesLines'][0]['LineNum'])->toBe(0);
});

it('adds lines with DTO', function () {
    $line = new SalesOpportunityLineDto(
        lineNum: 0,
        salesPerson: 1,
        startDate: '2024-01-01',
    );

    $data = SalesOpportunityBuilder::create()
        ->cardCode('C001')
        ->addLine($line)
        ->build();

    expect($data['SalesOpportunitiesLines'])->toHaveCount(1);
    expect($data['SalesOpportunitiesLines'][0]['LineNum'])->toBe(0);
});

it('sets multiple lines at once', function () {
    $lines = [
        ['LineNum' => 0, 'SalesPerson' => 1],
        ['LineNum' => 1, 'SalesPerson' => 2],
    ];

    $data = SalesOpportunityBuilder::create()
        ->cardCode('C001')
        ->salesOpportunitiesLines($lines)
        ->build();

    expect($data['SalesOpportunitiesLines'])->toHaveCount(2);
});

it('chains methods fluently', function () {
    $data = SalesOpportunityBuilder::create()
        ->cardCode('C001')
        ->salesPerson(1)
        ->status(OpportunityStatus::Open)
        ->opportunityName('Big Deal')
        ->build();

    expect($data)->toHaveCount(4);
});

it('excludes null values from build', function () {
    $data = SalesOpportunityBuilder::create()
        ->cardCode('C001')
        ->salesPerson(1)
        ->build();

    expect($data)->toHaveKey('CardCode');
    expect($data)->toHaveKey('SalesPerson');
    expect($data)->not->toHaveKey('Status');
    expect($data)->not->toHaveKey('OpportunityName');
});
