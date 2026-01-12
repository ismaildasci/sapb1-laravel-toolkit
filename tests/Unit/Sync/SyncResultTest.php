<?php

declare(strict_types=1);

use SapB1\Toolkit\Sync\SyncResult;

describe('SyncResult', function () {
    describe('constructor', function () {
        it('creates result with default values', function () {
            $result = new SyncResult(entity: 'Items');

            expect($result->entity)->toBe('Items');
            expect($result->created)->toBe(0);
            expect($result->updated)->toBe(0);
            expect($result->deleted)->toBe(0);
            expect($result->failed)->toBe(0);
            expect($result->duration)->toBe(0.0);
            expect($result->success)->toBeTrue();
            expect($result->error)->toBeNull();
        });

        it('creates result with specified values', function () {
            $result = new SyncResult(
                entity: 'Items',
                created: 10,
                updated: 20,
                deleted: 5,
                duration: 1.5,
            );

            expect($result->created)->toBe(10);
            expect($result->updated)->toBe(20);
            expect($result->deleted)->toBe(5);
            expect($result->duration)->toBe(1.5);
        });

        it('sets completedAt timestamp', function () {
            $result = new SyncResult(entity: 'Items');

            expect($result->completedAt)->toBeInstanceOf(DateTimeImmutable::class);
        });
    });

    describe('success', function () {
        it('creates successful result', function () {
            $result = SyncResult::success(
                entity: 'Items',
                created: 100,
                updated: 50,
                duration: 2.5,
            );

            expect($result->success)->toBeTrue();
            expect($result->created)->toBe(100);
            expect($result->updated)->toBe(50);
            expect($result->duration)->toBe(2.5);
            expect($result->error)->toBeNull();
        });
    });

    describe('failed', function () {
        it('creates failed result', function () {
            $result = SyncResult::failed(
                entity: 'Items',
                error: 'Connection failed',
                duration: 0.5,
            );

            expect($result->success)->toBeFalse();
            expect($result->error)->toBe('Connection failed');
            expect($result->duration)->toBe(0.5);
        });
    });

    describe('total', function () {
        it('calculates total affected records', function () {
            $result = new SyncResult(
                entity: 'Items',
                created: 10,
                updated: 20,
                deleted: 5,
            );

            expect($result->total())->toBe(35);
        });
    });

    describe('synced', function () {
        it('calculates created plus updated', function () {
            $result = new SyncResult(
                entity: 'Items',
                created: 10,
                updated: 20,
                deleted: 5,
            );

            expect($result->synced())->toBe(30);
        });
    });

    describe('hasChanges', function () {
        it('returns true when records were affected', function () {
            $result = new SyncResult(entity: 'Items', created: 1);

            expect($result->hasChanges())->toBeTrue();
        });

        it('returns false when no records were affected', function () {
            $result = new SyncResult(entity: 'Items');

            expect($result->hasChanges())->toBeFalse();
        });
    });

    describe('hasFailed', function () {
        it('returns true for failed results', function () {
            $result = SyncResult::failed('Items', 'Error');

            expect($result->hasFailed())->toBeTrue();
        });

        it('returns false for successful results', function () {
            $result = SyncResult::success('Items');

            expect($result->hasFailed())->toBeFalse();
        });
    });

    describe('withDeleted', function () {
        it('creates copy with deleted count', function () {
            $result = new SyncResult(entity: 'Items', created: 10);
            $withDeleted = $result->withDeleted(5);

            expect($withDeleted->deleted)->toBe(5);
            expect($withDeleted->created)->toBe(10);
            expect($result->deleted)->toBe(0);
        });
    });

    describe('withMetadata', function () {
        it('creates copy with merged metadata', function () {
            $result = new SyncResult(
                entity: 'Items',
                metadata: ['source' => 'test'],
            );

            $withMetadata = $result->withMetadata(['extra' => 'value']);

            expect($withMetadata->metadata)->toBe([
                'source' => 'test',
                'extra' => 'value',
            ]);
        });
    });

    describe('summary', function () {
        it('returns summary for successful sync', function () {
            $result = new SyncResult(
                entity: 'Items',
                created: 10,
                updated: 20,
                duration: 1.5,
            );

            expect($result->summary())->toBe('10 created, 20 updated in 1.50s');
        });

        it('returns summary with deleted', function () {
            $result = new SyncResult(
                entity: 'Items',
                created: 5,
                updated: 10,
                deleted: 3,
                duration: 2.0,
            );

            expect($result->summary())->toBe('5 created, 10 updated, 3 deleted in 2.00s');
        });

        it('returns no changes message', function () {
            $result = new SyncResult(entity: 'Items');

            expect($result->summary())->toBe('No changes');
        });

        it('returns error message for failed sync', function () {
            $result = SyncResult::failed('Items', 'Connection error');

            expect($result->summary())->toBe('Failed: Connection error');
        });
    });

    describe('toArray', function () {
        it('converts result to array', function () {
            $result = new SyncResult(
                entity: 'Items',
                created: 10,
                updated: 20,
                deleted: 5,
                duration: 1.5,
            );

            $array = $result->toArray();

            expect($array)->toHaveKeys([
                'entity', 'success', 'created', 'updated', 'deleted',
                'failed', 'total', 'duration', 'error', 'completed_at', 'metadata',
            ]);

            expect($array['entity'])->toBe('Items');
            expect($array['success'])->toBeTrue();
            expect($array['total'])->toBe(35);
        });
    });
});
