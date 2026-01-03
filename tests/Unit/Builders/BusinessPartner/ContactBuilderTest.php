<?php

declare(strict_types=1);

use SapB1\Toolkit\Builders\BusinessPartner\ContactBuilder;
use SapB1\Toolkit\Enums\BoYesNo;

it('creates builder with static method', function () {
    $builder = ContactBuilder::create();
    expect($builder)->toBeInstanceOf(ContactBuilder::class);
});

it('sets card code', function () {
    $data = ContactBuilder::create()
        ->cardCode('C001')
        ->build();

    expect($data['CardCode'])->toBe('C001');
});

it('sets name fields', function () {
    $data = ContactBuilder::create()
        ->name('John Smith')
        ->firstName('John')
        ->middleName('M')
        ->lastName('Smith')
        ->build();

    expect($data['Name'])->toBe('John Smith');
    expect($data['FirstName'])->toBe('John');
    expect($data['MiddleName'])->toBe('M');
    expect($data['LastName'])->toBe('Smith');
});

it('sets title', function () {
    $data = ContactBuilder::create()
        ->title('Mr.')
        ->build();

    expect($data['Title'])->toBe('Mr.');
});

it('sets position', function () {
    $data = ContactBuilder::create()
        ->position('Manager')
        ->build();

    expect($data['Position'])->toBe('Manager');
});

it('sets contact information', function () {
    $data = ContactBuilder::create()
        ->phone1('555-1234')
        ->phone2('555-5678')
        ->mobilePhone('555-9999')
        ->fax('555-0000')
        ->email('john@example.com')
        ->build();

    expect($data['Phone1'])->toBe('555-1234');
    expect($data['Phone2'])->toBe('555-5678');
    expect($data['MobilePhone'])->toBe('555-9999');
    expect($data['Fax'])->toBe('555-0000');
    expect($data['E_Mail'])->toBe('john@example.com');
});

it('sets address', function () {
    $data = ContactBuilder::create()
        ->address('123 Main St')
        ->build();

    expect($data['Address'])->toBe('123 Main St');
});

it('sets remarks', function () {
    $data = ContactBuilder::create()
        ->remarks1('Important contact')
        ->remarks2('Secondary remarks')
        ->build();

    expect($data['Remarks1'])->toBe('Important contact');
    expect($data['Remarks2'])->toBe('Secondary remarks');
});

it('sets active status', function () {
    $data = ContactBuilder::create()
        ->active(BoYesNo::Yes)
        ->build();

    expect($data['Active'])->toBe('tYES');
});

it('chains methods fluently', function () {
    $data = ContactBuilder::create()
        ->cardCode('C001')
        ->name('John Smith')
        ->email('john@example.com')
        ->active(BoYesNo::Yes)
        ->build();

    expect($data)->toHaveCount(4);
});

it('excludes null values from build', function () {
    $data = ContactBuilder::create()
        ->cardCode('C001')
        ->name('John Smith')
        ->build();

    expect($data)->toHaveKey('CardCode');
    expect($data)->toHaveKey('Name');
    expect($data)->not->toHaveKey('E_Mail');
    expect($data)->not->toHaveKey('Phone1');
});
