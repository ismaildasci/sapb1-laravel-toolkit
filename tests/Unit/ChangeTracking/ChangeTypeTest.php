<?php

declare(strict_types=1);

use SapB1\Toolkit\ChangeTracking\ChangeType;

it('enum exists', function () {
    expect(enum_exists(ChangeType::class))->toBeTrue();
});

it('has Created case', function () {
    expect(ChangeType::Created->value)->toBe('created');
});

it('has Updated case', function () {
    expect(ChangeType::Updated->value)->toBe('updated');
});

it('has Deleted case', function () {
    expect(ChangeType::Deleted->value)->toBe('deleted');
});

it('can be created from string', function () {
    expect(ChangeType::from('created'))->toBe(ChangeType::Created);
    expect(ChangeType::from('updated'))->toBe(ChangeType::Updated);
    expect(ChangeType::from('deleted'))->toBe(ChangeType::Deleted);
});
