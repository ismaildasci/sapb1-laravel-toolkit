<?php

declare(strict_types=1);

use SapB1\Toolkit\DTOs\Production\ProductTreeDto;
use SapB1\Toolkit\DTOs\Production\ProductTreeLineDto;
use SapB1\Toolkit\Enums\ProductTreeType;

it('creates from array', function () {
    $data = [
        'TreeCode' => 'FG001',
        'TreeType' => 'iProductionTree',
        'Quantity' => 1.0,
        'Warehouse' => 'WH01',
        'PriceList' => 1,
        'Project' => 'PRJ001',
        'Remarks' => 'Finished goods BOM',
    ];

    $dto = ProductTreeDto::fromArray($data);

    expect($dto->treeCode)->toBe('FG001');
    expect($dto->treeType)->toBe(ProductTreeType::Assembly);
    expect($dto->quantity)->toBe(1.0);
    expect($dto->warehouse)->toBe('WH01');
    expect($dto->priceList)->toBe(1);
    expect($dto->project)->toBe('PRJ001');
    expect($dto->remarks)->toBe('Finished goods BOM');
});

it('creates from response with lines', function () {
    $response = [
        'TreeCode' => 'FG001',
        'Quantity' => 1.0,
        'ProductTreeLines' => [
            [
                'ItemCode' => 'RAW001',
                'Quantity' => 2.0,
                'Warehouse' => 'WH01',
            ],
            [
                'ItemCode' => 'RAW002',
                'Quantity' => 3.0,
                'Warehouse' => 'WH01',
            ],
        ],
    ];

    $dto = ProductTreeDto::fromResponse($response);

    expect($dto->treeCode)->toBe('FG001');
    expect($dto->productTreeLines)->toHaveCount(2);
    expect($dto->productTreeLines[0])->toBeInstanceOf(ProductTreeLineDto::class);
    expect($dto->productTreeLines[0]->itemCode)->toBe('RAW001');
    expect($dto->productTreeLines[0]->quantity)->toBe(2.0);
});

it('converts to array', function () {
    $dto = new ProductTreeDto(
        treeCode: 'FG001',
        treeType: ProductTreeType::Template,
        quantity: 1.0,
        warehouse: 'WH01',
    );

    $array = $dto->toArray();

    expect($array['TreeCode'])->toBe('FG001');
    expect($array['TreeType'])->toBe('iTemplateTree');
    expect($array['Quantity'])->toBe(1.0);
    expect($array['Warehouse'])->toBe('WH01');
});

it('excludes null values in toArray', function () {
    $dto = new ProductTreeDto(
        treeCode: 'FG001',
    );

    $array = $dto->toArray();

    expect($array)->toHaveKey('TreeCode');
    expect($array)->not->toHaveKey('Quantity');
    expect($array)->not->toHaveKey('Warehouse');
});
