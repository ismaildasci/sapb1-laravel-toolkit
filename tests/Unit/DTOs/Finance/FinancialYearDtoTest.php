<?php

declare(strict_types=1);

use SapB1\Toolkit\DTOs\Finance\FinancialYearDto;

it('creates from array', function () {
    $data = [
        'AbsEntry' => 1,
        'Code' => '2024',
        'Description' => 'Fiscal Year 2024',
        'StartDate' => '2024-01-01',
        'EndDate' => '2024-12-31',
    ];

    $dto = FinancialYearDto::fromArray($data);

    expect($dto->absEntry)->toBe(1);
    expect($dto->code)->toBe('2024');
    expect($dto->description)->toBe('Fiscal Year 2024');
    expect($dto->startDate)->toBe('2024-01-01');
    expect($dto->endDate)->toBe('2024-12-31');
});

it('creates from response', function () {
    $response = [
        'AbsEntry' => 2,
        'Code' => '2025',
        'AssessYear' => 2025,
        'AssessYearStart' => 2025,
    ];

    $dto = FinancialYearDto::fromResponse($response);

    expect($dto->absEntry)->toBe(2);
    expect($dto->code)->toBe('2025');
    expect($dto->assessYear)->toBe(2025);
    expect($dto->assessYearStart)->toBe(2025);
});

it('converts to array', function () {
    $dto = new FinancialYearDto(
        absEntry: 1,
        code: '2024',
        description: 'Test Year',
        startDate: '2024-01-01',
    );

    $array = $dto->toArray();

    expect($array['AbsEntry'])->toBe(1);
    expect($array['Code'])->toBe('2024');
    expect($array['Description'])->toBe('Test Year');
    expect($array['StartDate'])->toBe('2024-01-01');
});

it('excludes null values in toArray', function () {
    $dto = new FinancialYearDto(
        absEntry: 1,
        code: '2024',
    );

    $array = $dto->toArray();

    expect($array)->toHaveKey('AbsEntry');
    expect($array)->toHaveKey('Code');
    expect($array)->not->toHaveKey('Description');
    expect($array)->not->toHaveKey('StartDate');
});
