<?php

declare(strict_types=1);

use SapB1\Toolkit\DTOs\Finance\WithholdingTaxCodeDto;

it('creates from array', function () {
    $data = [
        'WTCode' => 'WT01',
        'WTName' => 'Withholding Tax 20%',
        'OfficialCode' => 'OFFICIAL01',
        'Category' => 'bowtcVendorPayment',
        'WithholdingRate' => 20.0,
    ];

    $dto = WithholdingTaxCodeDto::fromArray($data);

    expect($dto->wTCode)->toBe('WT01');
    expect($dto->wTName)->toBe('Withholding Tax 20%');
    expect($dto->officialCode)->toBe('OFFICIAL01');
    expect($dto->category)->toBe('bowtcVendorPayment');
    expect($dto->withholdingRate)->toBe(20.0);
});

it('creates from response', function () {
    $response = [
        'WTCode' => 'WT02',
        'WTName' => 'Withholding Tax 15%',
        'EffectiveDateFrom' => '2024-01-01',
        'EffectiveDateTo' => '2024-12-31',
        'Inactive' => 'tNO',
    ];

    $dto = WithholdingTaxCodeDto::fromResponse($response);

    expect($dto->wTCode)->toBe('WT02');
    expect($dto->wTName)->toBe('Withholding Tax 15%');
    expect($dto->effectiveDateFrom)->toBe('2024-01-01');
    expect($dto->effectiveDateTo)->toBe('2024-12-31');
    expect($dto->inactive)->toBe('tNO');
});

it('converts to array', function () {
    $dto = new WithholdingTaxCodeDto(
        wTCode: 'WT01',
        wTName: 'Test Tax',
        withholdingRate: 15.0,
    );

    $array = $dto->toArray();

    expect($array['WTCode'])->toBe('WT01');
    expect($array['WTName'])->toBe('Test Tax');
    expect($array['WithholdingRate'])->toBe(15.0);
});

it('excludes null values in toArray', function () {
    $dto = new WithholdingTaxCodeDto(
        wTCode: 'WT01',
        wTName: 'Test',
    );

    $array = $dto->toArray();

    expect($array)->toHaveKey('WTCode');
    expect($array)->toHaveKey('WTName');
    expect($array)->not->toHaveKey('WithholdingRate');
    expect($array)->not->toHaveKey('Category');
});
