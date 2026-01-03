<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Models\Concerns;

/**
 * Manages model attributes, hydration, and attribute access.
 */
trait HasAttributes
{
    /**
     * The model's attributes.
     *
     * @var array<string, mixed>
     */
    protected array $attributes = [];

    /**
     * Original attribute values (before modifications).
     *
     * @var array<string, mixed>
     */
    protected array $original = [];

    /**
     * Fields that can be mass-assigned.
     *
     * @var array<int, string>
     */
    protected array $fillable = [];

    /**
     * Fields that cannot be mass-assigned.
     *
     * @var array<int, string>
     */
    protected array $guarded = ['*'];

    /**
     * Fields hidden from serialization.
     *
     * @var array<int, string>
     */
    protected array $hidden = [];

    /**
     * Fill model with array of attributes.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function fill(array $attributes): static
    {
        foreach ($attributes as $key => $value) {
            if ($this->isFillable($key)) {
                $this->setAttribute($key, $value);
            }
        }

        return $this;
    }

    /**
     * Force fill model with array of attributes (bypasses fillable).
     *
     * @param  array<string, mixed>  $attributes
     */
    public function forceFill(array $attributes): static
    {
        foreach ($attributes as $key => $value) {
            $this->setAttribute($key, $value);
        }

        return $this;
    }

    /**
     * Check if attribute can be mass-assigned.
     */
    protected function isFillable(string $key): bool
    {
        if (in_array($key, $this->fillable, true)) {
            return true;
        }

        if ($this->isGuarded($key)) {
            return false;
        }

        return empty($this->fillable);
    }

    /**
     * Check if attribute is guarded.
     */
    protected function isGuarded(string $key): bool
    {
        if (empty($this->guarded)) {
            return false;
        }

        return $this->guarded === ['*'] || in_array($key, $this->guarded, true);
    }

    /**
     * Set attribute value.
     */
    public function setAttribute(string $key, mixed $value): static
    {
        // Check for mutator
        $mutator = 'set'.$this->studly($key).'Attribute';
        if (method_exists($this, $mutator)) {
            $value = $this->{$mutator}($value);
        }

        $this->attributes[$key] = $value;

        return $this;
    }

    /**
     * Get attribute value.
     */
    public function getAttribute(string $key): mixed
    {
        if (! array_key_exists($key, $this->attributes)) {
            // Check if it's a relation
            if (method_exists($this, $key)) {
                return $this->getRelationValue($key);
            }

            return null;
        }

        $value = $this->attributes[$key];

        // Check for accessor
        $accessor = 'get'.$this->studly($key).'Attribute';
        if (method_exists($this, $accessor)) {
            return $this->{$accessor}($value);
        }

        // Apply casting (castAttribute is provided by HasCasting trait)
        /** @phpstan-ignore-next-line */
        return $this->castAttribute($key, $value);
    }

    /**
     * Get raw attribute without casting.
     */
    public function getRawAttribute(string $key): mixed
    {
        return $this->attributes[$key] ?? null;
    }

    /**
     * Check if attribute exists.
     */
    public function hasAttribute(string $key): bool
    {
        return array_key_exists($key, $this->attributes);
    }

    /**
     * Get all attributes.
     *
     * @return array<string, mixed>
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * Set all attributes at once.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function setRawAttributes(array $attributes, bool $sync = false): static
    {
        $this->attributes = $attributes;

        if ($sync) {
            $this->syncOriginal();
        }

        return $this;
    }

    /**
     * Sync original attributes.
     */
    public function syncOriginal(): static
    {
        $this->original = $this->attributes;

        return $this;
    }

    /**
     * Get original attribute value.
     */
    public function getOriginal(?string $key = null): mixed
    {
        if ($key === null) {
            return $this->original;
        }

        return $this->original[$key] ?? null;
    }

    /**
     * Convert model to array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $attributes = [];

        foreach ($this->attributes as $key => $value) {
            if (in_array($key, $this->hidden, true)) {
                continue;
            }

            $attributes[$key] = $this->getAttribute($key);
        }

        // Add loaded relations (relations property is provided by HasRelationships trait)
        /** @phpstan-ignore-next-line */
        if (isset($this->relations)) {
            /** @phpstan-ignore-next-line */
            foreach ($this->relations as $key => $value) {
                if (in_array($key, $this->hidden, true)) {
                    continue;
                }

                if (is_array($value)) {
                    $attributes[$key] = array_map(
                        fn ($item) => $item instanceof self ? $item->toArray() : $item,
                        $value
                    );
                } elseif ($value instanceof self) {
                    $attributes[$key] = $value->toArray();
                } else {
                    $attributes[$key] = $value;
                }
            }
        }

        return $attributes;
    }

    /**
     * Convert model to JSON.
     */
    public function toJson(int $options = 0): string
    {
        return json_encode($this->toArray(), $options) ?: '{}';
    }

    /**
     * Convert string to studly case.
     */
    protected function studly(string $value): string
    {
        $words = explode(' ', str_replace(['-', '_'], ' ', $value));

        $studlyWords = array_map(fn ($word) => ucfirst($word), $words);

        return implode('', $studlyWords);
    }

    /**
     * Magic getter.
     */
    public function __get(string $name): mixed
    {
        return $this->getAttribute($name);
    }

    /**
     * Magic setter.
     */
    public function __set(string $name, mixed $value): void
    {
        $this->setAttribute($name, $value);
    }

    /**
     * Magic isset.
     */
    public function __isset(string $name): bool
    {
        return $this->hasAttribute($name) || (method_exists($this, $name) && $this->relationLoaded($name));
    }
}
