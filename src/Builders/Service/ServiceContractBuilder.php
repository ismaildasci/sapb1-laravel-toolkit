<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Builders\Service;

use SapB1\Toolkit\Builders\BaseBuilder;
use SapB1\Toolkit\DTOs\Service\ServiceContractLineDto;

/**
 * @phpstan-consistent-constructor
 */
final class ServiceContractBuilder extends BaseBuilder
{
    public function customerCode(string $code): static
    {
        return $this->set('CustomerCode', $code);
    }

    public function contactCode(int $code): static
    {
        return $this->set('ContactCode', $code);
    }

    public function owner(string $owner): static
    {
        return $this->set('Owner', $owner);
    }

    public function status(string $status): static
    {
        return $this->set('Status', $status);
    }

    public function contractType(string $type): static
    {
        return $this->set('ContractType', $type);
    }

    public function renewalType(string $type): static
    {
        return $this->set('RenewalType', $type);
    }

    public function startDate(string $date): static
    {
        return $this->set('StartDate', $date);
    }

    public function endDate(string $date): static
    {
        return $this->set('EndDate', $date);
    }

    public function terminationDate(string $date): static
    {
        return $this->set('TerminationDate', $date);
    }

    public function description(string $description): static
    {
        return $this->set('Description', $description);
    }

    public function remarks(string $remarks): static
    {
        return $this->set('Remarks', $remarks);
    }

    public function templateId(int $id): static
    {
        return $this->set('TemplateId', $id);
    }

    public function responseTime(string $time): static
    {
        return $this->set('ResponseTime', $time);
    }

    public function responseUnit(string $unit): static
    {
        return $this->set('ResponseUnit', $unit);
    }

    public function resolutionTime(string $time): static
    {
        return $this->set('ResolutionTime', $time);
    }

    public function resolutionUnit(string $unit): static
    {
        return $this->set('ResolutionUnit', $unit);
    }

    /**
     * @param  array<ServiceContractLineDto|array<string, mixed>>  $lines
     */
    public function serviceContractLines(array $lines): static
    {
        $mapped = array_map(
            fn ($line) => $line instanceof ServiceContractLineDto ? $line->toArray() : $line,
            $lines
        );

        return $this->set('ServiceContract_Lines', $mapped);
    }

    /**
     * @param  ServiceContractLineDto|array<string, mixed>  $line
     */
    public function addLine(ServiceContractLineDto|array $line): static
    {
        $lines = $this->get('ServiceContract_Lines', []);
        $lines[] = $line instanceof ServiceContractLineDto ? $line->toArray() : $line;

        return $this->set('ServiceContract_Lines', $lines);
    }

    public function build(): array
    {
        return array_filter($this->data, fn ($value) => $value !== null);
    }
}
