<?php

declare(strict_types=1);

use SapB1\Toolkit\Builders\Service\ServiceCallBuilder;
use SapB1\Toolkit\Enums\ServiceCallPriority;

it('creates builder with static method', function () {
    $builder = ServiceCallBuilder::create();
    expect($builder)->toBeInstanceOf(ServiceCallBuilder::class);
});

it('sets subject', function () {
    $data = ServiceCallBuilder::create()
        ->subject('Installation Request')
        ->build();

    expect($data['Subject'])->toBe('Installation Request');
});

it('sets customer code', function () {
    $data = ServiceCallBuilder::create()
        ->customerCode('C001')
        ->build();

    expect($data['CustomerCode'])->toBe('C001');
});

it('sets contact and technician', function () {
    $data = ServiceCallBuilder::create()
        ->contactCode(10)
        ->technicianCode(5)
        ->build();

    expect($data['ContactCode'])->toBe(10);
    expect($data['TechnicianCode'])->toBe(5);
});

it('sets description', function () {
    $data = ServiceCallBuilder::create()
        ->description('Product installation needed')
        ->build();

    expect($data['Description'])->toBe('Product installation needed');
});

it('sets priority', function () {
    $data = ServiceCallBuilder::create()
        ->priority(ServiceCallPriority::High)
        ->build();

    expect($data['Priority'])->toBe('scp_High');
});

it('sets call type and problem type', function () {
    $data = ServiceCallBuilder::create()
        ->callType(1)
        ->problemType(2)
        ->problemSubType(3)
        ->build();

    expect($data['CallType'])->toBe(1);
    expect($data['ProblemType'])->toBe(2);
    expect($data['ProblemSubType'])->toBe(3);
});

it('sets assignee and origin', function () {
    $data = ServiceCallBuilder::create()
        ->assignee(2)
        ->origin(1)
        ->build();

    expect($data['Assignee'])->toBe(2);
    expect($data['Origin'])->toBe(1);
});

it('sets status', function () {
    $data = ServiceCallBuilder::create()
        ->status(1)
        ->build();

    expect($data['Status'])->toBe(1);
});

it('sets due date', function () {
    $data = ServiceCallBuilder::create()
        ->dueDate('2024-01-20')
        ->build();

    expect($data['DueDate'])->toBe('2024-01-20');
});

it('sets resolution', function () {
    $data = ServiceCallBuilder::create()
        ->resolution('Problem fixed by replacing part')
        ->build();

    expect($data['Resolution'])->toBe('Problem fixed by replacing part');
});

it('sets item information', function () {
    $data = ServiceCallBuilder::create()
        ->itemCode('ITEM001')
        ->manufacturerSerialNum('MSN123456')
        ->internalSerialNum('ISN123456')
        ->build();

    expect($data['ItemCode'])->toBe('ITEM001');
    expect($data['ManufacturerSerialNum'])->toBe('MSN123456');
    expect($data['InternalSerialNum'])->toBe('ISN123456');
});

it('chains methods fluently', function () {
    $data = ServiceCallBuilder::create()
        ->subject('Installation Request')
        ->customerCode('C001')
        ->priority(ServiceCallPriority::High)
        ->status(1)
        ->build();

    expect($data)->toHaveCount(4);
});

it('excludes null values from build', function () {
    $data = ServiceCallBuilder::create()
        ->subject('Test Call')
        ->customerCode('C001')
        ->build();

    expect($data)->toHaveKey('Subject');
    expect($data)->toHaveKey('CustomerCode');
    expect($data)->not->toHaveKey('Priority');
    expect($data)->not->toHaveKey('Status');
});
