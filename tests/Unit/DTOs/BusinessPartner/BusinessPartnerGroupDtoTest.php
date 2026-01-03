<?php

declare(strict_types=1);

use SapB1\Toolkit\DTOs\BusinessPartner\BusinessPartnerGroupDto;
use SapB1\Toolkit\Enums\CardType;

it('creates from array', function () {
    $data = [
        'Code' => 100,
        'Name' => 'VIP Customers',
        'Type' => 'cCustomer',
    ];

    $dto = BusinessPartnerGroupDto::fromArray($data);

    expect($dto->code)->toBe(100);
    expect($dto->name)->toBe('VIP Customers');
    expect($dto->type)->toBe(CardType::Customer);
});

it('creates from response', function () {
    $response = [
        'Code' => 200,
        'Name' => 'Local Vendors',
        'Type' => 'cSupplier',
    ];

    $dto = BusinessPartnerGroupDto::fromResponse($response);

    expect($dto->code)->toBe(200);
    expect($dto->name)->toBe('Local Vendors');
    expect($dto->type)->toBe(CardType::Supplier);
});

it('converts to array', function () {
    $dto = new BusinessPartnerGroupDto(
        code: 100,
        name: 'VIP Customers',
        type: CardType::Customer,
    );

    $array = $dto->toArray();

    expect($array['Code'])->toBe(100);
    expect($array['Name'])->toBe('VIP Customers');
    expect($array['Type'])->toBe('cCustomer');
});

it('excludes null values in toArray', function () {
    $dto = new BusinessPartnerGroupDto(
        code: 100,
        name: 'Test Group',
    );

    $array = $dto->toArray();

    expect($array)->toHaveKey('Code');
    expect($array)->toHaveKey('Name');
    expect($array)->not->toHaveKey('Type');
});

it('handles lead type', function () {
    $data = [
        'Code' => 300,
        'Name' => 'Hot Leads',
        'Type' => 'cLid',
    ];

    $dto = BusinessPartnerGroupDto::fromArray($data);

    expect($dto->type)->toBe(CardType::Lead);
});

it('handles missing type gracefully', function () {
    $data = [
        'Code' => 100,
        'Name' => 'No Type Group',
    ];

    $dto = BusinessPartnerGroupDto::fromArray($data);

    expect($dto->type)->toBeNull();
});
