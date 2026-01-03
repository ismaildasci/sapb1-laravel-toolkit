<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Models\Relations;

use SapB1\Toolkit\Models\ModelCollection;
use SapB1\Toolkit\Models\SapB1Model;

/**
 * One-to-many relationship.
 */
class HasMany extends Relation
{
    /**
     * Get the results of the relationship.
     *
     * @return ModelCollection<SapB1Model>
     */
    public function getResults(): ModelCollection
    {
        $parentKey = $this->getParentKey();

        if ($parentKey === null) {
            return new ModelCollection;
        }

        return $this->related::where($this->foreignKey, $parentKey)->get();
    }

    /**
     * Eager load the relationship on a collection of models.
     *
     * @param  ModelCollection<SapB1Model>  $models
     */
    public function eagerLoadOn(ModelCollection $models): void
    {
        $keys = $this->getParentKeys($models);

        if (empty($keys)) {
            return;
        }

        // Fetch all related models
        $related = $this->related::whereIn($this->foreignKey, $keys)->get();

        // Group by foreign key
        $grouped = [];
        foreach ($related->all() as $model) {
            $key = $model->{$this->foreignKey};
            $grouped[$key][] = $model;
        }

        // Assign to each parent
        foreach ($models->all() as $model) {
            $parentKey = $model->{$this->localKey};
            $model->setRelation(
                $this->getRelationName(),
                new ModelCollection($grouped[$parentKey] ?? [])
            );
        }
    }

    /**
     * Get the relation name (for setting on parent).
     */
    protected function getRelationName(): string
    {
        // Get calling method name from parent
        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 4);

        foreach ($trace as $frame) {
            if (str_starts_with($frame['function'], 'scope')) {
                continue;
            }

            if (isset($frame['object']) && $frame['object'] instanceof SapB1Model) {
                return $frame['function'];
            }
        }

        return 'related';
    }

    /**
     * Add a constraint to the query.
     *
     * @return ModelCollection<SapB1Model>
     */
    public function where(string $field, mixed $operator = null, mixed $value = null): ModelCollection
    {
        $parentKey = $this->getParentKey();

        if ($parentKey === null) {
            return new ModelCollection;
        }

        return $this->related::where($this->foreignKey, $parentKey)
            ->where($field, $operator, $value)
            ->get();
    }

    /**
     * Get first related model.
     */
    public function first(): ?SapB1Model
    {
        $parentKey = $this->getParentKey();

        if ($parentKey === null) {
            return null;
        }

        return $this->related::where($this->foreignKey, $parentKey)->first();
    }

    /**
     * Get count of related models.
     */
    public function count(): int
    {
        $parentKey = $this->getParentKey();

        if ($parentKey === null) {
            return 0;
        }

        return $this->related::where($this->foreignKey, $parentKey)->count();
    }

    /**
     * Check if any related models exist.
     */
    public function exists(): bool
    {
        return $this->count() > 0;
    }
}
