<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Builders\Purchase;

use SapB1\Toolkit\Builders\DocumentBuilder;

/**
 * Builder for Purchase Orders.
 *
 * @phpstan-consistent-constructor
 */
final class PurchaseOrderBuilder extends DocumentBuilder
{
    public function requester(string $requester): static
    {
        return $this->set('Requester', $requester);
    }

    public function requesterEmail(string $email): static
    {
        return $this->set('RequesterEmail', $email);
    }

    public function requesterName(string $name): static
    {
        return $this->set('RequesterName', $name);
    }

    public function requesterBranch(int $branch): static
    {
        return $this->set('RequesterBranch', $branch);
    }

    public function requesterDepartment(int $department): static
    {
        return $this->set('RequesterDepartment', $department);
    }
}
