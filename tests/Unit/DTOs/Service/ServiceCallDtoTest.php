<?php

declare(strict_types=1);

use SapB1\Toolkit\DTOs\Service\ServiceCallDto;
use SapB1\Toolkit\Enums\ServiceCallPriority;

it('creates from array', function () {
    $data = [
        'ServiceCallID' => 1,
        'Subject' => 'Installation Request',
        'CustomerCode' => 'C001',
        'CustomerName' => 'Test Customer',
        'ContactCode' => 10,
        'Description' => 'Product installation needed',
        'TechnicianCode' => 5,
        'Priority' => 'scp_High',
        'CallType' => 1,
        'ProblemType' => 1,
        'ProblemSubType' => 1,
        'Assignee' => 2,
        'Origin' => 1,
        'Status' => 1,
        'CreationDate' => '2024-01-15',
        'DueDate' => '2024-01-20',
    ];

    $dto = ServiceCallDto::fromArray($data);

    expect($dto->serviceCallID)->toBe(1);
    expect($dto->subject)->toBe('Installation Request');
    expect($dto->customerCode)->toBe('C001');
    expect($dto->customerName)->toBe('Test Customer');
    expect($dto->priority)->toBe(ServiceCallPriority::High);
    expect($dto->status)->toBe(1);
});

it('creates from response', function () {
    $response = [
        'ServiceCallID' => 2,
        'Subject' => 'Repair Request',
        'CustomerCode' => 'C002',
        'Priority' => 'scp_Medium',
        'Status' => 1,
    ];

    $dto = ServiceCallDto::fromResponse($response);

    expect($dto->serviceCallID)->toBe(2);
    expect($dto->subject)->toBe('Repair Request');
    expect($dto->priority)->toBe(ServiceCallPriority::Medium);
});

it('converts to array', function () {
    $dto = new ServiceCallDto(
        serviceCallID: 1,
        subject: 'Installation Request',
        customerCode: 'C001',
        priority: ServiceCallPriority::High,
        status: 1,
        creationDate: '2024-01-15',
    );

    $array = $dto->toArray();

    expect($array['ServiceCallID'])->toBe(1);
    expect($array['Subject'])->toBe('Installation Request');
    expect($array['CustomerCode'])->toBe('C001');
    expect($array['Priority'])->toBe('scp_High');
    expect($array['Status'])->toBe(1);
    expect($array['CreationDate'])->toBe('2024-01-15');
});

it('excludes null values in toArray', function () {
    $dto = new ServiceCallDto(
        serviceCallID: 1,
        subject: 'Test Call',
    );

    $array = $dto->toArray();

    expect($array)->toHaveKey('ServiceCallID');
    expect($array)->toHaveKey('Subject');
    expect($array)->not->toHaveKey('CustomerCode');
    expect($array)->not->toHaveKey('Priority');
});

it('handles low priority', function () {
    $data = [
        'ServiceCallID' => 1,
        'Subject' => 'Low Priority Call',
        'Priority' => 'scp_Low',
    ];

    $dto = ServiceCallDto::fromArray($data);

    expect($dto->priority)->toBe(ServiceCallPriority::Low);
});

it('handles item information', function () {
    $data = [
        'ServiceCallID' => 1,
        'Subject' => 'Item Service',
        'ItemCode' => 'ITEM001',
        'ManufacturerSerialNum' => 'MSN123456',
        'InternalSerialNum' => 'ISN123456',
    ];

    $dto = ServiceCallDto::fromArray($data);

    expect($dto->itemCode)->toBe('ITEM001');
    expect($dto->manufacturerSerialNum)->toBe('MSN123456');
    expect($dto->internalSerialNum)->toBe('ISN123456');
});

it('handles resolution data', function () {
    $data = [
        'ServiceCallID' => 1,
        'Subject' => 'Resolved Call',
        'Resolution' => 'Problem fixed by replacing part',
        'ClosingDate' => '2024-01-20',
    ];

    $dto = ServiceCallDto::fromArray($data);

    expect($dto->resolution)->toBe('Problem fixed by replacing part');
    expect($dto->closingDate)->toBe('2024-01-20');
});

it('handles missing priority gracefully', function () {
    $data = [
        'ServiceCallID' => 1,
        'Subject' => 'No Priority',
    ];

    $dto = ServiceCallDto::fromArray($data);

    expect($dto->priority)->toBeNull();
});
