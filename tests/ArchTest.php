<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Architecture Tests
|--------------------------------------------------------------------------
|
| These tests ensure the codebase follows architectural rules and best
| practices. They help maintain consistency and prevent common mistakes.
|
*/

// =============================================================================
// GENERAL RULES
// =============================================================================

arch('it will not use debugging functions')
    ->expect(['dd', 'dump', 'ray', 'var_dump', 'print_r'])
    ->each->not->toBeUsed();

arch('it uses strict types')
    ->expect('SapB1\Toolkit')
    ->toUseStrictTypes();

arch('it does not use die or exit')
    ->expect(['die', 'exit'])
    ->not->toBeUsed();

// =============================================================================
// NAMESPACE STRUCTURE
// =============================================================================

arch('contracts are interfaces')
    ->expect('SapB1\Toolkit\Contracts')
    ->toBeInterfaces();

arch('enums are enums')
    ->expect('SapB1\Toolkit\Enums')
    ->toBeEnums();

arch('exceptions extend Exception')
    ->expect('SapB1\Toolkit\Exceptions')
    ->toExtend(Exception::class);

arch('events are classes')
    ->expect('SapB1\Toolkit\Events')
    ->toBeClasses();

arch('rules implement ValidationRule')
    ->expect('SapB1\Toolkit\Rules')
    ->toImplement(\Illuminate\Contracts\Validation\ValidationRule::class);

// =============================================================================
// DTO RULES
// =============================================================================

arch('DTOs are classes')
    ->expect('SapB1\Toolkit\DTOs')
    ->toBeClasses();

arch('Concrete DTOs are final')
    ->expect('SapB1\Toolkit\DTOs')
    ->toBeFinal()
    ->ignoring([
        'SapB1\Toolkit\DTOs\Base\BaseDto',
        'SapB1\Toolkit\DTOs\DocumentDto',
        'SapB1\Toolkit\DTOs\DocumentLineDto',
    ]);

arch('DTOs implement DtoInterface')
    ->expect('SapB1\Toolkit\DTOs')
    ->toImplement(\SapB1\Toolkit\Contracts\DtoInterface::class)
    ->ignoring([
        'SapB1\Toolkit\DTOs\Base\BaseDto',
        'SapB1\Toolkit\DTOs\AddressDto',
    ]);

// =============================================================================
// BUILDER RULES
// =============================================================================

arch('Builders are classes')
    ->expect('SapB1\Toolkit\Builders')
    ->toBeClasses();

arch('Builders implement BuilderInterface')
    ->expect('SapB1\Toolkit\Builders')
    ->toImplement(\SapB1\Toolkit\Contracts\BuilderInterface::class)
    ->ignoring([
        'SapB1\Toolkit\Builders\BaseBuilder',
        'SapB1\Toolkit\Builders\DocumentBuilder',
        'SapB1\Toolkit\Builders\DocumentLineBuilder',
    ]);

// =============================================================================
// ACTION RULES
// =============================================================================

arch('Actions are classes')
    ->expect('SapB1\Toolkit\Actions')
    ->toBeClasses();

arch('Actions implement ActionInterface')
    ->expect('SapB1\Toolkit\Actions')
    ->toImplement(\SapB1\Toolkit\Contracts\ActionInterface::class)
    ->ignoring([
        'SapB1\Toolkit\Actions\BaseAction',
        'SapB1\Toolkit\Actions\DocumentAction',
    ]);

// =============================================================================
// SERVICE RULES
// =============================================================================

arch('Services are classes')
    ->expect('SapB1\Toolkit\Services')
    ->toBeClasses();

arch('Services have Service suffix')
    ->expect('SapB1\Toolkit\Services')
    ->toHaveSuffix('Service');

// =============================================================================
// COMMAND RULES
// =============================================================================

arch('Commands extend Laravel Command')
    ->expect('SapB1\Toolkit\Commands')
    ->toExtend(\Illuminate\Console\Command::class);

arch('Commands have Command suffix')
    ->expect('SapB1\Toolkit\Commands')
    ->toHaveSuffix('Command');

// =============================================================================
// CAST RULES
// =============================================================================

arch('Casts implement CastsAttributes')
    ->expect('SapB1\Toolkit\Casts')
    ->toImplement(\Illuminate\Contracts\Database\Eloquent\CastsAttributes::class);

arch('Casts have Cast suffix')
    ->expect('SapB1\Toolkit\Casts')
    ->toHaveSuffix('Cast');

// =============================================================================
// DEPENDENCY RULES
// =============================================================================

arch('DTOs do not depend on Actions')
    ->expect('SapB1\Toolkit\DTOs')
    ->not->toUse('SapB1\Toolkit\Actions');

arch('DTOs do not depend on Services')
    ->expect('SapB1\Toolkit\DTOs')
    ->not->toUse('SapB1\Toolkit\Services');

arch('Enums do not depend on other namespaces')
    ->expect('SapB1\Toolkit\Enums')
    ->not->toUse([
        'SapB1\Toolkit\Actions',
        'SapB1\Toolkit\Services',
        'SapB1\Toolkit\DTOs',
        'SapB1\Toolkit\Builders',
    ]);

// =============================================================================
// NAMING CONVENTIONS
// =============================================================================

arch('Actions have Action suffix')
    ->expect('SapB1\Toolkit\Actions')
    ->toHaveSuffix('Action');

arch('Builders have Builder suffix')
    ->expect('SapB1\Toolkit\Builders')
    ->toHaveSuffix('Builder');

arch('DTOs have Dto suffix')
    ->expect('SapB1\Toolkit\DTOs')
    ->toHaveSuffix('Dto');

arch('Exceptions have Exception suffix')
    ->expect('SapB1\Toolkit\Exceptions')
    ->toHaveSuffix('Exception');

arch('Events have event-like names')
    ->expect('SapB1\Toolkit\Events')
    ->not->toHaveSuffix('Service')
    ->not->toHaveSuffix('Action')
    ->not->toHaveSuffix('Controller');

arch('Rules have Rule suffix')
    ->expect('SapB1\Toolkit\Rules')
    ->toHaveSuffix('Rule');
