<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Contracts;

/**
 * Contract for SAP B1 action classes.
 *
 * Actions are responsible for executing single operations against
 * the SAP B1 Service Layer API such as CRUD operations on entities.
 */
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
