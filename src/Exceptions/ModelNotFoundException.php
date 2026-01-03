<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Exceptions;

use RuntimeException;

/**
 * Exception thrown when a model is not found.
 */
class ModelNotFoundException extends RuntimeException
{
    /**
     * The model class that was not found.
     */
    protected string $model = '';

    /**
     * The IDs that were searched for.
     *
     * @var array<int, int|string>
     */
    protected array $ids = [];

    /**
     * Set the affected model and IDs.
     *
     * @param  class-string  $model
     * @param  array<int, int|string>|int|string  $ids
     */
    public function setModel(string $model, array|int|string $ids = []): static
    {
        $this->model = $model;
        $this->ids = is_array($ids) ? $ids : [$ids];

        $this->message = sprintf(
            'No query results for model [%s]%s',
            $model,
            $this->ids ? ' with ID(s) '.implode(', ', $this->ids) : ''
        );

        return $this;
    }

    /**
     * Get the affected model class.
     */
    public function getModel(): string
    {
        return $this->model;
    }

    /**
     * Get the affected IDs.
     *
     * @return array<int, int|string>
     */
    public function getIds(): array
    {
        return $this->ids;
    }
}
