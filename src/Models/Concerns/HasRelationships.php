<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Models\Concerns;

use SapB1\Toolkit\Models\ModelCollection;
use SapB1\Toolkit\Models\Relations\BelongsTo;
use SapB1\Toolkit\Models\Relations\HasMany;
use SapB1\Toolkit\Models\Relations\HasOne;
use SapB1\Toolkit\Models\Relations\Relation;
use SapB1\Toolkit\Models\SapB1Model;

/**
 * Defines and manages model relationships.
 */
trait HasRelationships
{
    /**
     * Loaded relationships.
     *
     * @var array<string, mixed>
     */
    protected array $relations = [];

    /**
     * Define a one-to-many relationship.
     *
     * @param  class-string<SapB1Model>  $related
     */
    protected function hasMany(string $related, ?string $foreignKey = null, ?string $localKey = null): HasMany
    {
        $foreignKey ??= $this->guessForeignKey();
        $localKey ??= $this->getKeyName();

        return new HasMany($this, $related, $foreignKey, $localKey);
    }

    /**
     * Define a one-to-one relationship.
     *
     * @param  class-string<SapB1Model>  $related
     */
    protected function hasOne(string $related, ?string $foreignKey = null, ?string $localKey = null): HasOne
    {
        $foreignKey ??= $this->guessForeignKey();
        $localKey ??= $this->getKeyName();

        return new HasOne($this, $related, $foreignKey, $localKey);
    }

    /**
     * Define an inverse one-to-many (belongs to) relationship.
     *
     * @param  class-string<SapB1Model>  $related
     */
    protected function belongsTo(string $related, ?string $foreignKey = null, ?string $ownerKey = null): BelongsTo
    {
        $foreignKey ??= $this->guessBelongsToForeignKey($related);
        $ownerKey ??= (new $related)->getKeyName();

        return new BelongsTo($this, $related, $foreignKey, $ownerKey);
    }

    /**
     * Guess foreign key for hasMany/hasOne.
     */
    protected function guessForeignKey(): string
    {
        $baseName = class_basename(static::class);

        return lcfirst($baseName).'Id';
    }

    /**
     * Guess foreign key for belongsTo.
     *
     * @param  class-string<SapB1Model>  $related
     */
    protected function guessBelongsToForeignKey(string $related): string
    {
        $baseName = class_basename($related);

        return lcfirst($baseName).'Id';
    }

    /**
     * Get a relationship value.
     */
    public function getRelationValue(string $key): mixed
    {
        // If relation is already loaded, return it
        if ($this->relationLoaded($key)) {
            return $this->relations[$key];
        }

        // If the method exists and returns a Relation, load it
        if (method_exists($this, $key)) {
            $relation = $this->{$key}();

            if ($relation instanceof Relation) {
                $this->setRelation($key, $relation->getResults());

                return $this->relations[$key];
            }
        }

        return null;
    }

    /**
     * Set a loaded relationship.
     */
    public function setRelation(string $key, mixed $value): static
    {
        $this->relations[$key] = $value;

        return $this;
    }

    /**
     * Check if a relationship is loaded.
     */
    public function relationLoaded(string $key): bool
    {
        return array_key_exists($key, $this->relations);
    }

    /**
     * Get all loaded relations.
     *
     * @return array<string, mixed>
     */
    public function getRelations(): array
    {
        return $this->relations;
    }

    /**
     * Set all relations at once.
     *
     * @param  array<string, mixed>  $relations
     */
    public function setRelations(array $relations): static
    {
        $this->relations = $relations;

        return $this;
    }

    /**
     * Unset a loaded relationship.
     */
    public function unsetRelation(string $key): static
    {
        unset($this->relations[$key]);

        return $this;
    }

    /**
     * Load relationships.
     *
     * @param  string|array<int, string>  $relations
     */
    public function load(string|array $relations): static
    {
        $relations = is_array($relations) ? $relations : func_get_args();

        foreach ($relations as $relationName) {
            if (method_exists($this, $relationName)) {
                $relation = $this->{$relationName}();

                if ($relation instanceof Relation) {
                    $this->setRelation($relationName, $relation->getResults());
                }
            }
        }

        return $this;
    }

    /**
     * Eager load relationships for a collection.
     *
     * @param  ModelCollection<SapB1Model>  $models
     * @param  array<int, string>  $relations
     */
    public static function eagerLoadRelations(ModelCollection $models, array $relations): void
    {
        if ($models->isEmpty()) {
            return;
        }

        foreach ($relations as $relationName) {
            $first = $models->first();

            if ($first === null || ! method_exists($first, $relationName)) {
                continue;
            }

            $relation = $first->{$relationName}();

            if ($relation instanceof Relation) {
                $relation->eagerLoadOn($models);
            }
        }
    }

    /**
     * Get embedded relationship data (for document lines, etc).
     *
     * @param  class-string<SapB1Model>  $relatedClass
     * @return array<int, SapB1Model>
     */
    protected function getEmbeddedRelation(string $key, string $relatedClass): array
    {
        $data = $this->attributes[$key] ?? [];

        if (! is_array($data)) {
            return [];
        }

        return array_map(
            function (array $item) use ($relatedClass): SapB1Model {
                /** @var SapB1Model $model */
                $model = new $relatedClass;

                return $model->setRawAttributes($item, true);
            },
            $data
        );
    }

    /**
     * Set embedded relationship data.
     *
     * @param  array<int, SapB1Model|array<string, mixed>>  $items
     */
    protected function setEmbeddedRelation(string $key, array $items): static
    {
        $data = array_map(
            fn ($item) => $item instanceof SapB1Model ? $item->toArray() : $item,
            $items
        );

        $this->attributes[$key] = $data;

        return $this;
    }
}
