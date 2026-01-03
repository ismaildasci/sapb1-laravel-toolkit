<?php

declare(strict_types=1);

use SapB1\Toolkit\DTOs\Finance\SalesTaxCodeDto;

it('creates from array', function () {
    $data = [
        'Code' => 'ST01',
        'Name' => 'Sales Tax 8%',
        'Rate' => '8.00',
        'TaxType' => 'bovcOutputTax',
        'IsActive' => 'tYES',
    ];

    $dto = SalesTaxCodeDto::fromArray($data);

    expect($dto->code)->toBe('ST01');
    expect($dto->name)->toBe('Sales Tax 8%');
    expect($dto->rate)->toBe('8.00');
    expect($dto->taxType)->toBe('bovcOutputTax');
    expect($dto->isActive)->toBe('tYES');
});

it('creates from response', function () {
    $response = [
        'Code' => 'ST02',
        'Name' => 'Sales Tax 10%',
        'UserSignature' => 'admin',
        'Effective' => '2024-01-01',
    ];

    $dto = SalesTaxCodeDto::fromResponse($response);

    expect($dto->code)->toBe('ST02');
    expect($dto->name)->toBe('Sales Tax 10%');
    expect($dto->userSignature)->toBe('admin');
    expect($dto->effective)->toBe('2024-01-01');
});

it('converts to array', function () {
    $dto = new SalesTaxCodeDto(
        code: 'ST01',
        name: 'Test Tax',
        rate: '10.00',
    );

    $array = $dto->toArray();

    expect($array['Code'])->toBe('ST01');
    expect($array['Name'])->toBe('Test Tax');
    expect($array['Rate'])->toBe('10.00');
});

it('excludes null values in toArray', function () {
    $dto = new SalesTaxCodeDto(
        code: 'ST01',
        name: 'Test',
    );

    $array = $dto->toArray();

    expect($array)->toHaveKey('Code');
    expect($array)->toHaveKey('Name');
    expect($array)->not->toHaveKey('Rate');
    expect($array)->not->toHaveKey('TaxType');
});
