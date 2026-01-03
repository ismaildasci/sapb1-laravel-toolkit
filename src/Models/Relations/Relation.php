<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Models\Relations;

use SapB1\Toolkit\Models\ModelCollection;
use SapB1\Toolkit\Models\SapB1Model;

/**
 * Base class for model relationships.
 */
abstract class Relation
{
    /**
     * The parent model instance.
     */
    protected SapB1Model $parent;

    /**
     * The related model class.
     *
     * @var class-string<SapB1Model>
     */
    protected string $related;

    /**
     * The foreign key on the related model.
     */
    protected string $foreignKey;

    /**
     * The local key on the parent model.
     */
    protected string $localKey;

    /**
     * @param  class-string<SapB1Model>  $related
     */
    public function __construct(
        SapB1Model $parent,
        string $related,
        string $foreignKey,
        string $localKey
    ) {
        $this->parent = $parent;
        $this->related = $related;
        $this->foreignKey = $foreignKey;
        $this->localKey = $localKey;
    }

    /**
     * Get the results of the relationship.
     */
    abstract public function getResults(): mixed;

    /**
     * Eager load the relationship on a collection of models.
     *
     * @param  ModelCollection<SapB1Model>  $models
     */
    abstract public function eagerLoadOn(ModelCollection $models): void;

    /**
     * Get the related model instance.
     */
    protected function getRelatedModel(): SapB1Model
    {
        return new $this->related;
    }

    /**
     * Get the parent's local key value.
     */
    protected function getParentKey(): mixed
    {
        return $this->parent->{$this->localKey};
    }

    /**
     * Get all parent key values from a collection.
     *
     * @param  ModelCollection<SapB1Model>  $models
     * @return array<int, mixed>
     */
    protected function getParentKeys(ModelCollection $models): array
    {
        return array_unique($models->pluck($this->localKey));
    }
}
