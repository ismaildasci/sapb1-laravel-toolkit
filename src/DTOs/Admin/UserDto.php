<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs\Admin;

use SapB1\Toolkit\DTOs\Base\BaseDto;
use SapB1\Toolkit\Enums\BoYesNo;
use SapB1\Toolkit\Enums\UserType;

/**
 * @phpstan-consistent-constructor
 */
final class UserDto extends BaseDto
{
    public function __construct(
        public readonly ?int $internalKey = null,
        public readonly ?string $userCode = null,
        public readonly ?string $userName = null,
        public readonly ?BoYesNo $superuser = null,
        public readonly ?string $email = null,
        public readonly ?string $mobilePhone = null,
        public readonly ?int $defaults = null,
        public readonly ?string $faxNumber = null,
        public readonly ?int $branch = null,
        public readonly ?int $department = null,
        public readonly ?BoYesNo $locked = null,
        public readonly ?UserType $userType = null,
        public readonly ?int $group = null,
    ) {}

    protected static function mapFromArray(array $data): array
    {
        return [
            'internalKey' => $data['InternalKey'] ?? null,
            'userCode' => $data['UserCode'] ?? null,
            'userName' => $data['UserName'] ?? null,
            'superuser' => isset($data['Superuser']) ? BoYesNo::tryFrom($data['Superuser']) : null,
            'email' => $data['eMail'] ?? null,
            'mobilePhone' => $data['MobilePhoneNumber'] ?? null,
            'defaults' => $data['Defaults'] ?? null,
            'faxNumber' => $data['FaxNumber'] ?? null,
            'branch' => $data['Branch'] ?? null,
            'department' => $data['Department'] ?? null,
            'locked' => isset($data['Locked']) ? BoYesNo::tryFrom($data['Locked']) : null,
            'userType' => isset($data['UserType']) ? UserType::tryFrom($data['UserType']) : null,
            'group' => $data['Group'] ?? null,
        ];
    }

    public function toArray(): array
    {
        return array_filter([
            'InternalKey' => $this->internalKey,
            'UserCode' => $this->userCode,
            'UserName' => $this->userName,
            'Superuser' => $this->superuser?->value,
            'eMail' => $this->email,
            'MobilePhoneNumber' => $this->mobilePhone,
            'Defaults' => $this->defaults,
            'FaxNumber' => $this->faxNumber,
            'Branch' => $this->branch,
            'Department' => $this->department,
            'Locked' => $this->locked?->value,
            'UserType' => $this->userType?->value,
            'Group' => $this->group,
        ], fn ($value) => $value !== null);
    }

    public function isSuperuser(): bool
    {
        return $this->superuser === BoYesNo::Yes;
    }

    public function isLocked(): bool
    {
        return $this->locked === BoYesNo::Yes;
    }
}
