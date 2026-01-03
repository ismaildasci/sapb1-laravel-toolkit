<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Models;

use JsonSerializable;
use SapB1\Facades\SapB1;
use SapB1\Toolkit\Models\Concerns\HasAttributes;
use SapB1\Toolkit\Models\Concerns\HasCasting;
use SapB1\Toolkit\Models\Concerns\HasDirtyTracking;
use SapB1\Toolkit\Models\Concerns\HasEvents;
use SapB1\Toolkit\Models\Concerns\HasQueryBuilder;
use SapB1\Toolkit\Models\Concerns\HasRelationships;

/**
 * Base model class for SAP B1 entities.
 *
 * Provides ORM-like functionality adapted for SAP B1 Service Layer REST API.
 *
 * @phpstan-consistent-constructor
 */
abstract class SapB1Model implements JsonSerializable
{
    use HasAttributes;
    use HasCasting;
    use HasDirtyTracking;
    use HasEvents;
    use HasQueryBuilder;
    use HasRelationships;

    /**
     * The SAP B1 entity name (e.g., 'Orders', 'BusinessPartners').
     */
    protected static string $entity = '';

    /**
     * The primary key field.
     */
    protected static string $primaryKey = 'DocEntry';

    /**
     * The connection name.
     */
    protected static string $connection = 'default';

    /**
     * Indicates if the model exists in SAP B1.
     */
    public bool $exists = false;

    /**
     * Create a new model instance.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->bootIfNotBooted();
        $this->fill($attributes);
    }

    /**
     * Boot the model if not already booted.
     */
    protected function bootIfNotBooted(): void
    {
        static $booted = [];
        $class = static::class;

        if (! isset($booted[$class])) {
            $booted[$class] = true;
            static::bootHasEvents();
        }
    }

    /**
     * Get the SAP B1 entity name.
     */
    public static function getEntity(): string
    {
        return static::$entity;
    }

    /**
     * Get the primary key name.
     */
    public function getKeyName(): string
    {
        return static::$primaryKey;
    }

    /**
     * Get the primary key value.
     */
    public function getKey(): int|string|null
    {
        return $this->getAttribute($this->getKeyName());
    }

    /**
     * Get the connection name.
     */
    public static function getConnection(): string
    {
        return static::$connection;
    }

    /**
     * Set the connection name.
     */
    public function setConnection(string $connection): static
    {
        static::$connection = $connection;

        return $this;
    }

    /**
     * Create a new model instance.
     *
     * @param  array<string, mixed>  $attributes
     */
    public static function create(array $attributes): static
    {
        $model = new static($attributes);
        $model->save();

        return $model;
    }

    /**
     * First or create a model.
     *
     * @param  array<string, mixed>  $attributes
     * @param  array<string, mixed>  $values
     */
    public static function firstOrCreate(array $attributes, array $values = []): static
    {
        $query = static::query();

        foreach ($attributes as $key => $value) {
            $query->where($key, $value);
        }

        /** @var static|null $model */
        $model = $query->first();

        if ($model !== null) {
            return $model;
        }

        return static::create(array_merge($attributes, $values));
    }

    /**
     * Update or create a model.
     *
     * @param  array<string, mixed>  $attributes
     * @param  array<string, mixed>  $values
     */
    public static function updateOrCreate(array $attributes, array $values = []): static
    {
        $query = static::query();

        foreach ($attributes as $key => $value) {
            $query->where($key, $value);
        }

        /** @var static|null $model */
        $model = $query->first();

        if ($model !== null) {
            $model->fill($values);
            $model->save();

            return $model;
        }

        return static::create(array_merge($attributes, $values));
    }

    /**
     * Save the model to SAP B1.
     */
    public function save(): bool
    {
        // Fire saving event
        if (! $this->fireEvent('saving')) {
            return false;
        }

        $result = $this->exists ? $this->performUpdate() : $this->performInsert();

        if ($result) {
            $this->fireEvent('saved');
        }

        return $result;
    }

