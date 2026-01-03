<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Models\Relations;

use SapB1\Toolkit\Models\ModelCollection;
use SapB1\Toolkit\Models\SapB1Model;

/**
 * Inverse one-to-many (belongs to) relationship.
 */
class BelongsTo extends Relation
{
    /**
     * The owner key on the related model.
     */
    protected string $ownerKey;

    /**
     * @param  class-string<SapB1Model>  $related
     */
    public function __construct(
        SapB1Model $parent,
        string $related,
        string $foreignKey,
        string $ownerKey
    ) {
        parent::__construct($parent, $related, $foreignKey, $foreignKey);
        $this->ownerKey = $ownerKey;
    }

    /**
     * Get the results of the relationship.
     */
    public function getResults(): ?SapB1Model
    {
        $foreignValue = $this->parent->{$this->foreignKey};

        if ($foreignValue === null) {
            return null;
        }

        return $this->related::where($this->ownerKey, $foreignValue)->first();
    }

    /**
     * Eager load the relationship on a collection of models.
     *
     * @param  ModelCollection<SapB1Model>  $models
     */
    public function eagerLoadOn(ModelCollection $models): void
    {
        // Get all foreign key values
        $keys = array_unique(array_filter($models->pluck($this->foreignKey)));

        if (empty($keys)) {
            return;
        }

        // Fetch all related models
        $related = $this->related::whereIn($this->ownerKey, $keys)->get();

        // Key by owner key
        $keyed = $related->keyBy($this->ownerKey);

        // Assign to each parent
        foreach ($models->all() as $model) {
            $foreignValue = $model->{$this->foreignKey};
            $model->setRelation(
                $this->getRelationName(),
                $keyed[$foreignValue] ?? null
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
     * Associate a model with the parent.
     */
    public function associate(SapB1Model $model): SapB1Model
    {
        $this->parent->{$this->foreignKey} = $model->{$this->ownerKey};

        return $this->parent;
    }

    /**
     * Dissociate the parent from the related model.
     */
    public function dissociate(): SapB1Model
    {
        $this->parent->{$this->foreignKey} = null;

        return $this->parent;
    }

    /**
     * Check if related model exists.
     */
    public function exists(): bool
    {
        $foreignValue = $this->parent->{$this->foreignKey};

        if ($foreignValue === null) {
            return false;
        }

        return $this->related::where($this->ownerKey, $foreignValue)->exists();
    }
}
