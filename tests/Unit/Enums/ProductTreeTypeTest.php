<?php

declare(strict_types=1);

use SapB1\Toolkit\Enums\ProductTreeType;

it('has assembly type', function () {
    expect(ProductTreeType::Assembly->value)->toBe('iProductionTree');
    expect(ProductTreeType::Assembly->label())->toBe('Production/Assembly');
});

it('has template type', function () {
    expect(ProductTreeType::Template->value)->toBe('iTemplateTree');
    expect(ProductTreeType::Template->label())->toBe('Template');
});

it('has sales type', function () {
    expect(ProductTreeType::Sales->value)->toBe('iSalesTree');
    expect(ProductTreeType::Sales->label())->toBe('Sales');
});

it('can try from valid value', function () {
    expect(ProductTreeType::tryFrom('iProductionTree'))->toBe(ProductTreeType::Assembly);
    expect(ProductTreeType::tryFrom('iTemplateTree'))->toBe(ProductTreeType::Template);
    expect(ProductTreeType::tryFrom('iSalesTree'))->toBe(ProductTreeType::Sales);
});

it('returns null for invalid value', function () {
    expect(ProductTreeType::tryFrom('invalid'))->toBeNull();
});
