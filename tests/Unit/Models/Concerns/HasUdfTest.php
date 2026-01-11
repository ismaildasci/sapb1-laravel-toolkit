<?php

declare(strict_types=1);

use SapB1\Toolkit\Models\Concerns\HasUdf;

it('trait exists', function () {
    expect(trait_exists(HasUdf::class))->toBeTrue();
});

it('has getUdf method', function () {
    $methods = get_class_methods(HasUdf::class);

    expect(in_array('getUdf', $methods))->toBeTrue();
});

it('has setUdf method', function () {
    $methods = get_class_methods(HasUdf::class);

    expect(in_array('setUdf', $methods))->toBeTrue();
});

it('has hasUdf method', function () {
    $methods = get_class_methods(HasUdf::class);

    expect(in_array('hasUdf', $methods))->toBeTrue();
});

it('has getUdfs method', function () {
    $methods = get_class_methods(HasUdf::class);

    expect(in_array('getUdfs', $methods))->toBeTrue();
});

it('has getUdfsRaw method', function () {
    $methods = get_class_methods(HasUdf::class);

    expect(in_array('getUdfsRaw', $methods))->toBeTrue();
});

it('has setUdfs method', function () {
    $methods = get_class_methods(HasUdf::class);

    expect(in_array('setUdfs', $methods))->toBeTrue();
});

it('has udfCount method', function () {
    $methods = get_class_methods(HasUdf::class);

    expect(in_array('udfCount', $methods))->toBeTrue();
});

it('has hasAnyUdfs method', function () {
    $methods = get_class_methods(HasUdf::class);

    expect(in_array('hasAnyUdfs', $methods))->toBeTrue();
});

it('has getUdfNames method', function () {
    $methods = get_class_methods(HasUdf::class);

    expect(in_array('getUdfNames', $methods))->toBeTrue();
});

it('has clearUdf method', function () {
    $methods = get_class_methods(HasUdf::class);

    expect(in_array('clearUdf', $methods))->toBeTrue();
});

it('has fillUdfs method', function () {
    $methods = get_class_methods(HasUdf::class);

    expect(in_array('fillUdfs', $methods))->toBeTrue();
});

it('has getDirtyUdfs method', function () {
    $methods = get_class_methods(HasUdf::class);

    expect(in_array('getDirtyUdfs', $methods))->toBeTrue();
});

it('has isUdfDirty method', function () {
    $methods = get_class_methods(HasUdf::class);

    expect(in_array('isUdfDirty', $methods))->toBeTrue();
});

// ==================== CONCRETE IMPLEMENTATION TESTS ====================

// Test with a concrete class that uses the trait
class TestModelWithUdf
{
    use HasUdf;

    /** @var array<string, mixed> */
    protected array $attributes = [];

    /** @var array<string, mixed> */
    protected array $original = [];

    public function getAttribute(string $key): mixed
    {
        return $this->attributes[$key] ?? null;
    }

    public function setAttribute(string $key, mixed $value): void
    {
        $this->attributes[$key] = $value;
    }

    public function hasAttribute(string $key): bool
    {
        return array_key_exists($key, $this->attributes);
    }

    /** @return array<string, mixed> */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /** @return array<string, mixed> */
    public function getDirty(): array
    {
        $dirty = [];
        foreach ($this->attributes as $key => $value) {
            if (! array_key_exists($key, $this->original) || $this->original[$key] !== $value) {
                $dirty[$key] = $value;
            }
        }

        return $dirty;
    }

    /** @param string|array<int, string>|null $attributes */
    public function isDirty(string|array|null $attributes = null): bool
    {
        $dirty = $this->getDirty();

        if ($attributes === null) {
            return count($dirty) > 0;
        }

        if (is_string($attributes)) {
            return array_key_exists($attributes, $dirty);
        }

        foreach ($attributes as $attribute) {
            if (array_key_exists($attribute, $dirty)) {
                return true;
            }
        }

        return false;
    }

    public function syncOriginal(): void
    {
        $this->original = $this->attributes;
    }

    /** @param array<string, mixed> $attributes */
    public function fill(array $attributes): void
    {
        $this->attributes = array_merge($this->attributes, $attributes);
    }
}

it('can get UDF value without prefix', function () {
    $model = new TestModelWithUdf;
    $model->fill(['U_CustomField' => 'test value']);

    expect($model->getUdf('CustomField'))->toBe('test value');
});

it('can get UDF value with prefix', function () {
    $model = new TestModelWithUdf;
    $model->fill(['U_CustomField' => 'test value']);

    expect($model->getUdf('U_CustomField'))->toBe('test value');
});

