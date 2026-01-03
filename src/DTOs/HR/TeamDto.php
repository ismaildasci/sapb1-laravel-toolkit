<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs\HR;

use SapB1\Toolkit\DTOs\Base\BaseDto;

/**
 * @phpstan-consistent-constructor
 */
final class TeamDto extends BaseDto
{
    /**
     * @param  array<TeamMemberDto>|null  $teamMembers
     */
    public function __construct(
        public readonly ?int $teamID = null,
        public readonly ?string $teamName = null,
        public readonly ?string $description = null,
        public readonly ?array $teamMembers = null,
    ) {}

    protected static function mapFromArray(array $data): array
    {
        $members = null;
        if (isset($data['TeamMembers']) && is_array($data['TeamMembers'])) {
            $members = array_map(
                fn (array $member) => TeamMemberDto::fromArray($member),
                $data['TeamMembers']
            );
        }

        return [
            'teamID' => $data['TeamID'] ?? null,
            'teamName' => $data['TeamName'] ?? null,
            'description' => $data['Description'] ?? null,
            'teamMembers' => $members,
        ];
    }

    public function toArray(): array
    {
        $result = array_filter([
            'TeamID' => $this->teamID,
            'TeamName' => $this->teamName,
            'Description' => $this->description,
        ], fn ($value) => $value !== null);

        if ($this->teamMembers !== null) {
            $result['TeamMembers'] = array_map(
                fn (TeamMemberDto $member) => $member->toArray(),
                $this->teamMembers
            );
        }

        return $result;
    }
}
