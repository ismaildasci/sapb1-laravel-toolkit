<?php

declare(strict_types=1);

use SapB1\Toolkit\Builders\Finance\PaymentTermsTypeBuilder;

it('creates builder with static method', function () {
    $builder = PaymentTermsTypeBuilder::create();
    expect($builder)->toBeInstanceOf(PaymentTermsTypeBuilder::class);
});

it('sets payment terms group name', function () {
    $data = PaymentTermsTypeBuilder::create()
        ->paymentTermsGroupName('Net 30')
        ->startFrom('plfMonthStart')
        ->build();

    expect($data['PaymentTermsGroupName'])->toBe('Net 30');
    expect($data['StartFrom'])->toBe('plfMonthStart');
});

it('sets additional days and months', function () {
    $data = PaymentTermsTypeBuilder::create()
        ->numberOfAdditionalMonths(1)
        ->numberOfAdditionalDays(0)
        ->build();

    expect($data['NumberOfAdditionalMonths'])->toBe(1);
    expect($data['NumberOfAdditionalDays'])->toBe(0);
});

it('sets price list and discount', function () {
    $data = PaymentTermsTypeBuilder::create()
        ->priceListNo(1)
        ->discountCode('DC01')
        ->build();

    expect($data['PriceListNo'])->toBe(1);
    expect($data['DiscountCode'])->toBe('DC01');
});

it('chains methods fluently', function () {
    $data = PaymentTermsTypeBuilder::create()
        ->paymentTermsGroupName('Cash')
        ->numberOfAdditionalDays(0)
        ->openReceipt('tYES')
        ->build();

    expect($data)->toHaveCount(3);
});

it('excludes null values from build', function () {
    $data = PaymentTermsTypeBuilder::create()
        ->paymentTermsGroupName('Test')
        ->build();

    expect($data)->toHaveKey('PaymentTermsGroupName');
    expect($data)->not->toHaveKey('NumberOfAdditionalMonths');
});
