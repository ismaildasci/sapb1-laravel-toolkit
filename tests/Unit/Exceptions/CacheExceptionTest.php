<?php

declare(strict_types=1);

use SapB1\Toolkit\Exceptions\CacheException;

it('class exists', function () {
    expect(class_exists(CacheException::class))->toBeTrue();
});

it('extends Exception', function () {
    $exception = new CacheException('Test');

    expect($exception)->toBeInstanceOf(Exception::class);
});

it('creates operation failed exception', function () {
    $exception = CacheException::operationFailed('put', 'connection timeout');

    expect($exception->getMessage())->toBe('Cache put failed: connection timeout');
});

it('creates read failed exception', function () {
    $exception = CacheException::readFailed('items:123', 'serialization error');

    expect($exception->getMessage())->toBe('Failed to read cache key [items:123]: serialization error');
});

it('creates write failed exception', function () {
    $exception = CacheException::writeFailed('items:123', 'disk full');

    expect($exception->getMessage())->toBe('Failed to write cache key [items:123]: disk full');
});

it('creates flush failed exception', function () {
    $exception = CacheException::flushFailed('Items', 'tags not supported');

    expect($exception->getMessage())->toBe('Failed to flush cache for entity [Items]: tags not supported');
});

it('creates store not supported exception', function () {
    $exception = CacheException::storeNotSupported('array');

    expect($exception->getMessage())->toBe('Cache store [array] does not support tagging. Use Redis or another tag-supporting driver.');
});

it('creates invalid TTL exception', function () {
    $exception = CacheException::invalidTtl(-100);

    expect($exception->getMessage())->toBe('Invalid cache TTL [-100]. TTL must be a positive integer.');
});
