<?php

declare(strict_types=1);

use SapB1\Toolkit\Builders\Finance\CreditCardBuilder;

it('creates builder with static method', function () {
    $builder = CreditCardBuilder::create();
    expect($builder)->toBeInstanceOf(CreditCardBuilder::class);
});

it('sets credit card name', function () {
    $data = CreditCardBuilder::create()
        ->creditCardName('Visa')
        ->build();

    expect($data['CreditCardName'])->toBe('Visa');
});

it('sets GL account', function () {
    $data = CreditCardBuilder::create()
        ->gLAccount('1000')
        ->telephone('1234567890')
        ->build();

    expect($data['GLAccount'])->toBe('1000');
    expect($data['Telephone'])->toBe('1234567890');
});

it('sets company info', function () {
    $data = CreditCardBuilder::create()
        ->companyId('COMP01')
        ->countryCode('TR')
        ->build();

    expect($data['CompanyId'])->toBe('COMP01');
    expect($data['CountryCode'])->toBe('TR');
});

it('chains methods fluently', function () {
    $data = CreditCardBuilder::create()
        ->creditCardName('Visa')
        ->gLAccount('1000')
        ->telephone('123')
        ->build();

    expect($data)->toHaveCount(3);
});

it('excludes null values from build', function () {
    $data = CreditCardBuilder::create()
        ->creditCardName('Visa')
        ->build();

    expect($data)->toHaveKey('CreditCardName');
    expect($data)->not->toHaveKey('GLAccount');
});
