<?php

declare(strict_types=1);

use SapB1\Toolkit\Builders\HR\TeamBuilder;
use SapB1\Toolkit\Builders\HR\TeamMemberBuilder;

it('builds team data', function () {
    $builder = TeamBuilder::create()
        ->teamName('Development Team')
        ->description('Software Development');

    $data = $builder->build();

    expect($data['TeamName'])->toBe('Development Team');
    expect($data['Description'])->toBe('Software Development');
});

it('builds with members array', function () {
    $builder = TeamBuilder::create()
        ->teamName('Development Team')
        ->teamMembers([
            TeamMemberBuilder::create()->employeeID(10)->roleName('Lead'),
            TeamMemberBuilder::create()->employeeID(20)->roleName('Developer'),
        ]);

    $data = $builder->build();

    expect($data['TeamMembers'])->toHaveCount(2);
    expect($data['TeamMembers'][0]['EmployeeID'])->toBe(10);
    expect($data['TeamMembers'][1]['EmployeeID'])->toBe(20);
});

it('can add members incrementally', function () {
    $builder = TeamBuilder::create()
        ->teamName('Development Team')
        ->addMember(TeamMemberBuilder::create()->employeeID(10)->roleName('Lead'))
        ->addMember(['EmployeeID' => 20, 'RoleName' => 'Developer']);

    $data = $builder->build();

    expect($data['TeamMembers'])->toHaveCount(2);
});
