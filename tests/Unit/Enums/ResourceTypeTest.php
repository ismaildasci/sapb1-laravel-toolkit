<?php

declare(strict_types=1);

use SapB1\Toolkit\Enums\ResourceType;

it('has machine type', function () {
    expect(ResourceType::Machine->value)->toBe('rtMachine');
    expect(ResourceType::Machine->label())->toBe('Machine');
});

it('has labor type', function () {
    expect(ResourceType::Labor->value)->toBe('rtLabor');
    expect(ResourceType::Labor->label())->toBe('Labor');
});

it('has other type', function () {
    expect(ResourceType::Other->value)->toBe('rtOther');
    expect(ResourceType::Other->label())->toBe('Other');
});

it('can try from valid value', function () {
    expect(ResourceType::tryFrom('rtMachine'))->toBe(ResourceType::Machine);
    expect(ResourceType::tryFrom('rtLabor'))->toBe(ResourceType::Labor);
    expect(ResourceType::tryFrom('rtOther'))->toBe(ResourceType::Other);
});

it('returns null for invalid value', function () {
    expect(ResourceType::tryFrom('invalid'))->toBeNull();
});
