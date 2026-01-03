<?php

declare(strict_types=1);

use SapB1\Toolkit\Builders\Finance\CurrencyBuilder;

it('creates builder with static method', function () {
    $builder = CurrencyBuilder::create();
    expect($builder)->toBeInstanceOf(CurrencyBuilder::class);
});

it('sets code and name', function () {
    $data = CurrencyBuilder::create()
        ->code('USD')
        ->name('US Dollar')
        ->build();

    expect($data['Code'])->toBe('USD');
    expect($data['Name'])->toBe('US Dollar');
});

it('sets documents code', function () {
    $data = CurrencyBuilder::create()
        ->documentsCode('$')
        ->internationalDescription('United States Dollar')
        ->build();

    expect($data['DocumentsCode'])->toBe('$');
    expect($data['InternationalDescription'])->toBe('United States Dollar');
});

it('chains methods fluently', function () {
    $data = CurrencyBuilder::create()
        ->code('TRY')
        ->name('Turkish Lira')
        ->documentsCode('₺')
        ->build();

    expect($data)->toHaveCount(3);
});

it('excludes null values from build', function () {
    $data = CurrencyBuilder::create()
        ->code('EUR')
        ->build();

    expect($data)->toHaveKey('Code');
    expect($data)->not->toHaveKey('Name');
});
