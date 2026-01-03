<?php

declare(strict_types=1);

use SapB1\Toolkit\DTOs\BusinessPartner\CampaignItemDto;

it('creates from array', function () {
    $data = [
        'CampaignNumber' => 1,
        'LineNumber' => 0,
        'ItemCode' => 'ITEM001',
        'ItemName' => 'Product A',
        'ItemType' => 'itItems',
        'ItemGroup' => 'Electronics',
    ];

    $dto = CampaignItemDto::fromArray($data);

    expect($dto->campaignNumber)->toBe(1);
    expect($dto->lineNumber)->toBe(0);
    expect($dto->itemCode)->toBe('ITEM001');
    expect($dto->itemName)->toBe('Product A');
    expect($dto->itemType)->toBe('itItems');
    expect($dto->itemGroup)->toBe('Electronics');
});

it('creates from response', function () {
    $response = [
        'CampaignNumber' => 2,
        'LineNumber' => 1,
        'ItemCode' => 'ITEM002',
        'ItemName' => 'Product B',
    ];

    $dto = CampaignItemDto::fromResponse($response);

    expect($dto->campaignNumber)->toBe(2);
    expect($dto->lineNumber)->toBe(1);
    expect($dto->itemCode)->toBe('ITEM002');
    expect($dto->itemName)->toBe('Product B');
});

it('converts to array', function () {
    $dto = new CampaignItemDto(
        campaignNumber: 1,
        lineNumber: 0,
        itemCode: 'ITEM001',
        itemName: 'Product A',
        itemType: 'itItems',
    );

    $array = $dto->toArray();

    expect($array['CampaignNumber'])->toBe(1);
    expect($array['LineNumber'])->toBe(0);
    expect($array['ItemCode'])->toBe('ITEM001');
    expect($array['ItemName'])->toBe('Product A');
    expect($array['ItemType'])->toBe('itItems');
});

it('excludes null values in toArray', function () {
    $dto = new CampaignItemDto(
        campaignNumber: 1,
        lineNumber: 0,
        itemCode: 'ITEM001',
    );

    $array = $dto->toArray();

    expect($array)->toHaveKey('CampaignNumber');
    expect($array)->toHaveKey('LineNumber');
    expect($array)->toHaveKey('ItemCode');
    expect($array)->not->toHaveKey('ItemName');
    expect($array)->not->toHaveKey('ItemType');
});

it('handles minimal data', function () {
    $data = [
        'CampaignNumber' => 1,
        'LineNumber' => 0,
    ];

    $dto = CampaignItemDto::fromArray($data);

    expect($dto->campaignNumber)->toBe(1);
    expect($dto->lineNumber)->toBe(0);
    expect($dto->itemCode)->toBeNull();
    expect($dto->itemName)->toBeNull();
});
