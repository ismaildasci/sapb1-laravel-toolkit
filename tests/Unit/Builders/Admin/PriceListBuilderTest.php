<?php

declare(strict_types=1);

use SapB1\Toolkit\Builders\Admin\PriceListBuilder;
use SapB1\Toolkit\Enums\BoYesNo;

it('builds price list data', function () {
    $builder = PriceListBuilder::create()
        ->priceListName('VIP Customers')
        ->active(BoYesNo::Yes)
        ->isGrossPrice(BoYesNo::No)
        ->validFrom('2024-01-01')
        ->validTo('2024-12-31')
        ->defaultPrimeCurrency('USD');

    $data = $builder->build();

    expect($data['PriceListName'])->toBe('VIP Customers');
    expect($data['Active'])->toBe('tYES');
    expect($data['IsGrossPrice'])->toBe('tNO');
    expect($data['ValidFrom'])->toBe('2024-01-01');
    expect($data['DefaultPrimeCurrency'])->toBe('USD');
});

it('builds with base price list and factor', function () {
    $builder = PriceListBuilder::create()
        ->priceListName('Discount List')
        ->basePriceList(1)
        ->factor(0.9);

    $data = $builder->build();

    expect($data['BasePriceList'])->toBe(1);
    expect($data['Factor'])->toBe(0.9);
});
