<?php

declare(strict_types=1);

use SapB1\Toolkit\Builders\BusinessPartner\BusinessPartnerGroupBuilder;
use SapB1\Toolkit\Enums\CardType;

it('creates builder with static method', function () {
    $builder = BusinessPartnerGroupBuilder::create();
    expect($builder)->toBeInstanceOf(BusinessPartnerGroupBuilder::class);
});

it('sets code and name', function () {
    $data = BusinessPartnerGroupBuilder::create()
        ->code(100)
        ->name('VIP Customers')
        ->build();

    expect($data['Code'])->toBe(100);
    expect($data['Name'])->toBe('VIP Customers');
});

it('sets type', function () {
    $data = BusinessPartnerGroupBuilder::create()
        ->type(CardType::Customer)
        ->build();

    expect($data['Type'])->toBe('cCustomer');
});

it('chains methods fluently', function () {
    $data = BusinessPartnerGroupBuilder::create()
        ->code(100)
        ->name('VIP Customers')
        ->type(CardType::Customer)
        ->build();

    expect($data)->toHaveCount(3);
    expect($data['Code'])->toBe(100);
    expect($data['Name'])->toBe('VIP Customers');
    expect($data['Type'])->toBe('cCustomer');
});

it('resets builder data', function () {
    $builder = BusinessPartnerGroupBuilder::create()
        ->code(100)
        ->name('Test');

    $builder->reset();
    $data = $builder->build();

    expect($data)->toBeEmpty();
});

it('excludes null values from build', function () {
    $data = BusinessPartnerGroupBuilder::create()
        ->code(100)
        ->build();

    expect($data)->toHaveKey('Code');
    expect($data)->not->toHaveKey('Name');
    expect($data)->not->toHaveKey('Type');
});

it('handles vendor type', function () {
    $data = BusinessPartnerGroupBuilder::create()
        ->code(200)
        ->name('Local Vendors')
        ->type(CardType::Supplier)
        ->build();

    expect($data['Type'])->toBe('cSupplier');
});

it('handles lead type', function () {
    $data = BusinessPartnerGroupBuilder::create()
        ->code(300)
        ->name('Hot Leads')
        ->type(CardType::Lead)
        ->build();

    expect($data['Type'])->toBe('cLid');
});
