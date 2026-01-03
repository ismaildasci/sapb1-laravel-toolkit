<?php

declare(strict_types=1);

use SapB1\Toolkit\Models\QueryBuilder;
use SapB1\Toolkit\Models\SapB1Model;

beforeEach(function () {
    $this->model = new class extends SapB1Model
    {
        protected static string $entity = 'TestEntity';

        protected static string $primaryKey = 'DocEntry';
    };
});

describe('Field Name Validation', function () {
    it('allows valid field names', function () {
        $validFieldNames = [
            'DocEntry',
            'CardCode',
            'DocumentLines',
            '_private',
            'Field123',
            'Order_Number',
            'Parent.Child',
            'Level1.Level2.Level3',
        ];

        foreach ($validFieldNames as $field) {
            $builder = new QueryBuilder($this->model);
            expect(fn () => $builder->where($field, 'value'))->not->toThrow(InvalidArgumentException::class);
        }
    });

    it('rejects field names with OData injection attempts', function () {
        $invalidFieldNames = [
            "DocEntry' eq 1 or '1'='1",       // SQL/OData injection
            'DocEntry eq 1',                   // Space in field name
            "DocEntry' or '1'='1",             // Quote injection
            'DocEntry); DROP TABLE Users;--', // SQL injection
            'DocEntry<script>',                // XSS attempt
            '1Field',                          // Starting with number
            '-field',                          // Starting with hyphen
            'field-name',                      // Contains hyphen
            'field name',                      // Contains space
            "field'name",                      // Contains quote
            'field"name',                      // Contains double quote
            'field;name',                      // Contains semicolon
            'field(name)',                     // Contains parentheses
            '',                                // Empty string
        ];

        foreach ($invalidFieldNames as $field) {
            $builder = new QueryBuilder($this->model);
            expect(fn () => $builder->where($field, 'value'))->toThrow(InvalidArgumentException::class);
        }
    });

    it('validates field names in where method', function () {
        $builder = new QueryBuilder($this->model);

        expect(fn () => $builder->where("DocEntry' or '1'='1", 'value'))
            ->toThrow(InvalidArgumentException::class);
    });

    it('validates field names in orWhere method', function () {
        $builder = new QueryBuilder($this->model);

        expect(fn () => $builder->orWhere("CardCode'; DROP TABLE--", 'value'))
            ->toThrow(InvalidArgumentException::class);
    });

    it('validates field names in whereIn method', function () {
        $builder = new QueryBuilder($this->model);

        expect(fn () => $builder->whereIn('invalid field', ['A', 'B']))
            ->toThrow(InvalidArgumentException::class);
    });

    it('validates field names in whereBetween method', function () {
        $builder = new QueryBuilder($this->model);

        expect(fn () => $builder->whereBetween('field<>injection', [1, 10]))
            ->toThrow(InvalidArgumentException::class);
    });

    it('validates field names in whereNull method', function () {
        $builder = new QueryBuilder($this->model);

        expect(fn () => $builder->whereNull('field OR 1=1'))
            ->toThrow(InvalidArgumentException::class);
    });

    it('validates field names in whereNotNull method', function () {
        $builder = new QueryBuilder($this->model);

        expect(fn () => $builder->whereNotNull("field'; --"))
            ->toThrow(InvalidArgumentException::class);
    });

    it('validates field names in orderBy method', function () {
        $builder = new QueryBuilder($this->model);

        expect(fn () => $builder->orderBy('field DESC; DROP TABLE Users'))
            ->toThrow(InvalidArgumentException::class);
    });

    it('validates field names in select method', function () {
        $builder = new QueryBuilder($this->model);

        expect(fn () => $builder->select(['ValidField', 'invalid field']))
            ->toThrow(InvalidArgumentException::class);
    });

    it('allows nested property access with dots', function () {
        $builder = new QueryBuilder($this->model);

        expect(fn () => $builder->where('BusinessPartner.CardCode', 'C001'))
            ->not->toThrow(InvalidArgumentException::class);

        expect(fn () => $builder->orderBy('Level1.Level2.Field'))
            ->not->toThrow(InvalidArgumentException::class);
    });

    it('allows underscore-prefixed field names', function () {
        $builder = new QueryBuilder($this->model);

        expect(fn () => $builder->where('_internalField', 'value'))
            ->not->toThrow(InvalidArgumentException::class);
    });
});
