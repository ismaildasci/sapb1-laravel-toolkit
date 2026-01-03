<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Concerns;

trait HasDocumentLines
{
    /**
     * @var array<int, array<string, mixed>>
     */
    protected array $lines = [];

    /**
     * @param  array<string, mixed>  $line
     */
    public function addLine(array $line): static
    {
        $this->lines[] = $line;

        return $this;
    }

    /**
     * @param  array<int, array<string, mixed>>  $lines
     */
    public function addLines(array $lines): static
    {
        foreach ($lines as $line) {
            $this->addLine($line);
        }

        return $this;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getLines(): array
    {
        return $this->lines;
    }

    public function clearLines(): static
    {
        $this->lines = [];

        return $this;
    }

    public function hasLines(): bool
    {
        return count($this->lines) > 0;
    }

    public function lineCount(): int
    {
        return count($this->lines);
    }
}
