<?php

declare(strict_types=1);

use SapB1\Toolkit\Builders\Admin\UserBuilder;
use SapB1\Toolkit\Enums\BoYesNo;
use SapB1\Toolkit\Enums\UserType;

it('builds user data', function () {
    $builder = UserBuilder::create()
        ->userCode('user01')
        ->userName('Test User')
        ->email('test@example.com')
        ->superuser(BoYesNo::No)
        ->locked(BoYesNo::No)
        ->userType(UserType::Regular)
        ->branch(1)
        ->department(1);

    $data = $builder->build();

    expect($data['UserCode'])->toBe('user01');
    expect($data['UserName'])->toBe('Test User');
    expect($data['eMail'])->toBe('test@example.com');
    expect($data['Superuser'])->toBe('tNO');
    expect($data['Locked'])->toBe('tNO');
    expect($data['UserType'])->toBe('ut_Regular');
});

it('builds with password', function () {
    $builder = UserBuilder::create()
        ->userCode('user01')
        ->password('SecurePass123');

    $data = $builder->build();

    expect($data['UserCode'])->toBe('user01');
    expect($data['UserPassword'])->toBe('SecurePass123');
});

it('can be reset', function () {
    $builder = UserBuilder::create()
        ->userCode('user01')
        ->userName('Test User');

    $builder->reset();
    $data = $builder->build();

    expect($data)->toBeEmpty();
});
