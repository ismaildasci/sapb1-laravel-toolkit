<?php

declare(strict_types=1);

use Illuminate\Support\Facades\File;

beforeEach(function () {
    // Clean up any published files before each test
    $configPath = config_path('laravel-toolkit.php');
    if (File::exists($configPath)) {
        File::delete($configPath);
    }
});

afterEach(function () {
    // Clean up after tests
    $configPath = config_path('laravel-toolkit.php');
    if (File::exists($configPath)) {
        File::delete($configPath);
    }
});

it('has correct signature', function () {
    $command = Artisan::all()['sapb1:install'];

    expect($command->getName())->toBe('sapb1:install');
    expect($command->getDescription())->toBe('Install the SAP B1 Toolkit package');
});

it('has force option', function () {
    $command = Artisan::all()['sapb1:install'];
    $definition = $command->getDefinition();

    expect($definition->hasOption('force'))->toBeTrue();
});

it('displays installation message', function () {
    $this->artisan('sapb1:install')
        ->expectsConfirmation('Do you want to run the migrations now?', 'no')
        ->expectsOutput('Installing SAP B1 Toolkit...')
        ->assertSuccessful();
});

it('publishes configuration file', function () {
    $this->artisan('sapb1:install')
        ->expectsConfirmation('Do you want to run the migrations now?', 'no')
        ->expectsOutputToContain('Publishing configuration')
        ->assertSuccessful();
});

it('publishes migrations', function () {
    $this->artisan('sapb1:install')
        ->expectsConfirmation('Do you want to run the migrations now?', 'no')
        ->expectsOutputToContain('Publishing migrations')
        ->assertSuccessful();
});

it('displays success message', function () {
    $this->artisan('sapb1:install')
        ->expectsConfirmation('Do you want to run the migrations now?', 'no')
        ->expectsOutput('SAP B1 Toolkit installed successfully!')
        ->assertSuccessful();
});

it('displays post-install instructions', function () {
    $this->artisan('sapb1:install')
        ->expectsConfirmation('Do you want to run the migrations now?', 'no')
        ->expectsOutputToContain('Next steps:')
        ->expectsOutputToContain('config/laravel-toolkit.php')
        ->expectsOutputToContain('SAPB1_BASE_URL')
        ->expectsOutputToContain('php artisan sapb1:test')
        ->assertSuccessful();
});

it('returns success exit code', function () {
    $this->artisan('sapb1:install')
        ->expectsConfirmation('Do you want to run the migrations now?', 'no')
        ->assertExitCode(0);
});
