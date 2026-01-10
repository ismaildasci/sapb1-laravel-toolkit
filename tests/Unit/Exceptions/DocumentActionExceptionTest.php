<?php

declare(strict_types=1);

use SapB1\Toolkit\Exceptions\DocumentActionException;
use SapB1\Toolkit\Exceptions\SapB1Exception;

it('extends SapB1Exception', function () {
    $exception = new DocumentActionException('Test error');

    expect($exception)->toBeInstanceOf(SapB1Exception::class);
});

it('can create actionFailed exception', function () {
    $exception = DocumentActionException::actionFailed('Orders', 123, 'Close', 'Document is already closed');

    expect($exception)->toBeInstanceOf(DocumentActionException::class);
    expect($exception->getMessage())->toContain('Failed to Close document Orders(123)');
    expect($exception->getMessage())->toContain('Document is already closed');
    expect($exception->context)->toHaveKey('endpoint');
    expect($exception->context)->toHaveKey('docEntry');
    expect($exception->context)->toHaveKey('action');
    expect($exception->context)->toHaveKey('error');
});

it('can create actionNotSupported exception', function () {
    $exception = DocumentActionException::actionNotSupported('Invoices', 'Close');

    expect($exception)->toBeInstanceOf(DocumentActionException::class);
    expect($exception->getMessage())->toContain("Action 'Close' is not supported for endpoint 'Invoices'");
    expect($exception->context['endpoint'])->toBe('Invoices');
    expect($exception->context['action'])->toBe('Close');
});

it('can create documentNotFound exception', function () {
    $exception = DocumentActionException::documentNotFound('Orders', 999);

    expect($exception)->toBeInstanceOf(DocumentActionException::class);
    expect($exception->getMessage())->toContain('Document Orders(999) not found');
    expect($exception->context['endpoint'])->toBe('Orders');
    expect($exception->context['docEntry'])->toBe(999);
});

it('can create invalidState exception', function () {
    $exception = DocumentActionException::invalidState('Orders', 123, 'Cancel', 'closed');

    expect($exception)->toBeInstanceOf(DocumentActionException::class);
    expect($exception->getMessage())->toContain('Cannot Cancel document Orders(123): document is closed');
    expect($exception->context['endpoint'])->toBe('Orders');
    expect($exception->context['docEntry'])->toBe(123);
    expect($exception->context['action'])->toBe('Cancel');
    expect($exception->context['currentState'])->toBe('closed');
});
