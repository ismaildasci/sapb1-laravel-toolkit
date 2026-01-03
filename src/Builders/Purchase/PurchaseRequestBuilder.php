<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Builders\Purchase;

use SapB1\Toolkit\Builders\DocumentBuilder;

/**
 * Builder for Purchase Requests.
 *
 * @phpstan-consistent-constructor
 */
final class PurchaseRequestBuilder extends DocumentBuilder
{
    public function requester(int $requester): static
    {
        return $this->set('Requester', $requester);
    }

    public function requesterName(string $name): static
    {
        return $this->set('RequesterName', $name);
    }

    public function requesterEmail(string $email): static
    {
        return $this->set('RequesterEmail', $email);
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
