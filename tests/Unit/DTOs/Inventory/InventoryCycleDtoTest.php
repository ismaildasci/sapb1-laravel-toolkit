<?php

declare(strict_types=1);

use SapB1\Toolkit\DTOs\Inventory\InventoryCycleDto;

it('creates from array', function () {
    $data = [
        'CycleCode' => 1,
        'CycleName' => 'Weekly Count',
        'Frequency' => 7,
        'Day' => 'Monday',
        'Hour' => '09:00',
        'NextCountingDate' => '2024-01-22',
    ];

    $dto = InventoryCycleDto::fromArray($data);

    expect($dto->cycleCode)->toBe(1);
    expect($dto->cycleName)->toBe('Weekly Count');
    expect($dto->frequency)->toBe(7);
    expect($dto->day)->toBe('Monday');
    expect($dto->hour)->toBe('09:00');
    expect($dto->nextCountingDate)->toBe('2024-01-22');
});

it('creates from response', function () {
    $response = [
        'CycleCode' => 2,
        'CycleName' => 'Monthly Count',
        'Interval' => '30',
        'RecurrenceType' => 'rtMonthly',
        'EndAfterOccurrences' => 12,
    ];

    $dto = InventoryCycleDto::fromResponse($response);

    expect($dto->cycleCode)->toBe(2);
    expect($dto->cycleName)->toBe('Monthly Count');
    expect($dto->interval)->toBe('30');
    expect($dto->recurrenceType)->toBe('rtMonthly');
    expect($dto->endAfterOccurrences)->toBe(12);
});

it('converts to array', function () {
    $dto = new InventoryCycleDto(
        cycleCode: 1,
        cycleName: 'Test Cycle',
        frequency: 7,
        day: 'Monday',
    );

    $array = $dto->toArray();

    expect($array['CycleCode'])->toBe(1);
    expect($array['CycleName'])->toBe('Test Cycle');
    expect($array['Frequency'])->toBe(7);
    expect($array['Day'])->toBe('Monday');
});

it('handles weekly schedule days', function () {
    $data = [
        'CycleCode' => 1,
        'CycleName' => 'Weekdays',
        'MondayOn' => 'tYES',
        'TuesdayOn' => 'tYES',
        'WednesdayOn' => 'tYES',
        'ThursdayOn' => 'tYES',
        'FridayOn' => 'tYES',
        'SaturdayOn' => 'tNO',
        'SundayOn' => 'tNO',
    ];

    $dto = InventoryCycleDto::fromArray($data);

    expect($dto->mondayOn)->toBe('tYES');
    expect($dto->tuesdayOn)->toBe('tYES');
    expect($dto->wednesdayOn)->toBe('tYES');
    expect($dto->thursdayOn)->toBe('tYES');
    expect($dto->fridayOn)->toBe('tYES');
    expect($dto->saturdayOn)->toBe('tNO');
    expect($dto->sundayOn)->toBe('tNO');
});

it('excludes null values in toArray', function () {
    $dto = new InventoryCycleDto(
        cycleCode: 1,
        cycleName: 'Test',
    );

    $array = $dto->toArray();

    expect($array)->toHaveKey('CycleCode');
    expect($array)->toHaveKey('CycleName');
    expect($array)->not->toHaveKey('Frequency');
    expect($array)->not->toHaveKey('Day');
    expect($array)->not->toHaveKey('MondayOn');
});

it('handles end date', function () {
    $dto = new InventoryCycleDto(
        cycleCode: 1,
        cycleName: 'Temporary Cycle',
        endDate: '2024-12-31',
    );

    $array = $dto->toArray();

    expect($array['EndDate'])->toBe('2024-12-31');
});
