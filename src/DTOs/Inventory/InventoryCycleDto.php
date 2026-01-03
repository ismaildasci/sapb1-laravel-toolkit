<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs\Inventory;

use SapB1\Toolkit\DTOs\Base\BaseDto;

/**
 * @phpstan-consistent-constructor
 */
final class InventoryCycleDto extends BaseDto
{
    public function __construct(
        public readonly ?int $cycleCode = null,
        public readonly ?string $cycleName = null,
        public readonly ?int $frequency = null,
        public readonly ?string $day = null,
        public readonly ?string $hour = null,
        public readonly ?string $nextCountingDate = null,
        public readonly ?string $interval = null,
        public readonly ?string $sundayOn = null,
        public readonly ?string $mondayOn = null,
        public readonly ?string $tuesdayOn = null,
        public readonly ?string $wednesdayOn = null,
        public readonly ?string $thursdayOn = null,
        public readonly ?string $fridayOn = null,
        public readonly ?string $saturdayOn = null,
        public readonly ?string $recurrenceType = null,
        public readonly ?int $endAfterOccurrences = null,
        public readonly ?string $endDate = null,
    ) {}

    protected static function mapFromArray(array $data): array
    {
        return [
            'cycleCode' => isset($data['CycleCode']) ? (int) $data['CycleCode'] : null,
            'cycleName' => $data['CycleName'] ?? null,
            'frequency' => isset($data['Frequency']) ? (int) $data['Frequency'] : null,
            'day' => $data['Day'] ?? null,
            'hour' => $data['Hour'] ?? null,
            'nextCountingDate' => $data['NextCountingDate'] ?? null,
            'interval' => $data['Interval'] ?? null,
            'sundayOn' => $data['SundayOn'] ?? null,
            'mondayOn' => $data['MondayOn'] ?? null,
            'tuesdayOn' => $data['TuesdayOn'] ?? null,
            'wednesdayOn' => $data['WednesdayOn'] ?? null,
            'thursdayOn' => $data['ThursdayOn'] ?? null,
            'fridayOn' => $data['FridayOn'] ?? null,
            'saturdayOn' => $data['SaturdayOn'] ?? null,
            'recurrenceType' => $data['RecurrenceType'] ?? null,
            'endAfterOccurrences' => isset($data['EndAfterOccurrences']) ? (int) $data['EndAfterOccurrences'] : null,
            'endDate' => $data['EndDate'] ?? null,
        ];
    }

    public function toArray(): array
    {
        return array_filter([
            'CycleCode' => $this->cycleCode,
            'CycleName' => $this->cycleName,
            'Frequency' => $this->frequency,
            'Day' => $this->day,
            'Hour' => $this->hour,
            'NextCountingDate' => $this->nextCountingDate,
            'Interval' => $this->interval,
            'SundayOn' => $this->sundayOn,
            'MondayOn' => $this->mondayOn,
            'TuesdayOn' => $this->tuesdayOn,
            'WednesdayOn' => $this->wednesdayOn,
            'ThursdayOn' => $this->thursdayOn,
            'FridayOn' => $this->fridayOn,
            'SaturdayOn' => $this->saturdayOn,
            'RecurrenceType' => $this->recurrenceType,
            'EndAfterOccurrences' => $this->endAfterOccurrences,
            'EndDate' => $this->endDate,
        ], fn ($value) => $value !== null);
    }
}
