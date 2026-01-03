<?php

declare(strict_types=1);

use SapB1\Toolkit\DTOs\Production\ProductionOrderLineDto;
use SapB1\Toolkit\Enums\IssueMethod;

it('creates from array', function () {
    $data = [
        'LineNumber' => 1,
        'ItemNo' => 'RAW001',
        'BaseQuantity' => 2.0,
        'PlannedQuantity' => 20.0,
        'IssuedQuantity' => 10.0,
        'Warehouse' => 'WH01',
        'IssueMethod' => 'bomimBackflush',
    ];

    $dto = ProductionOrderLineDto::fromArray($data);

    expect($dto->lineNumber)->toBe(1);
    expect($dto->itemNo)->toBe('RAW001');
    expect($dto->baseQuantity)->toBe(2.0);
    expect($dto->plannedQuantity)->toBe(20.0);
    expect($dto->issuedQuantity)->toBe(10.0);
    expect($dto->warehouse)->toBe('WH01');
    expect($dto->issueMethod)->toBe(IssueMethod::Backflush);
});

it('converts to array', function () {
    $dto = new ProductionOrderLineDto(
        lineNumber: 1,
        itemNo: 'RAW001',
        plannedQuantity: 20.0,
        warehouse: 'WH01',
        issueMethod: IssueMethod::Manual,
    );

    $array = $dto->toArray();

    expect($array['LineNumber'])->toBe(1);
    expect($array['ItemNo'])->toBe('RAW001');
    expect($array['PlannedQuantity'])->toBe(20.0);
    expect($array['Warehouse'])->toBe('WH01');
    expect($array['IssueMethod'])->toBe('bomimManual');
});

it('excludes null values in toArray', function () {
    $dto = new ProductionOrderLineDto(
        lineNumber: 1,
        itemNo: 'RAW001',
    );

    $array = $dto->toArray();

    expect($array)->toHaveKey('LineNumber');
    expect($array)->toHaveKey('ItemNo');
    expect($array)->not->toHaveKey('Warehouse');
    expect($array)->not->toHaveKey('IssueMethod');
});
