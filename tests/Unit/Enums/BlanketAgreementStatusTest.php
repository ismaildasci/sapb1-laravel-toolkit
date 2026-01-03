<?php

declare(strict_types=1);

use SapB1\Toolkit\Enums\BlanketAgreementStatus;

it('has correct values', function () {
    expect(BlanketAgreementStatus::Approved->value)->toBe('asApproved');
    expect(BlanketAgreementStatus::OnHold->value)->toBe('asOnHold');
    expect(BlanketAgreementStatus::Draft->value)->toBe('asDraft');
    expect(BlanketAgreementStatus::Terminated->value)->toBe('asTerminated');
});

it('returns correct labels', function () {
    expect(BlanketAgreementStatus::Approved->label())->toBe('Approved');
    expect(BlanketAgreementStatus::OnHold->label())->toBe('On Hold');
    expect(BlanketAgreementStatus::Draft->label())->toBe('Draft');
    expect(BlanketAgreementStatus::Terminated->label())->toBe('Terminated');
});

it('can check if approved', function () {
    expect(BlanketAgreementStatus::Approved->isApproved())->toBeTrue();
    expect(BlanketAgreementStatus::Draft->isApproved())->toBeFalse();
});

it('can check if active', function () {
    expect(BlanketAgreementStatus::Approved->isActive())->toBeTrue();
    expect(BlanketAgreementStatus::OnHold->isActive())->toBeTrue();
    expect(BlanketAgreementStatus::Draft->isActive())->toBeFalse();
    expect(BlanketAgreementStatus::Terminated->isActive())->toBeFalse();
});

it('can check if final', function () {
    expect(BlanketAgreementStatus::Terminated->isFinal())->toBeTrue();
    expect(BlanketAgreementStatus::Approved->isFinal())->toBeFalse();
});

it('can be created from value', function () {
    expect(BlanketAgreementStatus::from('asApproved'))->toBe(BlanketAgreementStatus::Approved);
    expect(BlanketAgreementStatus::tryFrom('asInvalid'))->toBeNull();
});
