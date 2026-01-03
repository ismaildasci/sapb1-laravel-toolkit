<?php

declare(strict_types=1);

use Illuminate\Support\Facades\File;

beforeEach(function () {
    // Clean up any generated test files
    $testModule = 'TestModule';
    $basePath = dirname(__DIR__, 2).'/src';

    $paths = [
        "{$basePath}/DTOs/{$testModule}",
        "{$basePath}/Builders/{$testModule}",
        "{$basePath}/Actions/{$testModule}",
    ];

    foreach ($paths as $path) {
        if (File::isDirectory($path)) {
            File::deleteDirectory($path);
        }
    }
});

afterEach(function () {
    // Clean up after tests
    $testModule = 'TestModule';
    $basePath = dirname(__DIR__, 2).'/src';

    $paths = [
        "{$basePath}/DTOs/{$testModule}",
        "{$basePath}/Builders/{$testModule}",
        "{$basePath}/Actions/{$testModule}",
    ];

    foreach ($paths as $path) {
        if (File::isDirectory($path)) {
            File::deleteDirectory($path);
        }
    }
});

it('is registered as artisan command', function () {
    $commands = Artisan::all();

    expect($commands)->toHaveKey('sapb1:generate');
});

it('has correct signature', function () {
    $command = Artisan::all()['sapb1:generate'];

    expect($command->getName())->toBe('sapb1:generate');
    expect($command->getDescription())->toBe('Generate SAP B1 Toolkit files (DTO, Builder, Action)');
});

it('has required name argument', function () {
    $command = Artisan::all()['sapb1:generate'];
    $definition = $command->getDefinition();

    expect($definition->hasArgument('name'))->toBeTrue();
    expect($definition->getArgument('name')->isRequired())->toBeTrue();
});

it('has module option with default', function () {
    $command = Artisan::all()['sapb1:generate'];
    $definition = $command->getDefinition();

    expect($definition->hasOption('module'))->toBeTrue();
    expect($definition->getOption('module')->getDefault())->toBe('Custom');
});

it('has type option with default', function () {
    $command = Artisan::all()['sapb1:generate'];
    $definition = $command->getDefinition();

    expect($definition->hasOption('type'))->toBeTrue();
    expect($definition->getOption('type')->getDefault())->toBe('all');
});

it('has force option', function () {
    $command = Artisan::all()['sapb1:generate'];
    $definition = $command->getDefinition();

    expect($definition->hasOption('force'))->toBeTrue();
});

it('generates all files when type is all', function () {
    $basePath = dirname(__DIR__, 2).'/src';

    $this->artisan('sapb1:generate', [
        'name' => 'TestEntity',
        '--module' => 'TestModule',
    ])->assertSuccessful();

    expect(File::exists("{$basePath}/DTOs/TestModule/TestEntityDto.php"))->toBeTrue();
    expect(File::exists("{$basePath}/Builders/TestModule/TestEntityBuilder.php"))->toBeTrue();
    expect(File::exists("{$basePath}/Actions/TestModule/TestEntityAction.php"))->toBeTrue();
});

it('generates only dto when type is dto', function () {
    $basePath = dirname(__DIR__, 2).'/src';

    $this->artisan('sapb1:generate', [
        'name' => 'TestEntity',
        '--module' => 'TestModule',
        '--type' => 'dto',
    ])->assertSuccessful();

    expect(File::exists("{$basePath}/DTOs/TestModule/TestEntityDto.php"))->toBeTrue();
    expect(File::exists("{$basePath}/Builders/TestModule/TestEntityBuilder.php"))->toBeFalse();
    expect(File::exists("{$basePath}/Actions/TestModule/TestEntityAction.php"))->toBeFalse();
});

it('generates only builder when type is builder', function () {
    $basePath = dirname(__DIR__, 2).'/src';

    $this->artisan('sapb1:generate', [
        'name' => 'TestEntity',
        '--module' => 'TestModule',
        '--type' => 'builder',
    ])->assertSuccessful();

    expect(File::exists("{$basePath}/DTOs/TestModule/TestEntityDto.php"))->toBeFalse();
    expect(File::exists("{$basePath}/Builders/TestModule/TestEntityBuilder.php"))->toBeTrue();
    expect(File::exists("{$basePath}/Actions/TestModule/TestEntityAction.php"))->toBeFalse();
});

it('generates only action when type is action', function () {
    $basePath = dirname(__DIR__, 2).'/src';

    $this->artisan('sapb1:generate', [
        'name' => 'TestEntity',
        '--module' => 'TestModule',
        '--type' => 'action',
    ])->assertSuccessful();

    expect(File::exists("{$basePath}/DTOs/TestModule/TestEntityDto.php"))->toBeFalse();
    expect(File::exists("{$basePath}/Builders/TestModule/TestEntityBuilder.php"))->toBeFalse();
    expect(File::exists("{$basePath}/Actions/TestModule/TestEntityAction.php"))->toBeTrue();
});

it('does not overwrite existing files without force', function () {
    $basePath = dirname(__DIR__, 2).'/src';

    // Generate first time
    $this->artisan('sapb1:generate', [
        'name' => 'TestEntity',
        '--module' => 'TestModule',
        '--type' => 'dto',
    ])->assertSuccessful();

    // Try to generate again without force
    $this->artisan('sapb1:generate', [
        'name' => 'TestEntity',
        '--module' => 'TestModule',
        '--type' => 'dto',
    ])
        ->expectsOutputToContain('already exists')
        ->assertSuccessful();
});

it('overwrites existing files with force option', function () {
    $basePath = dirname(__DIR__, 2).'/src';
    $dtoPath = "{$basePath}/DTOs/TestModule/TestEntityDto.php";

    // Generate first time
    $this->artisan('sapb1:generate', [
        'name' => 'TestEntity',
        '--module' => 'TestModule',
        '--type' => 'dto',
    ])->assertSuccessful();

    $firstContent = File::get($dtoPath);

    // Generate again with force
    $this->artisan('sapb1:generate', [
        'name' => 'TestEntity',
        '--module' => 'TestModule',
        '--type' => 'dto',
        '--force' => true,
    ])->assertSuccessful();

    expect(File::exists($dtoPath))->toBeTrue();
});

it('uses custom entity name when provided', function () {
    $basePath = dirname(__DIR__, 2).'/src';

    $this->artisan('sapb1:generate', [
        'name' => 'TestEntity',
        '--module' => 'TestModule',
        '--entity' => 'CustomEntities',
        '--type' => 'action',
    ])->assertSuccessful();

    $actionPath = "{$basePath}/Actions/TestModule/TestEntityAction.php";
    $content = File::get($actionPath);

    expect($content)->toContain("protected string \$entity = 'CustomEntities';");
});

it('generates correct namespace in files', function () {
    $basePath = dirname(__DIR__, 2).'/src';

    $this->artisan('sapb1:generate', [
        'name' => 'TestEntity',
        '--module' => 'TestModule',
    ])->assertSuccessful();

    $dtoContent = File::get("{$basePath}/DTOs/TestModule/TestEntityDto.php");
    $builderContent = File::get("{$basePath}/Builders/TestModule/TestEntityBuilder.php");
    $actionContent = File::get("{$basePath}/Actions/TestModule/TestEntityAction.php");

    expect($dtoContent)->toContain('namespace SapB1\Toolkit\DTOs\TestModule;');
    expect($builderContent)->toContain('namespace SapB1\Toolkit\Builders\TestModule;');
    expect($actionContent)->toContain('namespace SapB1\Toolkit\Actions\TestModule;');
});
