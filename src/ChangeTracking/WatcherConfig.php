<?php

declare(strict_types=1);

namespace SapB1\Toolkit\ChangeTracking;

/**
 * Configuration for entity watching.
 */
final class WatcherConfig
{
    /**
     * The entity to watch.
     */
    public string $entity;

    /**
     * The primary key field name.
     */
    public string $primaryKey = 'DocEntry';

    /**
     * The update date field name (for detecting updates).
     */
    public string $updateDateField = 'UpdateDate';

    /**
     * The create date field name (for detecting creates).
     */
    public string $createDateField = 'CreateDate';

    /**
     * Whether to detect created records.
     */
    public bool $detectCreated = true;

    /**
     * Whether to detect updated records.
     */
    public bool $detectUpdated = true;

    /**
     * Whether to detect deleted records (requires storing all keys).
     */
    public bool $detectDeleted = false;

    /**
     * OData filter to apply when watching.
     */
    public ?string $filter = null;

    /**
     * Fields to select (empty = all fields).
     *
     * @var array<int, string>
     */
    public array $select = [];

    /**
     * Maximum records to fetch per poll.
     */
    public int $batchSize = 100;

    /**
     * Custom callbacks for change events.
     *
     * @var array<string, callable(Change): void>
     */
    private array $callbacks = [];

    /**
     * Create a new watcher config.
     */
    public function __construct(string $entity)
    {
        $this->entity = $entity;
    }

    /**
     * Create a new watcher config for an entity.
     */
    public static function for(string $entity): self
    {
        return new self($entity);
    }

    /**
     * Set the primary key field.
     */
    public function primaryKey(string $field): self
    {
        $this->primaryKey = $field;

        return $this;
    }

    /**
     * Set the update date field.
     */
    public function updateDateField(string $field): self
    {
        $this->updateDateField = $field;

        return $this;
    }

    /**
     * Set the create date field.
     */
    public function createDateField(string $field): self
    {
        $this->createDateField = $field;

        return $this;
    }

    /**
     * Enable/disable created detection.
     */
    public function detectCreated(bool $enabled = true): self
    {
        $this->detectCreated = $enabled;

        return $this;
    }

    /**
     * Enable/disable updated detection.
     */
    public function detectUpdated(bool $enabled = true): self
    {
        $this->detectUpdated = $enabled;

        return $this;
    }

    /**
     * Enable/disable deleted detection.
     */
    public function detectDeleted(bool $enabled = true): self
    {
        $this->detectDeleted = $enabled;

        return $this;
    }

    /**
     * Only detect created records.
     */
    public function onlyCreated(): self
    {
        $this->detectCreated = true;
        $this->detectUpdated = false;
        $this->detectDeleted = false;

        return $this;
    }

    /**
     * Only detect updated records.
     */
    public function onlyUpdated(): self
    {
        $this->detectCreated = false;
        $this->detectUpdated = true;
        $this->detectDeleted = false;

        return $this;
    }

    /**
     * Apply an OData filter.
     */
    public function filter(string $filter): self
    {
        $this->filter = $filter;

        return $this;
    }

    /**
     * Select specific fields.
     *
     * @param  array<int, string>  $fields
     */
    public function select(array $fields): self
    {
        $this->select = $fields;

        return $this;
    }

    /**
     * Set the batch size.
     */
    public function batchSize(int $size): self
    {
        $this->batchSize = $size;

        return $this;
    }

    /**
     * Register a callback for created events.
     *
     * @param  callable(Change): void  $callback
     */
    public function onCreated(callable $callback): self
    {
        $this->callbacks[ChangeType::Created->value] = $callback;

        return $this;
    }

    /**
     * Register a callback for updated events.
     *
     * @param  callable(Change): void  $callback
     */
    public function onUpdated(callable $callback): self
    {
        $this->callbacks[ChangeType::Updated->value] = $callback;

        return $this;
    }

    /**
     * Register a callback for deleted events.
     *
     * @param  callable(Change): void  $callback
     */
    public function onDeleted(callable $callback): self
    {
        $this->callbacks[ChangeType::Deleted->value] = $callback;

        return $this;
    }

    /**
     * Register a callback for any change.
     *
     * @param  callable(Change): void  $callback
     */
    public function onChange(callable $callback): self
    {
        $this->callbacks['any'] = $callback;

        return $this;
    }

    /**
     * Get the callback for a change type.
     *
     * @return callable(Change): void|null
     */
    public function getCallback(ChangeType $type): ?callable
    {
        return $this->callbacks[$type->value] ?? null;
    }

    /**
     * Get the "any change" callback.
     *
     * @return callable(Change): void|null
     */
    public function getAnyCallback(): ?callable
    {
        return $this->callbacks['any'] ?? null;
    }

    /**
     * Check if any callbacks are registered.
     */
    public function hasCallbacks(): bool
    {
        return ! empty($this->callbacks);
    }
}
