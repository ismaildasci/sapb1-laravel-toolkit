<?php

declare(strict_types=1);

use SapB1\Toolkit\DTOs\Admin\SpecialPriceDto;

it('creates from array', function () {
    $data = [
        'ItemCode' => 'ITEM001',
        'CardCode' => 'C001',
        'Price' => 99.99,
        'Currency' => 'USD',
        'DiscountPercent' => 10.0,
        'ValidFrom' => '2024-01-01',
        'ValidTo' => '2024-12-31',
    ];

    $dto = SpecialPriceDto::fromArray($data);

    expect($dto->itemCode)->toBe('ITEM001');
    expect($dto->cardCode)->toBe('C001');
    expect($dto->price)->toBe(99.99);
    expect($dto->currency)->toBe('USD');
    expect($dto->discountPercent)->toBe(10.0);
});

it('converts to array', function () {
    $dto = new SpecialPriceDto(
        itemCode: 'ITEM001',
        cardCode: 'C001',
        price: 99.99,
        currency: 'USD',
    );

    $array = $dto->toArray();

    expect($array['ItemCode'])->toBe('ITEM001');
    expect($array['CardCode'])->toBe('C001');
    expect($array['Price'])->toBe(99.99);
    expect($array['Currency'])->toBe('USD');
});

it('excludes null values in toArray', function () {
    $dto = new SpecialPriceDto(
        itemCode: 'ITEM001',
        cardCode: 'C001',
    );

    $array = $dto->toArray();

    expect($array)->toHaveKey('ItemCode');
    expect($array)->toHaveKey('CardCode');
    expect($array)->not->toHaveKey('Price');
    expect($array)->not->toHaveKey('DiscountPercent');
});
