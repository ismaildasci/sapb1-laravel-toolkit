<?php

declare(strict_types=1);

use SapB1\Toolkit\Builders\Sales\DownPaymentBuilder;
use SapB1\Toolkit\Enums\DownPaymentType;

it('creates builder with static method', function () {
    $builder = DownPaymentBuilder::create();

    expect($builder)->toBeInstanceOf(DownPaymentBuilder::class);
});

it('sets down payment type', function () {
    $builder = DownPaymentBuilder::create()
        ->downPaymentType(DownPaymentType::Request);

    $data = $builder->build();

    expect($data['DownPaymentType'])->toBe('dpt_Request');
});

it('sets as request type', function () {
    $builder = DownPaymentBuilder::create()
        ->asRequest();

    $data = $builder->build();

    expect($data['DownPaymentType'])->toBe('dpt_Request');
});

it('sets as invoice type', function () {
    $builder = DownPaymentBuilder::create()
        ->asInvoice();

    $data = $builder->build();

    expect($data['DownPaymentType'])->toBe('dpt_Invoice');
});

it('sets down payment amount', function () {
    $builder = DownPaymentBuilder::create()
        ->downPaymentAmount(5000.00);

    $data = $builder->build();

    expect($data['DownPaymentAmount'])->toBe(5000.00);
});

it('sets down payment amount fc', function () {
    $builder = DownPaymentBuilder::create()
        ->downPaymentAmountFc(5000.00);

    $data = $builder->build();

    expect($data['DownPaymentAmountFC'])->toBe(5000.00);
});

it('sets down payment percentage', function () {
    $builder = DownPaymentBuilder::create()
        ->downPaymentPercentage(50.0);

    $data = $builder->build();

    expect($data['DownPaymentPercentage'])->toBe(50.0);
});

it('sets payment method', function () {
    $builder = DownPaymentBuilder::create()
        ->paymentMethod('Cash');

    $data = $builder->build();

    expect($data['PaymentMethod'])->toBe('Cash');
});

it('sets payment group code', function () {
    $builder = DownPaymentBuilder::create()
        ->paymentGroupCode(1);

    $data = $builder->build();

    expect($data['PaymentGroupCode'])->toBe(1);
});

it('chains all methods fluently', function () {
    $data = DownPaymentBuilder::create()
        ->cardCode('C001')
        ->docDate('2024-01-15')
        ->asRequest()
        ->downPaymentAmount(5000.00)
        ->downPaymentPercentage(50.0)
        ->comments('Down payment for order')
        ->build();

    expect($data['CardCode'])->toBe('C001');
    expect($data['DocDate'])->toBe('2024-01-15');
    expect($data['DownPaymentType'])->toBe('dpt_Request');
    expect($data['DownPaymentAmount'])->toBe(5000.00);
    expect($data['DownPaymentPercentage'])->toBe(50.0);
    expect($data['Comments'])->toBe('Down payment for order');
});
