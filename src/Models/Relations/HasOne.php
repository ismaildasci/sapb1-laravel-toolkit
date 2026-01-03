<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Models\Relations;

use SapB1\Toolkit\Models\ModelCollection;
use SapB1\Toolkit\Models\SapB1Model;

/**
 * One-to-one relationship.
 */
class HasOne extends Relation
{
    /**
     * Get the results of the relationship.
     */
    public function getResults(): ?SapB1Model
    {
        $parentKey = $this->getParentKey();

        if ($parentKey === null) {
            return null;
        }

        return $this->related::where($this->foreignKey, $parentKey)->first();
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

        // Key by foreign key (first match wins for HasOne)
        $keyed = [];
        foreach ($related->all() as $model) {
            $key = $model->{$this->foreignKey};
            if (! isset($keyed[$key])) {
                $keyed[$key] = $model;
            }
        }

        // Assign to each parent
        foreach ($models->all() as $model) {
            $parentKey = $model->{$this->localKey};
            $model->setRelation(
                $this->getRelationName(),
                $keyed[$parentKey] ?? null
            );
        }
    }

    /**
     * Get the relation name (for setting on parent).
     */
    protected function getRelationName(): string
    {
        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 4);

        foreach ($trace as $frame) {
            if (isset($frame['object']) && $frame['object'] instanceof SapB1Model) {
                return $frame['function'];
            }
        }

        return 'related';
    }

    /**
     * Check if related model exists.
     */
    public function exists(): bool
    {
        $parentKey = $this->getParentKey();

        if ($parentKey === null) {
            return false;
        }

        return $this->related::where($this->foreignKey, $parentKey)->exists();
    }
}
