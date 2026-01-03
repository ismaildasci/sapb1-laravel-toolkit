<?php

declare(strict_types=1);

use SapB1\Toolkit\DTOs\BusinessPartner\CampaignDto;
use SapB1\Toolkit\DTOs\BusinessPartner\CampaignItemDto;
use SapB1\Toolkit\Enums\CampaignStatus;

it('creates from array', function () {
    $data = [
        'CampaignNumber' => 1,
        'CampaignName' => 'Summer Sale',
        'CampaignType' => 'Email',
        'TargetGroup' => 'VIP Customers',
        'Owner' => 1,
        'Status' => 'cs_Active',
        'StartDate' => '2024-06-01',
        'FinishDate' => '2024-08-31',
        'Remarks' => 'Annual summer promotion',
    ];

    $dto = CampaignDto::fromArray($data);

    expect($dto->campaignNumber)->toBe(1);
    expect($dto->campaignName)->toBe('Summer Sale');
    expect($dto->campaignType)->toBe('Email');
    expect($dto->targetGroup)->toBe('VIP Customers');
    expect($dto->owner)->toBe(1);
    expect($dto->status)->toBe(CampaignStatus::Active);
    expect($dto->startDate)->toBe('2024-06-01');
    expect($dto->finishDate)->toBe('2024-08-31');
});

it('creates from response with items', function () {
    $response = [
        'CampaignNumber' => 1,
        'CampaignName' => 'Summer Sale',
        'Status' => 'cs_Active',
        'CampaignBusinessPartners' => [
            [
                'CampaignNumber' => 1,
                'LineNumber' => 0,
                'ItemCode' => 'ITEM001',
                'ItemName' => 'Product A',
            ],
            [
                'CampaignNumber' => 1,
                'LineNumber' => 1,
                'ItemCode' => 'ITEM002',
                'ItemName' => 'Product B',
            ],
        ],
    ];

    $dto = CampaignDto::fromResponse($response);

    expect($dto->campaignNumber)->toBe(1);
    expect($dto->campaignBusinessPartners)->toHaveCount(2);
    expect($dto->campaignBusinessPartners[0])->toBeInstanceOf(CampaignItemDto::class);
    expect($dto->campaignBusinessPartners[0]->itemCode)->toBe('ITEM001');
    expect($dto->campaignBusinessPartners[1]->itemCode)->toBe('ITEM002');
});

it('converts to array', function () {
    $dto = new CampaignDto(
        campaignNumber: 1,
        campaignName: 'Summer Sale',
        owner: 1,
        status: CampaignStatus::Active,
        startDate: '2024-06-01',
    );

    $array = $dto->toArray();

    expect($array['CampaignNumber'])->toBe(1);
    expect($array['CampaignName'])->toBe('Summer Sale');
    expect($array['Owner'])->toBe(1);
    expect($array['Status'])->toBe('cs_Active');
    expect($array['StartDate'])->toBe('2024-06-01');
});

it('converts to array with items', function () {
    $item = new CampaignItemDto(
        campaignNumber: 1,
        lineNumber: 0,
        itemCode: 'ITEM001',
        itemName: 'Product A',
    );

    $dto = new CampaignDto(
        campaignNumber: 1,
        campaignName: 'Summer Sale',
        campaignBusinessPartners: [$item],
    );

    $array = $dto->toArray();

    expect($array)->toHaveKey('CampaignBusinessPartners');
    expect($array['CampaignBusinessPartners'])->toHaveCount(1);
    expect($array['CampaignBusinessPartners'][0]['ItemCode'])->toBe('ITEM001');
});

it('excludes null values in toArray', function () {
    $dto = new CampaignDto(
        campaignNumber: 1,
        campaignName: 'Summer Sale',
    );

    $array = $dto->toArray();

    expect($array)->toHaveKey('CampaignNumber');
    expect($array)->toHaveKey('CampaignName');
    expect($array)->not->toHaveKey('Owner');
    expect($array)->not->toHaveKey('Status');
});

it('handles draft status', function () {
    $data = [
        'CampaignNumber' => 1,
        'CampaignName' => 'Draft Campaign',
        'Status' => 'cs_Draft',
    ];

    $dto = CampaignDto::fromArray($data);

    expect($dto->status)->toBe(CampaignStatus::Draft);
});

it('handles finished status', function () {
    $data = [
        'CampaignNumber' => 1,
        'CampaignName' => 'Finished Campaign',
        'Status' => 'cs_Finished',
    ];

    $dto = CampaignDto::fromArray($data);

    expect($dto->status)->toBe(CampaignStatus::Finished);
});

it('handles cancelled status', function () {
    $data = [
        'CampaignNumber' => 1,
        'CampaignName' => 'Cancelled Campaign',
        'Status' => 'cs_Cancelled',
    ];

    $dto = CampaignDto::fromArray($data);

    expect($dto->status)->toBe(CampaignStatus::Cancelled);
});

it('handles empty items array', function () {
    $data = [
        'CampaignNumber' => 1,
        'CampaignName' => 'No Items Campaign',
    ];

    $dto = CampaignDto::fromArray($data);

    expect($dto->campaignBusinessPartners)->toBeArray();
    expect($dto->campaignBusinessPartners)->toBeEmpty();
});
