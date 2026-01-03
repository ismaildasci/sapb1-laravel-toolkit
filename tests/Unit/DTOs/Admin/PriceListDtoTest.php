<?php

declare(strict_types=1);

use SapB1\Toolkit\DTOs\Admin\PriceListDto;
use SapB1\Toolkit\Enums\BoYesNo;

it('creates from array', function () {
    $data = [
        'PriceListNo' => 1,
        'PriceListName' => 'Base Price',
        'IsGrossPrice' => 'tNO',
        'Active' => 'tYES',
        'ValidFrom' => '2024-01-01',
        'ValidTo' => '2024-12-31',
        'DefaultPrimeCurrency' => 'USD',
        'Factor' => 1.0,
    ];

    $dto = PriceListDto::fromArray($data);

    expect($dto->priceListNo)->toBe(1);
    expect($dto->priceListName)->toBe('Base Price');
    expect($dto->isGrossPrice)->toBe(BoYesNo::No);
    expect($dto->active)->toBe(BoYesNo::Yes);
    expect($dto->defaultPrimeCurrency)->toBe('USD');
    expect($dto->factor)->toBe(1.0);
});

it('converts to array', function () {
    $dto = new PriceListDto(
        priceListNo: 1,
        priceListName: 'Base Price',
        active: BoYesNo::Yes,
    );

    $array = $dto->toArray();

    expect($array['PriceListNo'])->toBe(1);
    expect($array['PriceListName'])->toBe('Base Price');
    expect($array['Active'])->toBe('tYES');
});

it('can check if active', function () {
    $active = new PriceListDto(active: BoYesNo::Yes);
    $inactive = new PriceListDto(active: BoYesNo::No);

    expect($active->isActive())->toBeTrue();
    expect($inactive->isActive())->toBeFalse();
});
