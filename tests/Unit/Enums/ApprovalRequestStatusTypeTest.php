<?php

declare(strict_types=1);

use SapB1\Toolkit\Enums\ApprovalRequestStatusType;

it('has correct values', function () {
    expect(ApprovalRequestStatusType::Pending->value)->toBe('arstPending');
    expect(ApprovalRequestStatusType::Approved->value)->toBe('arstApproved');
    expect(ApprovalRequestStatusType::NotApproved->value)->toBe('arstNotApproved');
    expect(ApprovalRequestStatusType::Generated->value)->toBe('arstGenerated');
    expect(ApprovalRequestStatusType::Cancelled->value)->toBe('arstCancelled');
});

it('returns correct labels', function () {
    expect(ApprovalRequestStatusType::Pending->label())->toBe('Pending');
    expect(ApprovalRequestStatusType::Approved->label())->toBe('Approved');
    expect(ApprovalRequestStatusType::NotApproved->label())->toBe('Not Approved');
});

it('can check if pending', function () {
    expect(ApprovalRequestStatusType::Pending->isPending())->toBeTrue();
    expect(ApprovalRequestStatusType::Approved->isPending())->toBeFalse();
});

it('can check if approved', function () {
    expect(ApprovalRequestStatusType::Approved->isApproved())->toBeTrue();
    expect(ApprovalRequestStatusType::Generated->isApproved())->toBeTrue();
    expect(ApprovalRequestStatusType::GeneratedByAuthorizer->isApproved())->toBeTrue();
    expect(ApprovalRequestStatusType::Pending->isApproved())->toBeFalse();
    expect(ApprovalRequestStatusType::NotApproved->isApproved())->toBeFalse();
});

it('can be created from value', function () {
    expect(ApprovalRequestStatusType::from('arstPending'))->toBe(ApprovalRequestStatusType::Pending);
    expect(ApprovalRequestStatusType::tryFrom('arstInvalid'))->toBeNull();
});
