<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Builders\BusinessPartner;

use SapB1\Toolkit\Builders\BaseBuilder;
use SapB1\Toolkit\DTOs\BusinessPartner\SalesOpportunityLineDto;
use SapB1\Toolkit\Enums\OpportunityStatus;

/**
 * @phpstan-consistent-constructor
 */
final class SalesOpportunityBuilder extends BaseBuilder
{
    public function cardCode(string $code): static
    {
        return $this->set('CardCode', $code);
    }

    public function salesPerson(int $salesPerson): static
    {
        return $this->set('SalesPerson', $salesPerson);
    }

    public function contactPerson(int $contactPerson): static
    {
        return $this->set('ContactPerson', $contactPerson);
    }

    public function opportunityName(string $name): static
    {
        return $this->set('OpportunityName', $name);
    }

    public function source(int $source): static
    {
        return $this->set('Source', $source);
    }

    public function industry(int $industry): static
    {
        return $this->set('Industry', $industry);
    }

    public function interestField1(int $value): static
    {
        return $this->set('InterestField1', $value);
    }

    public function interestField2(int $value): static
    {
        return $this->set('InterestField2', $value);
    }

    public function interestField3(int $value): static
    {
        return $this->set('InterestField3', $value);
    }

    public function interestLevel(int $level): static
    {
        return $this->set('InterestLevel', $level);
    }

    public function startDate(string $date): static
    {
        return $this->set('StartDate', $date);
    }

    public function closingDate(string $date): static
    {
        return $this->set('ClosingDate', $date);
    }

    public function status(OpportunityStatus $status): static
    {
        return $this->set('Status', $status->value);
    }

    public function statusRemarks(string $remarks): static
    {
        return $this->set('StatusRemarks', $remarks);
    }

    public function remarks(string $remarks): static
    {
        return $this->set('Remarks', $remarks);
    }

    /**
     * @param  array<SalesOpportunityLineDto|array<string, mixed>>  $lines
     */
    public function salesOpportunitiesLines(array $lines): static
    {
        $mapped = array_map(
            fn ($line) => $line instanceof SalesOpportunityLineDto ? $line->toArray() : $line,
            $lines
        );

        return $this->set('SalesOpportunitiesLines', $mapped);
    }

    /**
     * @param  SalesOpportunityLineDto|array<string, mixed>  $line
     */
    public function addLine(SalesOpportunityLineDto|array $line): static
    {
        $lines = $this->get('SalesOpportunitiesLines', []);
        $lines[] = $line instanceof SalesOpportunityLineDto ? $line->toArray() : $line;

        return $this->set('SalesOpportunitiesLines', $lines);
    }

    public function build(): array
    {
        return array_filter($this->data, fn ($value) => $value !== null);
    }
}
