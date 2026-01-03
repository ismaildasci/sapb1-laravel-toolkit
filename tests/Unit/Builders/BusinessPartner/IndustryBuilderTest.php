<?php

declare(strict_types=1);

use SapB1\Toolkit\Builders\BusinessPartner\IndustryBuilder;

it('creates builder with static method', function () {
    $builder = IndustryBuilder::create();
    expect($builder)->toBeInstanceOf(IndustryBuilder::class);
});

it('sets industry code', function () {
    $data = IndustryBuilder::create()
        ->industryCode(1)
        ->build();

    expect($data['IndustryCode'])->toBe(1);
});

it('sets industry name and description', function () {
    $data = IndustryBuilder::create()
        ->industryName('Manufacturing')
        ->industryDescription('Manufacturing industry sector')
        ->build();

    expect($data['IndustryName'])->toBe('Manufacturing');
    expect($data['IndustryDescription'])->toBe('Manufacturing industry sector');
});

it('chains methods fluently', function () {
    $data = IndustryBuilder::create()
        ->industryCode(1)
        ->industryName('Manufacturing')
        ->industryDescription('Manufacturing industry sector')
        ->build();

    expect($data)->toHaveCount(3);
});

it('excludes null values from build', function () {
    $data = IndustryBuilder::create()
        ->industryCode(1)
        ->industryName('Manufacturing')
        ->build();

    expect($data)->toHaveKey('IndustryCode');
    expect($data)->toHaveKey('IndustryName');
    expect($data)->not->toHaveKey('IndustryDescription');
});
