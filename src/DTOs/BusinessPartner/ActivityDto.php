<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs\BusinessPartner;

use SapB1\Toolkit\DTOs\Base\BaseDto;
use SapB1\Toolkit\Enums\BoYesNo;

/**
 * @phpstan-consistent-constructor
 */
final class ActivityDto extends BaseDto
{
    public function __construct(
        public readonly ?int $activityCode = null,
        public readonly ?string $cardCode = null,
        public readonly ?string $notes = null,
        public readonly ?string $activityDate = null,
        public readonly ?string $activityTime = null,
        public readonly ?string $startDate = null,
        public readonly ?string $startTime = null,
        public readonly ?string $endDate = null,
        public readonly ?string $endTime = null,
        public readonly ?int $duration = null,
        public readonly ?string $durationType = null,
        public readonly ?string $salesEmployee = null,
        public readonly ?int $contactPersonCode = null,
        public readonly ?int $handledBy = null,
        public readonly ?string $reminder = null,
        public readonly ?int $reminderPeriod = null,
        public readonly ?string $reminderType = null,
        public readonly ?string $city = null,
        public readonly ?string $country = null,
        public readonly ?string $street = null,
        public readonly ?string $state = null,
        public readonly ?string $priority = null,
        public readonly ?string $details = null,
        public readonly ?string $activityType = null,
        public readonly ?string $location = null,
        public readonly ?BoYesNo $closed = null,
        public readonly ?int $docType = null,
        public readonly ?int $docNum = null,
        public readonly ?int $docEntry = null,
    ) {}

    protected static function mapFromArray(array $data): array
    {
        return [
            'activityCode' => isset($data['ActivityCode']) ? (int) $data['ActivityCode'] : null,
            'cardCode' => $data['CardCode'] ?? null,
            'notes' => $data['Notes'] ?? null,
            'activityDate' => $data['ActivityDate'] ?? null,
            'activityTime' => $data['ActivityTime'] ?? null,
            'startDate' => $data['StartDate'] ?? null,
            'startTime' => $data['StartTime'] ?? null,
            'endDate' => $data['EndDueDate'] ?? null,
            'endTime' => $data['EndTime'] ?? null,
            'duration' => isset($data['Duration']) ? (int) $data['Duration'] : null,
            'durationType' => $data['DurationType'] ?? null,
            'salesEmployee' => isset($data['SalesEmployee']) ? (string) $data['SalesEmployee'] : null,
            'contactPersonCode' => isset($data['ContactPersonCode']) ? (int) $data['ContactPersonCode'] : null,
            'handledBy' => isset($data['HandledBy']) ? (int) $data['HandledBy'] : null,
            'reminder' => $data['Reminder'] ?? null,
            'reminderPeriod' => isset($data['ReminderPeriod']) ? (int) $data['ReminderPeriod'] : null,
            'reminderType' => $data['ReminderType'] ?? null,
            'city' => $data['City'] ?? null,
            'country' => $data['Country'] ?? null,
            'street' => $data['Street'] ?? null,
            'state' => $data['State'] ?? null,
            'priority' => $data['Priority'] ?? null,
            'details' => $data['Details'] ?? null,
            'activityType' => isset($data['ActivityType']) ? (string) $data['ActivityType'] : null,
            'location' => isset($data['Location']) ? (string) $data['Location'] : null,
            'closed' => isset($data['Closed']) ? BoYesNo::tryFrom($data['Closed']) : null,
            'docType' => isset($data['DocType']) ? (int) $data['DocType'] : null,
            'docNum' => isset($data['DocNum']) ? (int) $data['DocNum'] : null,
            'docEntry' => isset($data['DocEntry']) ? (int) $data['DocEntry'] : null,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter([
            'ActivityCode' => $this->activityCode,
            'CardCode' => $this->cardCode,
            'Notes' => $this->notes,
            'ActivityDate' => $this->activityDate,
            'ActivityTime' => $this->activityTime,
            'StartDate' => $this->startDate,
            'StartTime' => $this->startTime,
            'EndDueDate' => $this->endDate,
            'EndTime' => $this->endTime,
            'Duration' => $this->duration,
            'DurationType' => $this->durationType,
            'SalesEmployee' => $this->salesEmployee,
            'ContactPersonCode' => $this->contactPersonCode,
            'HandledBy' => $this->handledBy,
            'Reminder' => $this->reminder,
            'ReminderPeriod' => $this->reminderPeriod,
            'ReminderType' => $this->reminderType,
            'City' => $this->city,
            'Country' => $this->country,
            'Street' => $this->street,
            'State' => $this->state,
            'Priority' => $this->priority,
            'Details' => $this->details,
            'ActivityType' => $this->activityType,
            'Location' => $this->location,
            'Closed' => $this->closed?->value,
            'DocType' => $this->docType,
            'DocNum' => $this->docNum,
            'DocEntry' => $this->docEntry,
        ], fn ($value) => $value !== null);
    }
}
