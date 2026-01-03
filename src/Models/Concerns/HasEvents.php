<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Models\Concerns;

/**
 * Handles model lifecycle events.
 */
trait HasEvents
{
    /**
     * Registered event listeners.
     *
     * @var array<string, array<string, array<callable>>>
     */
    protected static array $eventListeners = [];

    /**
     * Whether events should be dispatched.
     */
    protected static bool $dispatchesEvents = true;

    /**
     * Available event types.
     *
     * @var array<int, string>
     */
    protected static array $eventTypes = [
        'creating',
        'created',
        'updating',
        'updated',
        'saving',
        'saved',
        'deleting',
        'deleted',
        'retrieved',
    ];

    /**
     * Boot the trait (called once per model class).
     */
    protected static function bootHasEvents(): void
    {
        static::booted();
    }

    /**
     * Override this method to register event listeners.
     */
    protected static function booted(): void
    {
        // Override in model to register listeners
    }

    /**
     * Register a creating event listener.
     */
    public static function creating(callable $callback): void
    {
        static::registerEvent('creating', $callback);
    }

    /**
     * Register a created event listener.
     */
    public static function created(callable $callback): void
    {
        static::registerEvent('created', $callback);
    }

    /**
     * Register an updating event listener.
     */
    public static function updating(callable $callback): void
    {
        static::registerEvent('updating', $callback);
    }

    /**
     * Register an updated event listener.
     */
    public static function updated(callable $callback): void
    {
        static::registerEvent('updated', $callback);
    }

    /**
     * Register a saving event listener.
     */
    public static function saving(callable $callback): void
    {
        static::registerEvent('saving', $callback);
    }

    /**
     * Register a saved event listener.
     */
    public static function saved(callable $callback): void
    {
        static::registerEvent('saved', $callback);
    }

    /**
     * Register a deleting event listener.
     */
    public static function deleting(callable $callback): void
    {
        static::registerEvent('deleting', $callback);
    }

    /**
     * Register a deleted event listener.
     */
    public static function deleted(callable $callback): void
    {
        static::registerEvent('deleted', $callback);
    }

    /**
     * Register a retrieved event listener.
     */
    public static function retrieved(callable $callback): void
    {
        static::registerEvent('retrieved', $callback);
    }

    /**
     * Register an event listener.
     */
    protected static function registerEvent(string $event, callable $callback): void
    {
        $class = static::class;
        static::$eventListeners[$class][$event][] = $callback;
    }

    /**
     * Fire a model event.
     *
     * @return bool False if event should halt operation
     */
    protected function fireEvent(string $event): bool
    {
        if (! static::$dispatchesEvents) {
            return true;
        }

        $class = static::class;
        $listeners = static::$eventListeners[$class][$event] ?? [];

        foreach ($listeners as $listener) {
            $result = $listener($this);

            // If listener returns false, halt the operation
            if ($result === false) {
                return false;
            }
        }

        // Dispatch Laravel event if available
        $this->dispatchLaravelEvent($event);

        return true;
    }

    /**
     * Dispatch Laravel event.
     */
    protected function dispatchLaravelEvent(string $event): void
    {
        if (! function_exists('event')) {
            return;
        }

        $eventClass = $this->getEventClass($event);

        if ($eventClass !== null && class_exists($eventClass)) {
            event(new $eventClass($this));
        }
    }

    /**
     * Get Laravel event class for the given event type.
     *
     * Note: Model events use a different mechanism than Action events.
     * Override this method in your model to dispatch custom events.
     */
    protected function getEventClass(string $event): ?string
    {
        // Default: no Laravel events dispatched from models
        // Action classes use their own event dispatching with DocumentCreated/Updated
        // Models use callback-based events via creating(), updated(), etc.
        return null;
    }

    /**
     * Enable event dispatching.
     */
    public static function enableEvents(): void
    {
        static::$dispatchesEvents = true;
    }

    /**
     * Disable event dispatching.
     */
    public static function disableEvents(): void
    {
        static::$dispatchesEvents = false;
    }

    /**
     * Execute callback without firing events.
     *
     * @template T
     *
     * @param  callable(): T  $callback
     * @return T
     */
    public static function withoutEvents(callable $callback): mixed
    {
        $previousState = static::$dispatchesEvents;
        static::$dispatchesEvents = false;

        try {
            return $callback();
        } finally {
            static::$dispatchesEvents = $previousState;
        }
    }

    /**
     * Clear all event listeners for this model.
     */
    public static function flushEventListeners(): void
    {
        $class = static::class;
        unset(static::$eventListeners[$class]);
    }
}
