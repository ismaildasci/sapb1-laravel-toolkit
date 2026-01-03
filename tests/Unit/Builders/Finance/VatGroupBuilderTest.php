<?php

declare(strict_types=1);

use SapB1\Toolkit\Builders\Finance\VatGroupBuilder;

it('creates builder with static method', function () {
    $builder = VatGroupBuilder::create();
    expect($builder)->toBeInstanceOf(VatGroupBuilder::class);
});

it('sets code and name', function () {
    $data = VatGroupBuilder::create()
        ->code('V18')
        ->name('VAT 18%')
        ->build();

    expect($data['Code'])->toBe('V18');
    expect($data['Name'])->toBe('VAT 18%');
});

it('sets category and tax account', function () {
    $data = VatGroupBuilder::create()
        ->category('bovcOutputTax')
        ->taxAccount(3910.0)
        ->build();

    expect($data['Category'])->toBe('bovcOutputTax');
    expect($data['TaxAccount'])->toBe(3910.0);
});

it('sets vat percent', function () {
    $data = VatGroupBuilder::create()
        ->vatPercent(18.0)
        ->inactive('tNO')
        ->build();

    expect($data['VatPercent'])->toBe(18.0);
    expect($data['Inactive'])->toBe('tNO');
});

it('chains methods fluently', function () {
    $data = VatGroupBuilder::create()
        ->code('V18')
        ->name('VAT 18%')
        ->category('bovcOutputTax')
        ->build();

    expect($data)->toHaveCount(3);
});

it('excludes null values from build', function () {
    $data = VatGroupBuilder::create()
        ->code('V10')
        ->build();

    expect($data)->toHaveKey('Code');
    expect($data)->not->toHaveKey('Name');
});
