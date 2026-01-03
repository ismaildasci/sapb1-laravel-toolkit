<?php

declare(strict_types=1);

use SapB1\Toolkit\Builders\Inventory\InventoryCycleBuilder;

it('creates builder with static method', function () {
    $builder = InventoryCycleBuilder::create();

    expect($builder)->toBeInstanceOf(InventoryCycleBuilder::class);
});

it('sets cycle name and frequency', function () {
    $builder = InventoryCycleBuilder::create()
        ->cycleName('Weekly Count')
        ->frequency(7);

    $data = $builder->build();

    expect($data['CycleName'])->toBe('Weekly Count');
    expect($data['Frequency'])->toBe(7);
});

it('sets day and hour', function () {
    $builder = InventoryCycleBuilder::create()
        ->day('Monday')
        ->hour('09:00');

    $data = $builder->build();

    expect($data['Day'])->toBe('Monday');
    expect($data['Hour'])->toBe('09:00');
});

it('sets next counting date', function () {
    $builder = InventoryCycleBuilder::create()
        ->nextCountingDate('2024-01-22');

    $data = $builder->build();

    expect($data['NextCountingDate'])->toBe('2024-01-22');
});

it('sets interval and recurrence type', function () {
    $builder = InventoryCycleBuilder::create()
        ->interval('30')
        ->recurrenceType('rtMonthly');

    $data = $builder->build();

    expect($data['Interval'])->toBe('30');
    expect($data['RecurrenceType'])->toBe('rtMonthly');
});

it('sets weekly schedule days', function () {
    $builder = InventoryCycleBuilder::create()
        ->mondayOn('tYES')
        ->tuesdayOn('tYES')
        ->wednesdayOn('tYES')
        ->thursdayOn('tYES')
        ->fridayOn('tYES')
        ->saturdayOn('tNO')
        ->sundayOn('tNO');

    $data = $builder->build();

    expect($data['MondayOn'])->toBe('tYES');
    expect($data['TuesdayOn'])->toBe('tYES');
    expect($data['WednesdayOn'])->toBe('tYES');
    expect($data['ThursdayOn'])->toBe('tYES');
    expect($data['FridayOn'])->toBe('tYES');
    expect($data['SaturdayOn'])->toBe('tNO');
    expect($data['SundayOn'])->toBe('tNO');
});

it('sets end after occurrences', function () {
    $builder = InventoryCycleBuilder::create()
        ->endAfterOccurrences(12);

    $data = $builder->build();

    expect($data['EndAfterOccurrences'])->toBe(12);
});

it('sets end date', function () {
    $builder = InventoryCycleBuilder::create()
        ->endDate('2024-12-31');

    $data = $builder->build();

    expect($data['EndDate'])->toBe('2024-12-31');
});

it('resets builder data', function () {
    $builder = InventoryCycleBuilder::create()
        ->cycleName('Test')
        ->frequency(7);

    $builder->reset();
    $data = $builder->build();

    expect($data)->toBeEmpty();
});

it('chains methods fluently', function () {
    $data = InventoryCycleBuilder::create()
        ->cycleName('Daily Count')
        ->frequency(1)
        ->hour('08:00')
        ->mondayOn('tYES')
        ->tuesdayOn('tYES')
        ->build();

    expect($data['CycleName'])->toBe('Daily Count');
    expect($data['Frequency'])->toBe(1);
    expect($data['Hour'])->toBe('08:00');
    expect($data['MondayOn'])->toBe('tYES');
    expect($data['TuesdayOn'])->toBe('tYES');
});

it('excludes null values from build', function () {
    $data = InventoryCycleBuilder::create()
        ->cycleName('Test')
        ->build();

    expect($data)->toHaveKey('CycleName');
    expect($data)->not->toHaveKey('Frequency');
    expect($data)->not->toHaveKey('Day');
});
