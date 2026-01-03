<?php

declare(strict_types=1);

use SapB1\Toolkit\DTOs\Finance\CreditCardDto;

it('creates from array', function () {
    $data = [
        'CreditCardCode' => 1,
        'CreditCardName' => 'Visa',
        'GLAccount' => '1000',
        'Telephone' => '1234567890',
    ];

    $dto = CreditCardDto::fromArray($data);

    expect($dto->creditCardCode)->toBe(1);
    expect($dto->creditCardName)->toBe('Visa');
    expect($dto->gLAccount)->toBe('1000');
    expect($dto->telephone)->toBe('1234567890');
});

it('creates from response', function () {
    $response = [
        'CreditCardCode' => 2,
        'CreditCardName' => 'MasterCard',
        'CompanyId' => 'COMP01',
    ];

    $dto = CreditCardDto::fromResponse($response);

    expect($dto->creditCardCode)->toBe(2);
    expect($dto->creditCardName)->toBe('MasterCard');
    expect($dto->companyId)->toBe('COMP01');
});

it('converts to array', function () {
    $dto = new CreditCardDto(
        creditCardCode: 1,
        creditCardName: 'Visa',
        gLAccount: '1000',
    );

    $array = $dto->toArray();

    expect($array['CreditCardCode'])->toBe(1);
    expect($array['CreditCardName'])->toBe('Visa');
    expect($array['GLAccount'])->toBe('1000');
});

it('excludes null values in toArray', function () {
    $dto = new CreditCardDto(
        creditCardCode: 1,
        creditCardName: 'Test',
    );

    $array = $dto->toArray();

    expect($array)->toHaveKey('CreditCardCode');
    expect($array)->toHaveKey('CreditCardName');
    expect($array)->not->toHaveKey('GLAccount');
    expect($array)->not->toHaveKey('Telephone');
});
