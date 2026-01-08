<?php

declare(strict_types=1);

use SapB1\Toolkit\Services\SemanticQueryService;
use SapB1\Toolkit\Services\SemanticQueryServiceBuilder;

it('can be instantiated', function () {
    $service = new SemanticQueryService;

    expect($service)->toBeInstanceOf(SemanticQueryService::class);
});

it('can set connection', function () {
    $service = new SemanticQueryService;
    $result = $service->connection('secondary');

    expect($result)->toBeInstanceOf(SemanticQueryService::class);
});

it('has query method that returns builder', function () {
    $service = new SemanticQueryService;

    expect(method_exists($service, 'query'))->toBeTrue();
});

it('has getAvailableQueries method', function () {
    $service = new SemanticQueryService;

    expect(method_exists($service, 'getAvailableQueries'))->toBeTrue();
});

it('has execute method for direct execution', function () {
    $service = new SemanticQueryService;

    expect(method_exists($service, 'execute'))->toBeTrue();
});

it('has executeWithDateRange method', function () {
    $service = new SemanticQueryService;

    expect(method_exists($service, 'executeWithDateRange'))->toBeTrue();
});

it('has aggregate method', function () {
    $service = new SemanticQueryService;

    expect(method_exists($service, 'aggregate'))->toBeTrue();
});

describe('SemanticQueryServiceBuilder', function () {
    // Ensure SemanticQueryService file is loaded (SemanticQueryServiceBuilder is in the same file)
    beforeEach(function () {
        class_exists(SemanticQueryService::class);
    });

    it('has dimensions method', function () {
        expect(method_exists(SemanticQueryServiceBuilder::class, 'dimensions'))->toBeTrue();
    });

    it('has measures method', function () {
        expect(method_exists(SemanticQueryServiceBuilder::class, 'measures'))->toBeTrue();
    });

    it('has select method', function () {
        expect(method_exists(SemanticQueryServiceBuilder::class, 'select'))->toBeTrue();
    });

    it('has filter method', function () {
        expect(method_exists(SemanticQueryServiceBuilder::class, 'filter'))->toBeTrue();
    });

    it('has where method', function () {
        expect(method_exists(SemanticQueryServiceBuilder::class, 'where'))->toBeTrue();
    });

    it('has whereBetween method', function () {
        expect(method_exists(SemanticQueryServiceBuilder::class, 'whereBetween'))->toBeTrue();
    });

    it('has whereIn method', function () {
        expect(method_exists(SemanticQueryServiceBuilder::class, 'whereIn'))->toBeTrue();
    });

    it('has param method', function () {
        expect(method_exists(SemanticQueryServiceBuilder::class, 'param'))->toBeTrue();
    });

    it('has params method', function () {
        expect(method_exists(SemanticQueryServiceBuilder::class, 'params'))->toBeTrue();
    });

    it('has orderBy method', function () {
        expect(method_exists(SemanticQueryServiceBuilder::class, 'orderBy'))->toBeTrue();
    });

    it('has orderByDesc method', function () {
        expect(method_exists(SemanticQueryServiceBuilder::class, 'orderByDesc'))->toBeTrue();
    });

    it('has limit method', function () {
        expect(method_exists(SemanticQueryServiceBuilder::class, 'limit'))->toBeTrue();
    });

    it('has top method alias', function () {
        expect(method_exists(SemanticQueryServiceBuilder::class, 'top'))->toBeTrue();
    });

    it('has offset method', function () {
        expect(method_exists(SemanticQueryServiceBuilder::class, 'offset'))->toBeTrue();
    });

    it('has skip method alias', function () {
        expect(method_exists(SemanticQueryServiceBuilder::class, 'skip'))->toBeTrue();
    });

    it('has paginate method', function () {
        expect(method_exists(SemanticQueryServiceBuilder::class, 'paginate'))->toBeTrue();
    });

    it('has get method', function () {
        expect(method_exists(SemanticQueryServiceBuilder::class, 'get'))->toBeTrue();
    });

    it('has first method', function () {
        expect(method_exists(SemanticQueryServiceBuilder::class, 'first'))->toBeTrue();
    });

    it('has aggregate method', function () {
        expect(method_exists(SemanticQueryServiceBuilder::class, 'aggregate'))->toBeTrue();
    });

    it('has collect method for Laravel collections', function () {
        expect(method_exists(SemanticQueryServiceBuilder::class, 'collect'))->toBeTrue();
    });

    it('has pluck method', function () {
        expect(method_exists(SemanticQueryServiceBuilder::class, 'pluck'))->toBeTrue();
    });

    it('has sum method', function () {
        expect(method_exists(SemanticQueryServiceBuilder::class, 'sum'))->toBeTrue();
    });

    it('has avg method', function () {
        expect(method_exists(SemanticQueryServiceBuilder::class, 'avg'))->toBeTrue();
    });

    it('has max method', function () {
        expect(method_exists(SemanticQueryServiceBuilder::class, 'max'))->toBeTrue();
    });

    it('has min method', function () {
        expect(method_exists(SemanticQueryServiceBuilder::class, 'min'))->toBeTrue();
    });

    it('has groupBy method', function () {
        expect(method_exists(SemanticQueryServiceBuilder::class, 'groupBy'))->toBeTrue();
    });

    it('has each method', function () {
        expect(method_exists(SemanticQueryServiceBuilder::class, 'each'))->toBeTrue();
    });

    it('has map method', function () {
        expect(method_exists(SemanticQueryServiceBuilder::class, 'map'))->toBeTrue();
    });

    it('has filterResults method for collections', function () {
        expect(method_exists(SemanticQueryServiceBuilder::class, 'filterResults'))->toBeTrue();
    });

    it('has getClient method', function () {
        expect(method_exists(SemanticQueryServiceBuilder::class, 'getClient'))->toBeTrue();
    });
});
