<?php

declare(strict_types=1);

use SapB1\Toolkit\DTOs\Purchase\PurchaseRequestDto;

it('creates from array', function () {
    $data = [
        'DocEntry' => 1,
        'DocNum' => 100,
        'DocDate' => '2024-01-15',
        'DocTotal' => 1000.00,
        'Requester' => 5,
        'RequesterName' => 'John Doe',
        'RequesterEmail' => 'john@example.com',
        'RequesterBranch' => 1,
        'RequesterDepartment' => 2,
    ];

    $dto = PurchaseRequestDto::fromArray($data);

    expect($dto->docEntry)->toBe(1);
    expect($dto->requester)->toBe(5);
    expect($dto->requesterName)->toBe('John Doe');
    expect($dto->requesterEmail)->toBe('john@example.com');
    expect($dto->requesterBranch)->toBe(1);
    expect($dto->requesterDepartment)->toBe(2);
});

it('converts to array', function () {
    $dto = new PurchaseRequestDto(
        docEntry: 1,
        requester: 5,
        requesterName: 'John Doe',
        requesterEmail: 'john@example.com',
        requesterBranch: 1,
        requesterDepartment: 2,
    );

    $data = $dto->toArray();

    expect($data['DocEntry'])->toBe(1);
    expect($data['Requester'])->toBe(5);
    expect($data['RequesterName'])->toBe('John Doe');
    expect($data['RequesterEmail'])->toBe('john@example.com');
    expect($data['RequesterBranch'])->toBe(1);
    expect($data['RequesterDepartment'])->toBe(2);
});

it('excludes null values in toArray', function () {
    $dto = new PurchaseRequestDto(
        docEntry: 1,
    );

    $data = $dto->toArray();

    expect($data)->not->toHaveKey('Requester');
    expect($data)->not->toHaveKey('RequesterName');
});
