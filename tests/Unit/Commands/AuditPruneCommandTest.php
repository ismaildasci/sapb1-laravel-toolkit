<?php

declare(strict_types=1);

use SapB1\Toolkit\Commands\AuditPruneCommand;

it('has correct signature', function () {
    $command = Artisan::all()['sapb1:audit-prune'];

    expect($command->getName())->toBe('sapb1:audit-prune');
    expect($command->getDescription())->toBe('Prune old audit log entries');
});

it('has days option', function () {
    $command = Artisan::all()['sapb1:audit-prune'];
    $definition = $command->getDefinition();

    expect($definition->hasOption('days'))->toBeTrue();
});

it('has dry-run option', function () {
    $command = Artisan::all()['sapb1:audit-prune'];
    $definition = $command->getDefinition();

    expect($definition->hasOption('dry-run'))->toBeTrue();
});

it('has force option', function () {
    $command = Artisan::all()['sapb1:audit-prune'];
    $definition = $command->getDefinition();

    expect($definition->hasOption('force'))->toBeTrue();
});

it('command class exists', function () {
    expect(class_exists(AuditPruneCommand::class))->toBeTrue();
});
