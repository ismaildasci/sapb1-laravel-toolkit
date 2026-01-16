<?php

declare(strict_types=1);

use SapB1\Toolkit\Commands\AuditExportCommand;

it('has correct signature', function () {
    $command = Artisan::all()['sapb1:audit-export'];

    expect($command->getName())->toBe('sapb1:audit-export');
    expect($command->getDescription())->toBe('Export audit log entries to CSV or JSON');
});

it('has entity option', function () {
    $command = Artisan::all()['sapb1:audit-export'];
    $definition = $command->getDefinition();

    expect($definition->hasOption('entity'))->toBeTrue();
});

it('has since option', function () {
    $command = Artisan::all()['sapb1:audit-export'];
    $definition = $command->getDefinition();

    expect($definition->hasOption('since'))->toBeTrue();
});

it('has format option', function () {
    $command = Artisan::all()['sapb1:audit-export'];
    $definition = $command->getDefinition();

    expect($definition->hasOption('format'))->toBeTrue();
});

it('has limit option', function () {
    $command = Artisan::all()['sapb1:audit-export'];
    $definition = $command->getDefinition();

    expect($definition->hasOption('limit'))->toBeTrue();
});

it('has output option', function () {
    $command = Artisan::all()['sapb1:audit-export'];
    $definition = $command->getDefinition();

    expect($definition->hasOption('output'))->toBeTrue();
});

it('command class exists', function () {
    expect(class_exists(AuditExportCommand::class))->toBeTrue();
});
