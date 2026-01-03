<?php

declare(strict_types=1);

use SapB1\Toolkit\Enums\UserType;

it('has correct values', function () {
    expect(UserType::Regular->value)->toBe('ut_Regular');
    expect(UserType::LimitedAccess->value)->toBe('ut_LimitedAccess');
    expect(UserType::ExternalUser->value)->toBe('ut_ExternalUser');
});

it('returns correct labels', function () {
    expect(UserType::Regular->label())->toBe('Regular');
    expect(UserType::LimitedAccess->label())->toBe('Limited Access');
    expect(UserType::ExternalUser->label())->toBe('External User');
});

it('can be created from value', function () {
    expect(UserType::from('ut_Regular'))->toBe(UserType::Regular);
    expect(UserType::tryFrom('ut_Invalid'))->toBeNull();
});
