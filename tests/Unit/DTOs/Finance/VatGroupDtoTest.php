<?php

declare(strict_types=1);

use SapB1\Toolkit\DTOs\Finance\VatGroupDto;

it('creates from array', function () {
    $data = [
        'Code' => 'V18',
        'Name' => 'VAT 18%',
        'Category' => 'bovcOutputTax',
        'TaxAccount' => 3910,
        'VatPercent' => 18.0,
    ];

    $dto = VatGroupDto::fromArray($data);

    expect($dto->code)->toBe('V18');
    expect($dto->name)->toBe('VAT 18%');
    expect($dto->category)->toBe('bovcOutputTax');
    expect($dto->taxAccount)->toBe(3910.0);
    expect($dto->vatPercent)->toBe(18.0);
});

it('creates from response', function () {
    $response = [
        'Code' => 'V10',
        'Name' => 'VAT 10%',
        'Inactive' => 'tNO',
        'IsDefault' => 'tYES',
    ];

    $dto = VatGroupDto::fromResponse($response);

    expect($dto->code)->toBe('V10');
    expect($dto->name)->toBe('VAT 10%');
    expect($dto->inactive)->toBe('tNO');
    expect($dto->isDefault)->toBe('tYES');
});

it('converts to array', function () {
    $dto = new VatGroupDto(
        code: 'V20',
        name: 'VAT 20%',
        category: 'bovcOutputTax',
    );

    $array = $dto->toArray();

    expect($array['Code'])->toBe('V20');
    expect($array['Name'])->toBe('VAT 20%');
    expect($array['Category'])->toBe('bovcOutputTax');
});

it('excludes null values in toArray', function () {
    $dto = new VatGroupDto(
        code: 'V18',
        name: 'VAT 18%',
    );

    $array = $dto->toArray();

    expect($array)->toHaveKey('Code');
    expect($array)->toHaveKey('Name');
    expect($array)->not->toHaveKey('Category');
    expect($array)->not->toHaveKey('TaxAccount');
});
