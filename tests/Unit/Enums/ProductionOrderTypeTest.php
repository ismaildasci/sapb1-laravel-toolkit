<?php

declare(strict_types=1);

use SapB1\Toolkit\Enums\ProductionOrderType;

it('has standard type', function () {
    expect(ProductionOrderType::Standard->value)->toBe('bopotStandard');
    expect(ProductionOrderType::Standard->label())->toBe('Standard');
});

it('has special type', function () {
    expect(ProductionOrderType::Special->value)->toBe('bopotSpecial');
    expect(ProductionOrderType::Special->label())->toBe('Special');
});

it('has disassembly type', function () {
    expect(ProductionOrderType::Disassembly->value)->toBe('bopotDisassembly');
    expect(ProductionOrderType::Disassembly->label())->toBe('Disassembly');
});

it('can try from valid value', function () {
    expect(ProductionOrderType::tryFrom('bopotStandard'))->toBe(ProductionOrderType::Standard);
    expect(ProductionOrderType::tryFrom('bopotSpecial'))->toBe(ProductionOrderType::Special);
    expect(ProductionOrderType::tryFrom('bopotDisassembly'))->toBe(ProductionOrderType::Disassembly);
});

it('returns null for invalid value', function () {
    expect(ProductionOrderType::tryFrom('invalid'))->toBeNull();
});
