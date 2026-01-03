<?php

declare(strict_types=1);

use SapB1\Toolkit\Builders\BusinessPartner\CampaignResponseTypeBuilder;
use SapB1\Toolkit\Enums\BoYesNo;

it('creates builder with static method', function () {
    $builder = CampaignResponseTypeBuilder::create();
    expect($builder)->toBeInstanceOf(CampaignResponseTypeBuilder::class);
});

it('sets response type', function () {
    $data = CampaignResponseTypeBuilder::create()
        ->responseType('Interested')
        ->build();

    expect($data['ResponseType'])->toBe('Interested');
});

it('sets response type description', function () {
    $data = CampaignResponseTypeBuilder::create()
        ->responseTypeDescription('Interested in product')
        ->build();

    expect($data['ResponseTypeDescription'])->toBe('Interested in product');
});

it('sets active status', function () {
    $data = CampaignResponseTypeBuilder::create()
        ->isActive(BoYesNo::Yes)
        ->build();

    expect($data['IsActive'])->toBe('tYES');
});

it('chains methods fluently', function () {
    $data = CampaignResponseTypeBuilder::create()
        ->responseType('Interested')
        ->responseTypeDescription('Interested in product')
        ->isActive(BoYesNo::Yes)
        ->build();

    expect($data)->toHaveCount(3);
});

it('excludes null values from build', function () {
    $data = CampaignResponseTypeBuilder::create()
        ->responseType('Interested')
        ->responseTypeDescription('Interested')
        ->build();

    expect($data)->toHaveKey('ResponseType');
    expect($data)->toHaveKey('ResponseTypeDescription');
    expect($data)->not->toHaveKey('IsActive');
});
