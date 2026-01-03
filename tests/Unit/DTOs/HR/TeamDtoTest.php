<?php

declare(strict_types=1);

use SapB1\Toolkit\DTOs\HR\TeamDto;
use SapB1\Toolkit\DTOs\HR\TeamMemberDto;

it('creates from array', function () {
    $data = [
        'TeamID' => 1,
        'TeamName' => 'Development Team',
        'Description' => 'Software Development',
    ];

    $dto = TeamDto::fromArray($data);

    expect($dto->teamID)->toBe(1);
    expect($dto->teamName)->toBe('Development Team');
    expect($dto->description)->toBe('Software Development');
});

it('creates from response with members', function () {
    $response = [
        'TeamID' => 1,
        'TeamName' => 'Development Team',
        'TeamMembers' => [
            [
                'TeamID' => 1,
                'EmployeeID' => 10,
                'RoleName' => 'Team Lead',
            ],
            [
                'TeamID' => 1,
                'EmployeeID' => 20,
                'RoleName' => 'Developer',
            ],
        ],
    ];

    $dto = TeamDto::fromResponse($response);

    expect($dto->teamID)->toBe(1);
    expect($dto->teamMembers)->toHaveCount(2);
    expect($dto->teamMembers[0])->toBeInstanceOf(TeamMemberDto::class);
    expect($dto->teamMembers[0]->employeeID)->toBe(10);
    expect($dto->teamMembers[0]->roleName)->toBe('Team Lead');
});

it('converts to array', function () {
    $dto = new TeamDto(
        teamID: 1,
        teamName: 'Development Team',
        description: 'Software Development',
    );

    $array = $dto->toArray();

    expect($array['TeamID'])->toBe(1);
    expect($array['TeamName'])->toBe('Development Team');
    expect($array['Description'])->toBe('Software Development');
});

it('includes members in toArray', function () {
    $dto = new TeamDto(
        teamID: 1,
        teamName: 'Development Team',
        teamMembers: [
            new TeamMemberDto(teamID: 1, employeeID: 10, roleName: 'Lead'),
        ],
    );

    $array = $dto->toArray();

    expect($array['TeamMembers'])->toHaveCount(1);
    expect($array['TeamMembers'][0]['EmployeeID'])->toBe(10);
});
