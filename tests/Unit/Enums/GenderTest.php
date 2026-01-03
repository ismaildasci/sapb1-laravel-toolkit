<?php

declare(strict_types=1);

use SapB1\Toolkit\Enums\Gender;

it('has correct values', function () {
    expect(Gender::Male->value)->toBe('gt_Male');
    expect(Gender::Female->value)->toBe('gt_Female');
    expect(Gender::Undefined->value)->toBe('gt_Undefined');
});

it('returns correct labels', function () {
    expect(Gender::Male->label())->toBe('Male');
    expect(Gender::Female->label())->toBe('Female');
    expect(Gender::Undefined->label())->toBe('Undefined');
});

it('can be created from value', function () {
    expect(Gender::from('gt_Male'))->toBe(Gender::Male);
    expect(Gender::tryFrom('gt_Invalid'))->toBeNull();
});
