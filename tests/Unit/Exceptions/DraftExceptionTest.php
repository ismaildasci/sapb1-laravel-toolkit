<?php

declare(strict_types=1);

use SapB1\Toolkit\Exceptions\DraftException;
use SapB1\Toolkit\Exceptions\SapB1Exception;

it('extends SapB1Exception', function () {
    $exception = new DraftException('Test error');

    expect($exception)->toBeInstanceOf(SapB1Exception::class);
});

it('can create createFailed exception', function () {
    $exception = DraftException::createFailed('Sales Order', 'Invalid CardCode');

    expect($exception)->toBeInstanceOf(DraftException::class);
    expect($exception->getMessage())->toContain('Failed to create Sales Order draft');
    expect($exception->getMessage())->toContain('Invalid CardCode');
    expect($exception->context['documentType'])->toBe('Sales Order');
    expect($exception->context['error'])->toBe('Invalid CardCode');
});

it('can create notFound exception', function () {
    $exception = DraftException::notFound(123);

    expect($exception)->toBeInstanceOf(DraftException::class);
    expect($exception->getMessage())->toContain('Draft with DocEntry 123 not found');
    expect($exception->context['docEntry'])->toBe(123);
});

it('can create updateFailed exception', function () {
    $exception = DraftException::updateFailed(456, 'Document is locked');

    expect($exception)->toBeInstanceOf(DraftException::class);
    expect($exception->getMessage())->toContain('Failed to update draft 456');
    expect($exception->getMessage())->toContain('Document is locked');
    expect($exception->context['docEntry'])->toBe(456);
    expect($exception->context['error'])->toBe('Document is locked');
});

it('can create deleteFailed exception', function () {
    $exception = DraftException::deleteFailed(789, 'Access denied');

    expect($exception)->toBeInstanceOf(DraftException::class);
    expect($exception->getMessage())->toContain('Failed to delete draft 789');
    expect($exception->getMessage())->toContain('Access denied');
    expect($exception->context['docEntry'])->toBe(789);
    expect($exception->context['error'])->toBe('Access denied');
});

it('can create saveFailed exception', function () {
    $exception = DraftException::saveFailed(100, 'Insufficient inventory');

    expect($exception)->toBeInstanceOf(DraftException::class);
    expect($exception->getMessage())->toContain('Failed to save draft 100 as document');
    expect($exception->getMessage())->toContain('Insufficient inventory');
    expect($exception->context['docEntry'])->toBe(100);
    expect($exception->context['error'])->toBe('Insufficient inventory');
});
