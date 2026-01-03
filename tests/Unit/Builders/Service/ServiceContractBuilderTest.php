<?php

declare(strict_types=1);

use SapB1\Toolkit\Builders\Service\ServiceContractBuilder;
use SapB1\Toolkit\DTOs\Service\ServiceContractLineDto;

it('creates builder with static method', function () {
    $builder = ServiceContractBuilder::create();
    expect($builder)->toBeInstanceOf(ServiceContractBuilder::class);
});

it('sets customer code', function () {
    $data = ServiceContractBuilder::create()
        ->customerCode('C001')
        ->build();

    expect($data['CustomerCode'])->toBe('C001');
});

it('sets contact code', function () {
    $data = ServiceContractBuilder::create()
        ->contactCode(10)
        ->build();

    expect($data['ContactCode'])->toBe(10);
});

it('sets description', function () {
    $data = ServiceContractBuilder::create()
        ->description('Annual support contract')
        ->build();

    expect($data['Description'])->toBe('Annual support contract');
});

it('sets contract type', function () {
    $data = ServiceContractBuilder::create()
        ->contractType('ct_Customer')
        ->build();

    expect($data['ContractType'])->toBe('ct_Customer');
});

it('sets renewal type', function () {
    $data = ServiceContractBuilder::create()
        ->renewalType('rt_Automatic')
        ->build();

    expect($data['RenewalType'])->toBe('rt_Automatic');
});

it('sets owner', function () {
    $data = ServiceContractBuilder::create()
        ->owner('1')
        ->build();

    expect($data['Owner'])->toBe('1');
});

it('sets status', function () {
    $data = ServiceContractBuilder::create()
        ->status('Active')
        ->build();

    expect($data['Status'])->toBe('Active');
});

it('sets dates', function () {
    $data = ServiceContractBuilder::create()
        ->startDate('2024-01-01')
        ->endDate('2024-12-31')
        ->terminationDate('2024-06-30')
        ->build();

    expect($data['StartDate'])->toBe('2024-01-01');
    expect($data['EndDate'])->toBe('2024-12-31');
    expect($data['TerminationDate'])->toBe('2024-06-30');
});

it('adds line with array', function () {
    $data = ServiceContractBuilder::create()
        ->customerCode('C001')
        ->addLine([
            'LineNum' => 0,
            'ItemCode' => 'ITEM001',
            'ItemDescription' => 'Product A',
        ])
        ->build();

    expect($data['ServiceContract_Lines'])->toHaveCount(1);
    expect($data['ServiceContract_Lines'][0]['LineNum'])->toBe(0);
});

it('adds line with DTO', function () {
    $line = new ServiceContractLineDto(
        lineNum: 0,
        itemCode: 'ITEM001',
        itemDescription: 'Product A',
    );

    $data = ServiceContractBuilder::create()
        ->customerCode('C001')
        ->addLine($line)
        ->build();

    expect($data['ServiceContract_Lines'])->toHaveCount(1);
    expect($data['ServiceContract_Lines'][0]['ItemCode'])->toBe('ITEM001');
});

it('sets multiple lines at once', function () {
    $lines = [
        ['LineNum' => 0, 'ItemCode' => 'ITEM001'],
        ['LineNum' => 1, 'ItemCode' => 'ITEM002'],
    ];

    $data = ServiceContractBuilder::create()
        ->customerCode('C001')
        ->serviceContractLines($lines)
        ->build();

    expect($data['ServiceContract_Lines'])->toHaveCount(2);
});

it('chains methods fluently', function () {
    $data = ServiceContractBuilder::create()
        ->customerCode('C001')
        ->contractType('ct_Customer')
        ->status('Active')
        ->startDate('2024-01-01')
        ->build();

    expect($data)->toHaveCount(4);
});

it('excludes null values from build', function () {
    $data = ServiceContractBuilder::create()
        ->customerCode('C001')
        ->status('Active')
        ->build();

    expect($data)->toHaveKey('CustomerCode');
    expect($data)->toHaveKey('Status');
    expect($data)->not->toHaveKey('ContractType');
    expect($data)->not->toHaveKey('StartDate');
});
