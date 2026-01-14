<?php

declare(strict_types=1);

use Carbon\Carbon;
use SapB1\Toolkit\Models\Concerns\HasUdf;
use SapB1\Toolkit\Models\Concerns\HasUdfMapping;

// Create a test model for testing
class TestUdfMappingModel
{
    use HasUdf;
    use HasUdfMapping;

    /** @var array<string, mixed> */
    protected array $attributes = [];

    /** @var array<string, mixed> */
    protected array $original = [];

    /** @var array<string, string|array{field: string, type?: string}> */
    protected array $udfMappings = [
        'deliveryDate' => ['field' => 'U_DeliveryDate', 'type' => 'date'],
        'customerId' => ['field' => 'U_CustomerID', 'type' => 'integer'],
        'priority' => 'U_Priority',
        'isUrgent' => ['field' => 'U_Urgent', 'type' => 'boolean'],
        'metadata' => ['field' => 'U_Metadata', 'type' => 'json'],
        'price' => ['field' => 'U_Price', 'type' => 'decimal'],
    ];

    public function getAttribute(string $key): mixed
    {
        return $this->attributes[$key] ?? null;
    }

    public function setAttribute(string $key, mixed $value): static
    {
        $this->attributes[$key] = $value;

        return $this;
    }

    public function hasAttribute(string $key): bool
    {
        return array_key_exists($key, $this->attributes);
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function getDirty(): array
    {
        $dirty = [];
        foreach ($this->attributes as $key => $value) {
            if (! isset($this->original[$key]) || $this->original[$key] !== $value) {
                $dirty[$key] = $value;
            }
        }

        return $dirty;
    }

    public function isDirty(string|array|null $attributes = null): bool
    {
        if ($attributes === null) {
            return count($this->getDirty()) > 0;
        }
        if (is_string($attributes)) {
            $attributes = [$attributes];
        }
        $dirty = $this->getDirty();
        foreach ($attributes as $attr) {
            if (array_key_exists($attr, $dirty)) {
                return true;
            }
        }

        return false;
    }

    public function fill(array $data): static
    {
        foreach ($data as $key => $value) {
            $this->setAttribute($key, $value);
        }

        return $this;
    }
}

beforeEach(function () {
    Carbon::setTestNow('2026-01-14 12:00:00');
});

afterEach(function () {
    Carbon::setTestNow();
});

describe('HasUdfMapping', function () {
    it('checks if an alias is a mapped UDF', function () {
        $model = new TestUdfMappingModel;

        expect($model->isMappedUdf('deliveryDate'))->toBeTrue();
        expect($model->isMappedUdf('customerId'))->toBeTrue();
        expect($model->isMappedUdf('priority'))->toBeTrue();
        expect($model->isMappedUdf('unknownField'))->toBeFalse();
    });

    it('gets the field name for an alias', function () {
        $model = new TestUdfMappingModel;

        expect($model->getMappedUdfField('deliveryDate'))->toBe('U_DeliveryDate');
        expect($model->getMappedUdfField('priority'))->toBe('U_Priority');
        expect($model->getMappedUdfField('unknown'))->toBeNull();
    });

    it('gets the type for a mapped UDF', function () {
        $model = new TestUdfMappingModel;

        expect($model->getMappedUdfType('deliveryDate'))->toBe('date');
        expect($model->getMappedUdfType('customerId'))->toBe('integer');
        expect($model->getMappedUdfType('priority'))->toBeNull();
        expect($model->getMappedUdfType('isUrgent'))->toBe('boolean');
    });

    it('gets and sets mapped UDF values', function () {
        $model = new TestUdfMappingModel;

        $model->setMappedUdf('priority', 'high');

        expect($model->getMappedUdf('priority'))->toBe('high');
        expect($model->getUdf('U_Priority'))->toBe('high');
    });

    it('casts integer values', function () {
        $model = new TestUdfMappingModel;
        $model->setUdf('U_CustomerID', '12345');

        $value = $model->getMappedUdf('customerId');

        expect($value)->toBe(12345);
        expect($value)->toBeInt();
    });

    it('casts boolean values', function () {
        $model = new TestUdfMappingModel;

        // Setting true
        $model->setMappedUdf('isUrgent', true);
        expect($model->getUdf('U_Urgent'))->toBe('Y');

        // Getting as boolean
        $model->setUdf('U_Urgent', 'Y');
        expect($model->getMappedUdf('isUrgent'))->toBeTrue();

        $model->setUdf('U_Urgent', 'N');
        expect($model->getMappedUdf('isUrgent'))->toBeFalse();
    });

    it('casts date values', function () {
        $model = new TestUdfMappingModel;

        // Set with Carbon
        $model->setMappedUdf('deliveryDate', Carbon::parse('2026-03-15'));
        expect($model->getUdf('U_DeliveryDate'))->toBe('2026-03-15');

        // Get as Carbon
        $model->setUdf('U_DeliveryDate', '2026-03-15');
        $value = $model->getMappedUdf('deliveryDate');
        expect($value)->toBeInstanceOf(Carbon::class);
        expect($value->format('Y-m-d'))->toBe('2026-03-15');
    });

    it('casts decimal values', function () {
        $model = new TestUdfMappingModel;
        $model->setUdf('U_Price', '123.45');

        $value = $model->getMappedUdf('price');

        expect($value)->toBe(123.45);
        expect($value)->toBeFloat();
    });

    it('casts JSON values', function () {
        $model = new TestUdfMappingModel;

        // Set as array
        $data = ['key' => 'value', 'count' => 10];
        $model->setMappedUdf('metadata', $data);
        expect($model->getUdf('U_Metadata'))->toBe(json_encode($data));

        // Get as array
        $model->setUdf('U_Metadata', '{"key":"value","count":10}');
        $value = $model->getMappedUdf('metadata');
        expect($value)->toBe(['key' => 'value', 'count' => 10]);
    });

    it('handles null values', function () {
        $model = new TestUdfMappingModel;

        expect($model->getMappedUdf('deliveryDate'))->toBeNull();
        expect($model->getMappedUdf('customerId'))->toBeNull();
    });

    it('throws exception for unknown alias when setting', function () {
        $model = new TestUdfMappingModel;

        expect(fn () => $model->setMappedUdf('unknownField', 'value'))
            ->toThrow(InvalidArgumentException::class, 'Unknown UDF mapping: unknownField');
    });

    it('gets all mapped UDF values', function () {
        $model = new TestUdfMappingModel;
        $model->setUdf('U_Priority', 'high');
        $model->setUdf('U_CustomerID', '100');

        $mapped = $model->getMappedUdfs();

        expect($mapped)->toHaveKey('priority', 'high');
        expect($mapped)->toHaveKey('customerId', 100);
        expect($mapped)->toHaveKey('deliveryDate', null);
    });

    it('sets multiple mapped UDF values', function () {
        $model = new TestUdfMappingModel;

        $model->setMappedUdfs([
            'priority' => 'low',
            'customerId' => 200,
        ]);

        expect($model->getMappedUdf('priority'))->toBe('low');
        expect($model->getMappedUdf('customerId'))->toBe(200);
    });

    it('fills mapped UDFs from data', function () {
        $model = new TestUdfMappingModel;

        $model->fillMapped([
            'priority' => 'medium',
            'customerId' => 300,
            'otherField' => 'ignored',
        ]);

        expect($model->getMappedUdf('priority'))->toBe('medium');
        expect($model->getMappedUdf('customerId'))->toBe(300);
    });

    it('gets UDF aliases', function () {
        $model = new TestUdfMappingModel;

        $aliases = $model->getUdfAliases();

        expect($aliases)->toContain('deliveryDate', 'customerId', 'priority', 'isUrgent', 'metadata', 'price');
    });

    it('gets reverse mappings', function () {
        $model = new TestUdfMappingModel;

        $reverse = $model->getReverseUdfMappings();

        expect($reverse['U_DeliveryDate'])->toBe('deliveryDate');
        expect($reverse['U_Priority'])->toBe('priority');
    });
});
