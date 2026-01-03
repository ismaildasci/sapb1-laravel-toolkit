<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Builders\BusinessPartner;

use SapB1\Toolkit\Builders\BaseBuilder;
use SapB1\Toolkit\Enums\BoYesNo;

/**
 * Builder for Activities.
 *
 * @phpstan-consistent-constructor
 */
final class ActivityBuilder extends BaseBuilder
{
    public function cardCode(string $cardCode): static
    {
        return $this->set('CardCode', $cardCode);
    }

    public function notes(string $notes): static
    {
        return $this->set('Notes', $notes);
    }

    public function activityDate(string $date): static
    {
        return $this->set('ActivityDate', $date);
    }

    public function activityTime(string $time): static
    {
        return $this->set('ActivityTime', $time);
    }

    public function startDate(string $date): static
    {
        return $this->set('StartDate', $date);
    }

    public function startTime(string $time): static
    {
        return $this->set('StartTime', $time);
    }

    public function endDate(string $date): static
    {
        return $this->set('EndDueDate', $date);
    }

    public function endTime(string $time): static
    {
        return $this->set('EndTime', $time);
    }

    public function duration(int $duration): static
    {
        return $this->set('Duration', $duration);
    }

    public function durationType(string $type): static
    {
        return $this->set('DurationType', $type);
    }

    public function salesEmployee(int $employee): static
    {
        return $this->set('SalesEmployee', $employee);
    }

    public function contactPersonCode(int $code): static
    {
        return $this->set('ContactPersonCode', $code);
    }

    public function handledBy(int $userId): static
    {
        return $this->set('HandledBy', $userId);
    }

    public function priority(string $priority): static
    {
        return $this->set('Priority', $priority);
    }

    public function details(string $details): static
    {
        return $this->set('Details', $details);
    }

    public function activityType(int $type): static
    {
        return $this->set('ActivityType', $type);
    }

    public function location(int $location): static
    {
        return $this->set('Location', $location);
    }

    public function closed(BoYesNo $value): static
    {
        return $this->set('Closed', $value->value);
    }

    public function city(string $city): static
    {
        return $this->set('City', $city);
    }

    public function country(string $country): static
    {
        return $this->set('Country', $country);
    }

    public function street(string $street): static
    {
        return $this->set('Street', $street);
    }

    public function state(string $state): static
    {
        return $this->set('State', $state);
    }

    /**
     * @return array<string, mixed>
     */
    public function build(): array
    {
        return array_filter($this->data, fn ($value) => $value !== null);
    }
}
