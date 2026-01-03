<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Builders\HR;

use SapB1\Toolkit\Builders\BaseBuilder;

/**
 * @phpstan-consistent-constructor
 */
final class TeamBuilder extends BaseBuilder
{
    public function teamName(string $name): static
    {
        return $this->set('TeamName', $name);
    }

    public function description(string $description): static
    {
        return $this->set('Description', $description);
    }

    /**
     * @param  array<TeamMemberBuilder|array<string, mixed>>  $members
     */
    public function teamMembers(array $members): static
    {
        $builtMembers = array_map(
            fn ($member) => $member instanceof TeamMemberBuilder ? $member->build() : $member,
            $members
        );

        return $this->set('TeamMembers', $builtMembers);
    }

    /**
     * @param  TeamMemberBuilder|array<string, mixed>  $member
     */
    public function addMember(TeamMemberBuilder|array $member): static
    {
        $members = $this->get('TeamMembers', []);
        $members[] = $member instanceof TeamMemberBuilder ? $member->build() : $member;

        return $this->set('TeamMembers', $members);
    }

    public function build(): array
    {
        return array_filter($this->data, fn ($value) => $value !== null);
    }
}
