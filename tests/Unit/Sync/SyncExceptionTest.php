<?php

declare(strict_types=1);

use SapB1\Toolkit\Sync\Exceptions\SyncException;

describe('SyncException', function () {
    describe('entityNotConfigured', function () {
        it('creates exception with entity name', function () {
            $exception = SyncException::entityNotConfigured('Unknown');

            expect($exception)->toBeInstanceOf(SyncException::class);
            expect($exception->getMessage())->toContain('Unknown');
            expect($exception->getMessage())->toContain('not configured');
        });
    });

    describe('tableNotFound', function () {
        it('creates exception with table name', function () {
            $exception = SyncException::tableNotFound('sap_items');

            expect($exception->getMessage())->toContain('sap_items');
            expect($exception->getMessage())->toContain('does not exist');
        });
    });

    describe('migrationAlreadyExists', function () {
        it('creates exception with entity and path', function () {
            $exception = SyncException::migrationAlreadyExists('Items', '/path/to/migration.php');

            expect($exception->getMessage())->toContain('Items');
            expect($exception->getMessage())->toContain('/path/to/migration.php');
        });
    });

    describe('connectionFailed', function () {
        it('creates exception with entity and reason', function () {
            $exception = SyncException::connectionFailed('Items', 'Timeout');

            expect($exception->getMessage())->toContain('Items');
            expect($exception->getMessage())->toContain('Timeout');
        });
    });

    describe('syncFailed', function () {
        it('creates exception with entity and reason', function () {
            $exception = SyncException::syncFailed('Items', 'Database error');

            expect($exception->getMessage())->toContain('Items');
            expect($exception->getMessage())->toContain('Database error');
        });
    });

    describe('upsertFailed', function () {
        it('creates exception with table and reason', function () {
            $exception = SyncException::upsertFailed('sap_items', 'Duplicate key');

            expect($exception->getMessage())->toContain('sap_items');
            expect($exception->getMessage())->toContain('Duplicate key');
        });
    });

    describe('deleteDetectionFailed', function () {
        it('creates exception with entity and reason', function () {
            $exception = SyncException::deleteDetectionFailed('Items', 'Query timeout');

            expect($exception->getMessage())->toContain('Items');
            expect($exception->getMessage())->toContain('Query timeout');
        });
    });

    describe('invalidConfiguration', function () {
        it('creates exception with entity and reason', function () {
            $exception = SyncException::invalidConfiguration('Items', 'Missing primary key');

            expect($exception->getMessage())->toContain('Items');
            expect($exception->getMessage())->toContain('Missing primary key');
        });
    });

    describe('stubNotFound', function () {
        it('creates exception with entity name', function () {
            $exception = SyncException::stubNotFound('Unknown');

            expect($exception->getMessage())->toContain('Unknown');
            expect($exception->getMessage())->toContain('not found');
        });
    });

    describe('metadataTableMissing', function () {
        it('creates exception with table name', function () {
            $exception = SyncException::metadataTableMissing();

            expect($exception->getMessage())->toContain('sapb1_sync_metadata');
        });
    });
});
