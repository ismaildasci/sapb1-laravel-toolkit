<?php

declare(strict_types=1);

use SapB1\Toolkit\DTOs\Finance\PaymentTermsTypeDto;

it('creates from array', function () {
    $data = [
        'GroupNumber' => 1,
        'PaymentTermsGroupName' => 'Net 30',
        'StartFrom' => 'plfMonthStart',
        'NumberOfAdditionalMonths' => 1,
        'NumberOfAdditionalDays' => 0,
    ];

    $dto = PaymentTermsTypeDto::fromArray($data);

    expect($dto->groupNumber)->toBe(1);
    expect($dto->paymentTermsGroupName)->toBe('Net 30');
    expect($dto->startFrom)->toBe('plfMonthStart');
    expect($dto->numberOfAdditionalMonths)->toBe(1);
    expect($dto->numberOfAdditionalDays)->toBe(0);
});

it('creates from response', function () {
    $response = [
        'GroupNumber' => 2,
        'PaymentTermsGroupName' => 'Net 60',
        'OpenReceipt' => 'tYES',
        'PriceListNo' => 1,
    ];

    $dto = PaymentTermsTypeDto::fromResponse($response);

    expect($dto->groupNumber)->toBe(2);
    expect($dto->paymentTermsGroupName)->toBe('Net 60');
    expect($dto->openReceipt)->toBe('tYES');
    expect($dto->priceListNo)->toBe(1);
});

it('converts to array', function () {
    $dto = new PaymentTermsTypeDto(
        groupNumber: 1,
        paymentTermsGroupName: 'Cash',
        numberOfAdditionalDays: 0,
    );

    $array = $dto->toArray();

    expect($array['GroupNumber'])->toBe(1);
    expect($array['PaymentTermsGroupName'])->toBe('Cash');
    expect($array['NumberOfAdditionalDays'])->toBe(0);
});

it('excludes null values in toArray', function () {
    $dto = new PaymentTermsTypeDto(
        groupNumber: 1,
        paymentTermsGroupName: 'Test',
    );

    $array = $dto->toArray();

    expect($array)->toHaveKey('GroupNumber');
    expect($array)->toHaveKey('PaymentTermsGroupName');
    expect($array)->not->toHaveKey('StartFrom');
    expect($array)->not->toHaveKey('NumberOfAdditionalMonths');
});
