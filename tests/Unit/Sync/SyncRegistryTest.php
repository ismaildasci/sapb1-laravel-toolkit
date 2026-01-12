<?php

declare(strict_types=1);

use SapB1\Toolkit\Sync\Exceptions\SyncException;
use SapB1\Toolkit\Sync\SyncConfig;
use SapB1\Toolkit\Sync\SyncRegistry;

describe('SyncRegistry', function () {
    beforeEach(function () {
        $this->registry = new SyncRegistry;
    });

    describe('register', function () {
        it('registers a config', function () {
            $config = SyncConfig::items();
            $this->registry->register('Items', $config);

            expect($this->registry->has('Items'))->toBeTrue();
        });

        it('returns self for chaining', function () {
            $result = $this->registry->register('Items', SyncConfig::items());

            expect($result)->toBe($this->registry);
        });
    });

    describe('registerMany', function () {
        it('registers multiple configs', function () {
            $this->registry->registerMany([
                'Items' => SyncConfig::items(),
                'Orders' => SyncConfig::orders(),
            ]);

            expect($this->registry->has('Items'))->toBeTrue();
            expect($this->registry->has('Orders'))->toBeTrue();
        });
    });

    describe('get', function () {
        it('returns registered config', function () {
            $config = SyncConfig::items();
            $this->registry->register('Items', $config);

            expect($this->registry->get('Items'))->toBe($config);
        });

        it('falls back to predefined config', function () {
            $config = $this->registry->get('Items');

            expect($config)->toBeInstanceOf(SyncConfig::class);
            expect($config->entity)->toBe('Items');
        });

        it('throws exception for unknown entity', function () {
            expect(fn () => $this->registry->get('Unknown'))
                ->toThrow(SyncException::class);
        });
    });

    describe('find', function () {
        it('returns config without throwing', function () {
            expect($this->registry->find('Items'))->toBeInstanceOf(SyncConfig::class);
            expect($this->registry->find('Unknown'))->toBeNull();
        });
    });

    describe('has', function () {
        it('returns true for registered entity', function () {
            $this->registry->register('Items', SyncConfig::items());

            expect($this->registry->has('Items'))->toBeTrue();
        });

        it('returns true for predefined entity', function () {
            expect($this->registry->has('Items'))->toBeTrue();
            expect($this->registry->has('Orders'))->toBeTrue();
        });

        it('returns false for unknown entity', function () {
            expect($this->registry->has('Unknown'))->toBeFalse();
        });
    });

    describe('all', function () {
        it('returns empty array initially', function () {
            expect($this->registry->all())->toBe([]);
        });

        it('returns registered configs', function () {
            $this->registry->register('Items', SyncConfig::items());

            expect($this->registry->all())->toHaveCount(1);
            expect($this->registry->all())->toHaveKey('Items');
        });
    });

    describe('entities', function () {
        it('returns registered entity names', function () {
            $this->registry->register('Items', SyncConfig::items());
            $this->registry->register('Orders', SyncConfig::orders());

            expect($this->registry->entities())->toBe(['Items', 'Orders']);
        });
    });

    describe('forget', function () {
        it('removes a registered config', function () {
            $this->registry->register('Items', SyncConfig::items());
            $this->registry->forget('Items');

            expect($this->registry->all())->not->toHaveKey('Items');
        });
    });

    describe('clear', function () {
        it('removes all registered configs', function () {
            $this->registry->register('Items', SyncConfig::items());
            $this->registry->register('Orders', SyncConfig::orders());
            $this->registry->clear();

            expect($this->registry->all())->toBe([]);
        });
    });

    describe('count', function () {
        it('returns count of registered configs', function () {
            expect($this->registry->count())->toBe(0);

            $this->registry->register('Items', SyncConfig::items());

            expect($this->registry->count())->toBe(1);
        });
    });

    describe('isEmpty', function () {
        it('returns true when empty', function () {
            expect($this->registry->isEmpty())->toBeTrue();
        });

        it('returns false when configs registered', function () {
            $this->registry->register('Items', SyncConfig::items());

            expect($this->registry->isEmpty())->toBeFalse();
        });
    });

    describe('summary', function () {
        it('returns summary of registered configs', function () {
            $this->registry->register('Items', SyncConfig::items());

            $summary = $this->registry->summary();

            expect($summary)->toHaveKey('Items');
            expect($summary['Items'])->toHaveKeys([
                'table', 'primaryKey', 'columns', 'syncLines',
            ]);
        });
    });
});
