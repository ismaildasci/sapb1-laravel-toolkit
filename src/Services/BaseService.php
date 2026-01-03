<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Services;

use SapB1\Facades\SapB1;
use SapB1\Toolkit\Contracts\ServiceInterface;

/**
 * Base service class for SAP B1 business operations.
 *
 * Provides common functionality for all services including connection
 * management and client access. Services orchestrate multiple actions
 * and provide higher-level business operations.
 */
abstract class BaseService implements ServiceInterface
{
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
}
