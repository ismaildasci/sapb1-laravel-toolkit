<?php

declare(strict_types=1);

use SapB1\Toolkit\DTOs\Service\ServiceContractDto;
use SapB1\Toolkit\DTOs\Service\ServiceContractLineDto;

it('creates from array', function () {
    $data = [
        'ContractID' => 1,
        'CustomerCode' => 'C001',
        'CustomerName' => 'Test Customer',
        'ContactCode' => 10,
        'Description' => 'Annual support contract',
        'ContractType' => 'ct_Customer',
        'RenewalType' => 'rt_Automatic',
        'Owner' => '1',
        'Status' => 'Active',
        'StartDate' => '2024-01-01',
        'EndDate' => '2024-12-31',
    ];

    $dto = ServiceContractDto::fromArray($data);

    expect($dto->contractID)->toBe(1);
    expect($dto->customerCode)->toBe('C001');
    expect($dto->customerName)->toBe('Test Customer');
    expect($dto->contractType)->toBe('ct_Customer');
    expect($dto->status)->toBe('Active');
});

it('creates from response with lines', function () {
    $response = [
        'ContractID' => 1,
        'CustomerCode' => 'C001',
        'Status' => 'Active',
        'ServiceContract_Lines' => [
            [
                'LineNum' => 0,
                'ItemCode' => 'ITEM001',
                'ItemDescription' => 'Product A',
                'ManufacturerSerialNum' => 'MSN001',
            ],
            [
                'LineNum' => 1,
                'ItemCode' => 'ITEM002',
                'ItemDescription' => 'Product B',
                'ManufacturerSerialNum' => 'MSN002',
            ],
        ],
    ];

    $dto = ServiceContractDto::fromResponse($response);

    expect($dto->contractID)->toBe(1);
    expect($dto->serviceContractLines)->toHaveCount(2);
    expect($dto->serviceContractLines[0])->toBeInstanceOf(ServiceContractLineDto::class);
    expect($dto->serviceContractLines[0]->itemCode)->toBe('ITEM001');
    expect($dto->serviceContractLines[1]->itemCode)->toBe('ITEM002');
});

it('converts to array', function () {
    $dto = new ServiceContractDto(
        contractID: 1,
        customerCode: 'C001',
        contractType: 'ct_Customer',
        status: 'Active',
        startDate: '2024-01-01',
        endDate: '2024-12-31',
    );

    $array = $dto->toArray();

    expect($array['ContractID'])->toBe(1);
    expect($array['CustomerCode'])->toBe('C001');
    expect($array['ContractType'])->toBe('ct_Customer');
    expect($array['Status'])->toBe('Active');
    expect($array['StartDate'])->toBe('2024-01-01');
    expect($array['EndDate'])->toBe('2024-12-31');
});

it('converts to array with lines', function () {
    $line = new ServiceContractLineDto(
        lineNum: 0,
        itemCode: 'ITEM001',
        itemDescription: 'Product A',
    );

    $dto = new ServiceContractDto(
        contractID: 1,
        customerCode: 'C001',
        serviceContractLines: [$line],
    );

    $array = $dto->toArray();

    expect($array)->toHaveKey('ServiceContract_Lines');
    expect($array['ServiceContract_Lines'])->toHaveCount(1);
    expect($array['ServiceContract_Lines'][0]['ItemCode'])->toBe('ITEM001');
});

it('excludes null values in toArray', function () {
    $dto = new ServiceContractDto(
        contractID: 1,
        customerCode: 'C001',
    );

    $array = $dto->toArray();

    expect($array)->toHaveKey('ContractID');
    expect($array)->toHaveKey('CustomerCode');
    expect($array)->not->toHaveKey('ContractType');
    expect($array)->not->toHaveKey('Status');
});

it('handles termination date', function () {
    $data = [
        'ContractID' => 1,
        'CustomerCode' => 'C001',
        'TerminationDate' => '2024-06-30',
    ];

    $dto = ServiceContractDto::fromArray($data);

    expect($dto->terminationDate)->toBe('2024-06-30');
});

it('handles empty lines array', function () {
    $data = [
        'ContractID' => 1,
        'CustomerCode' => 'C001',
    ];

    $dto = ServiceContractDto::fromArray($data);

    expect($dto->serviceContractLines)->toBeArray();
    expect($dto->serviceContractLines)->toBeEmpty();
});
