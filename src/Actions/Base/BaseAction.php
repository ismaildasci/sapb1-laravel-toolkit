<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Actions\Base;

use SapB1\Facades\SapB1;
use SapB1\Toolkit\Contracts\ActionInterface;

/**
 * Base action class for SAP B1 Service Layer operations.
 *
 * Provides common functionality for all actions including connection
 * management and client access. All concrete action classes should
 * extend this class or DocumentAction.
 */
abstract class BaseAction implements ActionInterface
{
    /**
     * The SAP B1 Service Layer entity name.
     */
    protected string $entity = '';

    /**
     * The SAP B1 connection name to use.
     */
    protected string $connection = 'default';

    /**
     * Set the SAP B1 connection to use.
     *
     * @param  string  $name  The connection name as defined in config
     */
    public function connection(string $name): static
    {
        $this->connection = $name;

        return $this;
    }

    /**
     * Get the SAP B1 client instance.
     *
     * @return mixed The SAP B1 client for the configured connection
     */
    protected function client(): mixed
    {
        return SapB1::connection($this->connection);
    }

    /**
     * Execute the action with the given arguments.
     *
     * @param  mixed  ...$args  Action-specific arguments
     * @return mixed The action result
     */
    abstract public function execute(mixed ...$args): mixed;
}