it('can set UDF value without prefix', function () {
    $model = new TestModelWithUdf;
    $model->setUdf('CustomField', 'test value');

    expect($model->getAttribute('U_CustomField'))->toBe('test value');
});

it('can set UDF value with prefix', function () {
    $model = new TestModelWithUdf;
    $model->setUdf('U_CustomField', 'test value');

    expect($model->getAttribute('U_CustomField'))->toBe('test value');
});

it('can check if UDF exists', function () {
    $model = new TestModelWithUdf;
    $model->fill(['U_CustomField' => 'test value']);

    expect($model->hasUdf('CustomField'))->toBeTrue();
    expect($model->hasUdf('NonExistent'))->toBeFalse();
});

it('can get all UDFs without prefix', function () {
    $model = new TestModelWithUdf;
    $model->fill([
        'DocEntry' => 123,
        'CardCode' => 'C001',
        'U_CustomField' => 'value1',
        'U_AnotherField' => 'value2',
    ]);

    $udfs = $model->getUdfs();

    expect($udfs)->toBe([
        'CustomField' => 'value1',
        'AnotherField' => 'value2',
    ]);
});

it('can get all UDFs with prefix', function () {
    $model = new TestModelWithUdf;
    $model->fill([
        'DocEntry' => 123,
        'U_CustomField' => 'value1',
        'U_AnotherField' => 'value2',
    ]);

    $udfs = $model->getUdfsRaw();

    expect($udfs)->toBe([
        'U_CustomField' => 'value1',
        'U_AnotherField' => 'value2',
    ]);
});

it('can set multiple UDFs at once', function () {
    $model = new TestModelWithUdf;
    $model->setUdfs([
        'CustomField' => 'value1',
        'AnotherField' => 'value2',
    ]);

    expect($model->getAttribute('U_CustomField'))->toBe('value1');
    expect($model->getAttribute('U_AnotherField'))->toBe('value2');
});

it('can count UDFs', function () {
    $model = new TestModelWithUdf;
    $model->fill([
        'DocEntry' => 123,
        'U_Field1' => 'value1',
        'U_Field2' => 'value2',
        'U_Field3' => 'value3',
    ]);

    expect($model->udfCount())->toBe(3);
});

it('can check if has any UDFs', function () {
    $model1 = new TestModelWithUdf;
    $model1->fill(['DocEntry' => 123]);

    $model2 = new TestModelWithUdf;
    $model2->fill(['DocEntry' => 123, 'U_CustomField' => 'value']);

    expect($model1->hasAnyUdfs())->toBeFalse();
    expect($model2->hasAnyUdfs())->toBeTrue();
});

it('can get UDF names', function () {
    $model = new TestModelWithUdf;
    $model->fill([
        'DocEntry' => 123,
        'U_Alpha' => 'a',
        'U_Beta' => 'b',
        'U_Gamma' => 'c',
    ]);

    $names = $model->getUdfNames();

    expect($names)->toBe(['Alpha', 'Beta', 'Gamma']);
});

it('can clear UDF value', function () {
    $model = new TestModelWithUdf;
    $model->fill(['U_CustomField' => 'value']);
    $model->clearUdf('CustomField');

    expect($model->getAttribute('U_CustomField'))->toBeNull();
});

it('can fill UDFs from data array', function () {
    $model = new TestModelWithUdf;

    $data = [
        'DocEntry' => 123,
        'U_CustomField' => 'value1',
        'U_AnotherField' => 'value2',
        'U_ThirdField' => 'value3',
    ];

    $model->fillUdfs($data, ['CustomField', 'AnotherField']);

    expect($model->getAttribute('U_CustomField'))->toBe('value1');
    expect($model->getAttribute('U_AnotherField'))->toBe('value2');
    expect($model->getAttribute('U_ThirdField'))->toBeNull(); // Not in list
});

it('can get dirty UDFs', function () {
    $model = new TestModelWithUdf;
    $model->fill([
        'DocEntry' => 123,
        'U_CustomField' => 'original',
    ]);
    $model->syncOriginal();

    $model->setUdf('CustomField', 'changed');
    $model->setUdf('NewField', 'new value');

    $dirty = $model->getDirtyUdfs();

    expect($dirty)->toBe([
        'CustomField' => 'changed',
        'NewField' => 'new value',
    ]);
});

it('can check if specific UDF is dirty', function () {
    $model = new TestModelWithUdf;
    $model->fill([
        'U_Field1' => 'original1',
        'U_Field2' => 'original2',
    ]);
    $model->syncOriginal();

    $model->setUdf('Field1', 'changed');

    expect($model->isUdfDirty('Field1'))->toBeTrue();
    expect($model->isUdfDirty('Field2'))->toBeFalse();
});
