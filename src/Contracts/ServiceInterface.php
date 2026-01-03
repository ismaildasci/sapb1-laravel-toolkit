<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Contracts;

/**
 * Contract for SAP B1 service classes.
 *
 * Services orchestrate multiple actions and provide higher-level
 * business operations like document flows, payments, and reporting.
 */
interface ServiceInterface
{
    /**
     * Set the SAP B1 connection to use.
     */
    public function connection(string $name): static;
}
