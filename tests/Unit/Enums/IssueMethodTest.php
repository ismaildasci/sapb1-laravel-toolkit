<?php

declare(strict_types=1);

use SapB1\Toolkit\Enums\IssueMethod;

it('has backflush method', function () {
    expect(IssueMethod::Backflush->value)->toBe('bomimBackflush');
    expect(IssueMethod::Backflush->label())->toBe('Backflush');
});

it('has manual method', function () {
    expect(IssueMethod::Manual->value)->toBe('bomimManual');
    expect(IssueMethod::Manual->label())->toBe('Manual');
});

it('can try from valid value', function () {
    expect(IssueMethod::tryFrom('bomimBackflush'))->toBe(IssueMethod::Backflush);
    expect(IssueMethod::tryFrom('bomimManual'))->toBe(IssueMethod::Manual);
});

it('returns null for invalid value', function () {
    expect(IssueMethod::tryFrom('invalid'))->toBeNull();
});
