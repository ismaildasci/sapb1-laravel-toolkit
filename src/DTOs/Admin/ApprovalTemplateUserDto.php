<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs\Admin;

use SapB1\Toolkit\DTOs\Base\BaseDto;

/**
 * @phpstan-consistent-constructor
 */
final class ApprovalTemplateUserDto extends BaseDto
{
    public function __construct(
        public readonly ?int $userID = null,
    ) {}

    protected static function mapFromArray(array $data): array
    {
        return [
            'userID' => $data['UserID'] ?? null,
        ];
    }

    public function toArray(): array
    {
        return array_filter([
            'UserID' => $this->userID,
        ], fn ($value) => $value !== null);
    }
}
