<?php

declare(strict_types=1);

use SapB1\Toolkit\Enums\CardType;

it('has correct values', function () {
    expect(CardType::Customer->value)->toBe('cCustomer');
    expect(CardType::Supplier->value)->toBe('cSupplier');
    expect(CardType::Lead->value)->toBe('cLid');
});

it('has correct labels', function () {
    expect(CardType::Customer->label())->toBe('Customer');
    expect(CardType::Supplier->label())->toBe('Supplier');
    expect(CardType::Lead->label())->toBe('Lead');
});

it('identifies customer type', function () {
    expect(CardType::Customer->isCustomer())->toBeTrue();
    expect(CardType::Supplier->isCustomer())->toBeFalse();
});

it('identifies supplier type', function () {
    expect(CardType::Supplier->isSupplier())->toBeTrue();
    expect(CardType::Customer->isSupplier())->toBeFalse();
});

it('identifies lead type', function () {
    expect(CardType::Lead->isLead())->toBeTrue();
    expect(CardType::Customer->isLead())->toBeFalse();
});
