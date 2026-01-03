<?php

declare(strict_types=1);

use SapB1\Toolkit\DTOs\Production\ProductionOrderDto;
use SapB1\Toolkit\DTOs\Production\ProductionOrderLineDto;
use SapB1\Toolkit\Enums\ProductionOrderStatus;
use SapB1\Toolkit\Enums\ProductionOrderType;

it('creates from array', function () {
    $data = [
        'AbsoluteEntry' => 1,
        'DocumentNumber' => 100,
        'Series' => 1,
        'ItemNo' => 'A001',
        'ProductionOrderStatus' => 'boposPlanned',
        'ProductionOrderType' => 'bopotStandard',
        'PlannedQuantity' => 10.0,
        'CompletedQuantity' => 5.0,
        'Warehouse' => 'WH01',
        'DueDate' => '2026-01-15',
        'Remarks' => 'Test production order',
    ];

    $dto = ProductionOrderDto::fromArray($data);

    expect($dto->absoluteEntry)->toBe(1);
    expect($dto->documentNumber)->toBe(100);
    expect($dto->itemNo)->toBe('A001');
    expect($dto->productionOrderStatus)->toBe(ProductionOrderStatus::Planned);
    expect($dto->productionOrderType)->toBe(ProductionOrderType::Standard);
    expect($dto->plannedQuantity)->toBe(10.0);
    expect($dto->completedQuantity)->toBe(5.0);
    expect($dto->warehouse)->toBe('WH01');
    expect($dto->remarks)->toBe('Test production order');
});

it('creates from response with lines', function () {
    $response = [
        'AbsoluteEntry' => 1,
        'ItemNo' => 'A001',
        'PlannedQuantity' => 10.0,
        'ProductionOrderLines' => [
            [
                'LineNumber' => 1,
                'ItemNo' => 'RAW001',
                'PlannedQuantity' => 20.0,
            ],
            [
                'LineNumber' => 2,
                'ItemNo' => 'RAW002',
                'PlannedQuantity' => 5.0,
            ],
        ],
    ];

    $dto = ProductionOrderDto::fromResponse($response);

    expect($dto->absoluteEntry)->toBe(1);
    expect($dto->productionOrderLines)->toHaveCount(2);
    expect($dto->productionOrderLines[0])->toBeInstanceOf(ProductionOrderLineDto::class);
    expect($dto->productionOrderLines[0]->itemNo)->toBe('RAW001');
});

it('converts to array', function () {
    $dto = new ProductionOrderDto(
        absoluteEntry: 1,
        documentNumber: 100,
        itemNo: 'A001',
        productionOrderStatus: ProductionOrderStatus::Released,
        plannedQuantity: 10.0,
        warehouse: 'WH01',
    );

    $array = $dto->toArray();

    expect($array['AbsoluteEntry'])->toBe(1);
    expect($array['DocumentNumber'])->toBe(100);
    expect($array['ItemNo'])->toBe('A001');
    expect($array['ProductionOrderStatus'])->toBe('boposReleased');
    expect($array['PlannedQuantity'])->toBe(10.0);
    expect($array['Warehouse'])->toBe('WH01');
});

it('excludes null values in toArray', function () {
    $dto = new ProductionOrderDto(
        absoluteEntry: 1,
        itemNo: 'A001',
    );

    $array = $dto->toArray();

    expect($array)->toHaveKey('AbsoluteEntry');
    expect($array)->toHaveKey('ItemNo');
    expect($array)->not->toHaveKey('DocumentNumber');
    expect($array)->not->toHaveKey('Remarks');
});
