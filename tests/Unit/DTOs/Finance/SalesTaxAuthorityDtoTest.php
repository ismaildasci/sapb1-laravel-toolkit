<?php

declare(strict_types=1);

use SapB1\Toolkit\DTOs\Finance\SalesTaxAuthorityDto;

it('creates from array', function () {
    $data = [
        'Code' => 1,
        'Name' => 'Tax Authority 1',
        'TaxAccount' => '3910',
        'UserSignature' => 'admin',
    ];

    $dto = SalesTaxAuthorityDto::fromArray($data);

    expect($dto->code)->toBe(1);
    expect($dto->name)->toBe('Tax Authority 1');
    expect($dto->taxAccount)->toBe('3910');
    expect($dto->userSignature)->toBe('admin');
});

it('creates from response', function () {
    $response = [
        'Code' => 2,
        'Name' => 'Regional Authority',
        'TaxType' => 'tt_Yes',
        'BusinessPartner' => 'BP001',
    ];

    $dto = SalesTaxAuthorityDto::fromResponse($response);

    expect($dto->code)->toBe(2);
    expect($dto->name)->toBe('Regional Authority');
    expect($dto->taxType)->toBe('tt_Yes');
    expect($dto->businessPartner)->toBe('BP001');
});

it('converts to array', function () {
    $dto = new SalesTaxAuthorityDto(
        code: 1,
        name: 'Test Authority',
        taxAccount: '3910',
    );

    $array = $dto->toArray();

    expect($array['Code'])->toBe(1);
    expect($array['Name'])->toBe('Test Authority');
    expect($array['TaxAccount'])->toBe('3910');
});

it('excludes null values in toArray', function () {
    $dto = new SalesTaxAuthorityDto(
        code: 1,
        name: 'Test',
    );

    $array = $dto->toArray();

    expect($array)->toHaveKey('Code');
    expect($array)->toHaveKey('Name');
    expect($array)->not->toHaveKey('TaxAccount');
    expect($array)->not->toHaveKey('BusinessPartner');
});
