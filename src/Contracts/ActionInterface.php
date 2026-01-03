<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Contracts;

interface ActionInterface
{
    /**
     * Execute the action.
     */
    public function execute(mixed ...$args): mixed;

    /**
     * Set the SAP B1 connection to use.
     */
    public function connection(string $name): static;
}
