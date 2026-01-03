<?php

declare(strict_types=1);

use SapB1\Toolkit\Builders\BusinessPartner\CampaignBuilder;
use SapB1\Toolkit\DTOs\BusinessPartner\CampaignItemDto;
use SapB1\Toolkit\Enums\CampaignStatus;

it('creates builder with static method', function () {
    $builder = CampaignBuilder::create();
    expect($builder)->toBeInstanceOf(CampaignBuilder::class);
});

it('sets campaign name and type', function () {
    $data = CampaignBuilder::create()
        ->campaignName('Summer Sale')
        ->campaignType('Email')
        ->build();

    expect($data['CampaignName'])->toBe('Summer Sale');
    expect($data['CampaignType'])->toBe('Email');
});

it('sets target group', function () {
    $data = CampaignBuilder::create()
        ->targetGroup('VIP Customers')
        ->build();

    expect($data['TargetGroup'])->toBe('VIP Customers');
});

it('sets owner', function () {
    $data = CampaignBuilder::create()
        ->owner(1)
        ->build();

    expect($data['Owner'])->toBe(1);
});

it('sets status', function () {
    $data = CampaignBuilder::create()
        ->status(CampaignStatus::Active)
        ->build();

    expect($data['Status'])->toBe('cs_Active');
});

it('sets dates', function () {
    $data = CampaignBuilder::create()
        ->startDate('2024-06-01')
        ->finishDate('2024-08-31')
        ->build();

    expect($data['StartDate'])->toBe('2024-06-01');
    expect($data['FinishDate'])->toBe('2024-08-31');
});

it('sets remarks', function () {
    $data = CampaignBuilder::create()
        ->remarks('Annual summer promotion')
        ->build();

    expect($data['Remarks'])->toBe('Annual summer promotion');
});

it('sets attached document path', function () {
    $data = CampaignBuilder::create()
        ->attachedDocumentPath('/path/to/document.pdf')
        ->build();

    expect($data['AttachedDocumentPath'])->toBe('/path/to/document.pdf');
});

it('adds business partner with array', function () {
    $data = CampaignBuilder::create()
        ->campaignName('Test Campaign')
        ->addBusinessPartner([
            'ItemCode' => 'ITEM001',
            'ItemName' => 'Product A',
        ])
        ->build();

    expect($data['CampaignBusinessPartners'])->toHaveCount(1);
    expect($data['CampaignBusinessPartners'][0]['ItemCode'])->toBe('ITEM001');
});

it('adds business partner with DTO', function () {
    $item = new CampaignItemDto(
        campaignNumber: 1,
        lineNumber: 0,
        itemCode: 'ITEM001',
        itemName: 'Product A',
    );

    $data = CampaignBuilder::create()
        ->campaignName('Test Campaign')
        ->addBusinessPartner($item)
        ->build();

    expect($data['CampaignBusinessPartners'])->toHaveCount(1);
    expect($data['CampaignBusinessPartners'][0]['ItemCode'])->toBe('ITEM001');
});

it('sets multiple business partners at once', function () {
    $items = [
        ['ItemCode' => 'ITEM001', 'ItemName' => 'Product A'],
        ['ItemCode' => 'ITEM002', 'ItemName' => 'Product B'],
    ];

    $data = CampaignBuilder::create()
        ->campaignName('Test Campaign')
        ->campaignBusinessPartners($items)
        ->build();

    expect($data['CampaignBusinessPartners'])->toHaveCount(2);
});

it('chains methods fluently', function () {
    $data = CampaignBuilder::create()
        ->campaignName('Summer Sale')
        ->campaignType('Email')
        ->owner(1)
        ->status(CampaignStatus::Active)
        ->build();

    expect($data)->toHaveCount(4);
});

it('excludes null values from build', function () {
    $data = CampaignBuilder::create()
        ->campaignName('Summer Sale')
        ->owner(1)
        ->build();

    expect($data)->toHaveKey('CampaignName');
    expect($data)->toHaveKey('Owner');
    expect($data)->not->toHaveKey('CampaignType');
    expect($data)->not->toHaveKey('Status');
});