    /**
     * Perform an insert operation.
     */
    protected function performInsert(): bool
    {
        // Fire creating event
        if (! $this->fireEvent('creating')) {
            return false;
        }

        $client = $this->getClient();
        $data = $this->prepareForSapInsert();

        try {
            $response = $client->service(static::$entity)->create($data);

            // Update model with response data
            if (is_array($response)) {
                $this->setRawAttributes($response, true);
            }

            $this->exists = true;

            $this->fireEvent('created');

            return true;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Perform an update operation.
     */
    protected function performUpdate(): bool
    {
        // Only update if there are changes
        if (! $this->isDirty()) {
            return true;
        }

        // Fire updating event
        if (! $this->fireEvent('updating')) {
            return false;
        }

        $client = $this->getClient();
        $key = $this->getKey();

        if ($key === null) {
            throw new \RuntimeException('Cannot update model without primary key');
        }

        $data = $this->getDirtyForSap();

        try {
            $client->service(static::$entity)->update($key, $data);

            // Sync changes
            $this->syncChanges();

            $this->fireEvent('updated');

            return true;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Update the model with given attributes.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function update(array $attributes = []): bool
    {
        if (! $this->exists) {
            return false;
        }

        $this->fill($attributes);

        return $this->save();
    }

    /**
     * Delete the model from SAP B1.
     */
    public function delete(): bool
    {
        if (! $this->exists) {
            return false;
        }

        // Fire deleting event
        if (! $this->fireEvent('deleting')) {
            return false;
        }

        $client = $this->getClient();
        $key = $this->getKey();

        if ($key === null) {
            throw new \RuntimeException('Cannot delete model without primary key');
        }

        try {
            $client->service(static::$entity)->delete($key);

            $this->exists = false;

            $this->fireEvent('deleted');

            return true;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Refresh the model from SAP B1.
     */
    public function refresh(): static
    {
        if (! $this->exists) {
            return $this;
        }

        $key = $this->getKey();

        if ($key === null) {
            return $this;
        }

        $fresh = static::find($key);

        if ($fresh !== null) {
            $this->setRawAttributes($fresh->getAttributes(), true);
            $this->setRelations([]);
        }

        return $this;
    }

    /**
     * Get a fresh instance from SAP B1.
     */
    public function fresh(): ?static
    {
        if (! $this->exists) {
            return null;
        }

        $key = $this->getKey();

        if ($key === null) {
            return null;
        }

        return static::find($key);
    }

    /**
     * Prepare data for SAP B1 insert.
     *
     * @return array<string, mixed>
     */
    protected function prepareForSapInsert(): array
    {
        $data = [];

        foreach ($this->attributes as $key => $value) {
            $sapKey = ucfirst($key);
            $data[$sapKey] = $this->prepareValueForSap($key, $value);
        }

        return $data;
    }

    /**
     * Fire a model event.
     *
     * @internal Used by QueryBuilder to fire events during hydration
     */
    public function fireModelEvent(string $event): bool
    {
        return $this->fireEvent($event);
    }

    /**
     * Get the SAP B1 client.
     *
     * @return mixed
     */
    protected function getClient()
    {
        return SapB1::connection(static::$connection);
    }

    /**
     * Convert the model to its JSON representation.
     *
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /**
     * Get the model as a string (JSON).
     */
    public function __toString(): string
    {
        return $this->toJson();
    }

    /**
     * Dynamically retrieve attributes.
     */
    public function __get(string $name): mixed
    {
        return $this->getAttribute($name);
    }

    /**
     * Dynamically set attributes.
     */
    public function __set(string $name, mixed $value): void
    {
        $this->setAttribute($name, $value);
    }

    /**
     * Determine if an attribute exists.
     */
    public function __isset(string $name): bool
    {
        return $this->hasAttribute($name) || $this->relationLoaded($name);
    }

    /**
     * Unset an attribute.
     */
    public function __unset(string $name): void
    {
        unset($this->attributes[$name], $this->relations[$name]);
    }

    /**
     * Clone the model.
     */
    public function __clone()
    {
        $this->exists = false;
        $this->relations = [];

        // Remove primary key
        unset($this->attributes[$this->getKeyName()]);
    }

    /**
     * Replicate the model to a new instance.
     *
     * @param  array<int, string>|null  $except
     */
    public function replicate(?array $except = null): static
    {
        $defaults = [
            $this->getKeyName(),
        ];

        $attributes = $this->attributes;

        foreach (array_merge($defaults, $except ?? []) as $key) {
            unset($attributes[$key]);
        }

        $model = new static($attributes);
        $model->exists = false;

        return $model;
    }
}
