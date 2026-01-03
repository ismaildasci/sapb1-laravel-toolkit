<?php

declare(strict_types=1);

use SapB1\Toolkit\DTOs\Admin\UserDto;
use SapB1\Toolkit\Enums\BoYesNo;
use SapB1\Toolkit\Enums\UserType;

it('creates from array', function () {
    $data = [
        'InternalKey' => 1,
        'UserCode' => 'admin',
        'UserName' => 'Administrator',
        'Superuser' => 'tYES',
        'eMail' => 'admin@example.com',
        'Locked' => 'tNO',
        'UserType' => 'ut_Regular',
    ];

    $dto = UserDto::fromArray($data);

    expect($dto->internalKey)->toBe(1);
    expect($dto->userCode)->toBe('admin');
    expect($dto->userName)->toBe('Administrator');
    expect($dto->superuser)->toBe(BoYesNo::Yes);
    expect($dto->email)->toBe('admin@example.com');
    expect($dto->locked)->toBe(BoYesNo::No);
    expect($dto->userType)->toBe(UserType::Regular);
});

it('converts to array', function () {
    $dto = new UserDto(
        internalKey: 1,
        userCode: 'admin',
        userName: 'Administrator',
        superuser: BoYesNo::Yes,
        locked: BoYesNo::No,
    );

    $array = $dto->toArray();

    expect($array['InternalKey'])->toBe(1);
    expect($array['UserCode'])->toBe('admin');
    expect($array['UserName'])->toBe('Administrator');
    expect($array['Superuser'])->toBe('tYES');
    expect($array['Locked'])->toBe('tNO');
});

it('can check if superuser', function () {
    $superuser = new UserDto(superuser: BoYesNo::Yes);
    $regularUser = new UserDto(superuser: BoYesNo::No);

    expect($superuser->isSuperuser())->toBeTrue();
    expect($regularUser->isSuperuser())->toBeFalse();
});

it('can check if locked', function () {
    $lockedUser = new UserDto(locked: BoYesNo::Yes);
    $activeUser = new UserDto(locked: BoYesNo::No);

    expect($lockedUser->isLocked())->toBeTrue();
    expect($activeUser->isLocked())->toBeFalse();
});
