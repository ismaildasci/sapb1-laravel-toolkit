<?php

declare(strict_types=1);

use SapB1\Toolkit\Services\SqlQueryService;
use SapB1\Toolkit\Services\SqlQueryServiceBuilder;

it('can be instantiated', function () {
    $service = new SqlQueryService;

    expect($service)->toBeInstanceOf(SqlQueryService::class);
});

it('can set connection', function () {
    $service = new SqlQueryService;
    $result = $service->connection('secondary');

    expect($result)->toBeInstanceOf(SqlQueryService::class);
});

it('has query method that returns builder', function () {
    $service = new SqlQueryService;

    expect(method_exists($service, 'query'))->toBeTrue();
});

it('has execute method for direct execution', function () {
    $service = new SqlQueryService;

    expect(method_exists($service, 'execute'))->toBeTrue();
});

it('has first method for single result', function () {
    $service = new SqlQueryService;

    expect(method_exists($service, 'first'))->toBeTrue();
});

it('has count method', function () {
    $service = new SqlQueryService;

    expect(method_exists($service, 'count'))->toBeTrue();
});

it('has exists method', function () {
    $service = new SqlQueryService;

    expect(method_exists($service, 'exists'))->toBeTrue();
});

it('has executeWithDateRange method for reports', function () {
    $service = new SqlQueryService;

    expect(method_exists($service, 'executeWithDateRange'))->toBeTrue();
});

describe('SqlQueryServiceBuilder', function () {
    // Ensure SqlQueryService file is loaded (SqlQueryServiceBuilder is in the same file)
    beforeEach(function () {
        class_exists(SqlQueryService::class);
    });

    it('has param method', function () {
        expect(method_exists(SqlQueryServiceBuilder::class, 'param'))->toBeTrue();
    });

    it('has params method', function () {
        expect(method_exists(SqlQueryServiceBuilder::class, 'params'))->toBeTrue();
    });

    it('has limit method', function () {
        expect(method_exists(SqlQueryServiceBuilder::class, 'limit'))->toBeTrue();
    });

    it('has top method alias', function () {
        expect(method_exists(SqlQueryServiceBuilder::class, 'top'))->toBeTrue();
    });

    it('has offset method', function () {
        expect(method_exists(SqlQueryServiceBuilder::class, 'offset'))->toBeTrue();
    });

    it('has skip method alias', function () {
        expect(method_exists(SqlQueryServiceBuilder::class, 'skip'))->toBeTrue();
    });

    it('has paginate method', function () {
        expect(method_exists(SqlQueryServiceBuilder::class, 'paginate'))->toBeTrue();
    });

    it('has get method', function () {
        expect(method_exists(SqlQueryServiceBuilder::class, 'get'))->toBeTrue();
    });

    it('has first method', function () {
        expect(method_exists(SqlQueryServiceBuilder::class, 'first'))->toBeTrue();
    });

    it('has count method', function () {
        expect(method_exists(SqlQueryServiceBuilder::class, 'count'))->toBeTrue();
    });

    it('has exists method', function () {
        expect(method_exists(SqlQueryServiceBuilder::class, 'exists'))->toBeTrue();
    });

    it('has collect method for Laravel collections', function () {
        expect(method_exists(SqlQueryServiceBuilder::class, 'collect'))->toBeTrue();
    });

    it('has pluck method', function () {
        expect(method_exists(SqlQueryServiceBuilder::class, 'pluck'))->toBeTrue();
    });

    it('has sum method', function () {
        expect(method_exists(SqlQueryServiceBuilder::class, 'sum'))->toBeTrue();
    });

    it('has avg method', function () {
        expect(method_exists(SqlQueryServiceBuilder::class, 'avg'))->toBeTrue();
    });

    it('has max method', function () {
        expect(method_exists(SqlQueryServiceBuilder::class, 'max'))->toBeTrue();
    });

    it('has min method', function () {
        expect(method_exists(SqlQueryServiceBuilder::class, 'min'))->toBeTrue();
    });

    it('has groupBy method', function () {
        expect(method_exists(SqlQueryServiceBuilder::class, 'groupBy'))->toBeTrue();
    });

    it('has each method', function () {
        expect(method_exists(SqlQueryServiceBuilder::class, 'each'))->toBeTrue();
    });

    it('has map method', function () {
        expect(method_exists(SqlQueryServiceBuilder::class, 'map'))->toBeTrue();
    });

    it('has filter method', function () {
        expect(method_exists(SqlQueryServiceBuilder::class, 'filter'))->toBeTrue();
    });
});
