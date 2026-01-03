<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Contracts;

interface ServiceInterface
{
    /**
     * Set the SAP B1 connection to use.
     */
    public function connection(string $name): static;
}
