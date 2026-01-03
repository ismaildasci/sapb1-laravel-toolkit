<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Builders\Service;

use SapB1\Toolkit\Builders\BaseBuilder;
use SapB1\Toolkit\Enums\ServiceCallPriority;

/**
 * @phpstan-consistent-constructor
 */
final class ServiceCallBuilder extends BaseBuilder
{
    public function subject(string $subject): static
    {
        return $this->set('Subject', $subject);
    }

    public function customerCode(string $code): static
    {
        return $this->set('CustomerCode', $code);
    }

    public function contactCode(int $code): static
    {
        return $this->set('ContactCode', $code);
    }

    public function itemCode(string $code): static
    {
        return $this->set('ItemCode', $code);
    }

    public function manufacturerSerialNum(string $serialNum): static
    {
        return $this->set('ManufacturerSerialNum', $serialNum);
    }

    public function internalSerialNum(string $serialNum): static
    {
        return $this->set('InternalSerialNum', $serialNum);
    }

    public function contractID(int $id): static
    {
        return $this->set('ContractID', $id);
    }

    public function status(int $status): static
    {
        return $this->set('Status', $status);
    }

    public function priority(ServiceCallPriority $priority): static
    {
        return $this->set('Priority', $priority->value);
    }

    public function callType(int $type): static
    {
        return $this->set('CallType', $type);
    }

    public function problemType(int $type): static
    {
        return $this->set('ProblemType', $type);
    }

    public function problemSubType(int $type): static
    {
        return $this->set('ProblemSubType', $type);
    }

    public function origin(int $origin): static
    {
        return $this->set('Origin', $origin);
    }

    public function assignee(int $assignee): static
    {
        return $this->set('Assignee', $assignee);
    }

    public function description(string $description): static
    {
        return $this->set('Description', $description);
    }

    public function technicianCode(int $code): static
    {
        return $this->set('TechnicianCode', $code);
    }

    public function resolution(string $resolution): static
    {
        return $this->set('Resolution', $resolution);
    }

    public function dueDate(string $date): static
    {
        return $this->set('DueDate', $date);
    }

    public function remarks(string $remarks): static
    {
        return $this->set('Remarks', $remarks);
    }

    public function build(): array
    {
        return array_filter($this->data, fn ($value) => $value !== null);
    }
}
