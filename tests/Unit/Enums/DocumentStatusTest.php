<?php

declare(strict_types=1);

use SapB1\Toolkit\Enums\DocumentStatus;

it('has correct values', function () {
    expect(DocumentStatus::Open->value)->toBe('bost_Open');
    expect(DocumentStatus::Closed->value)->toBe('bost_Close');
    expect(DocumentStatus::Cancelled->value)->toBe('bost_Cancelled');
    expect(DocumentStatus::Pending->value)->toBe('bost_Pending');
    expect(DocumentStatus::Delivered->value)->toBe('bost_Delivered');
});

it('has correct labels', function () {
    expect(DocumentStatus::Open->label())->toBe('Open');
    expect(DocumentStatus::Closed->label())->toBe('Closed');
    expect(DocumentStatus::Cancelled->label())->toBe('Cancelled');
});

it('determines if editable', function () {
    expect(DocumentStatus::Open->isEditable())->toBeTrue();
    expect(DocumentStatus::Closed->isEditable())->toBeFalse();
    expect(DocumentStatus::Cancelled->isEditable())->toBeFalse();
});

it('determines if final', function () {
    expect(DocumentStatus::Open->isFinal())->toBeFalse();
    expect(DocumentStatus::Closed->isFinal())->toBeTrue();
    expect(DocumentStatus::Cancelled->isFinal())->toBeTrue();
});

it('determines if can close', function () {
    expect(DocumentStatus::Open->canClose())->toBeTrue();
    expect(DocumentStatus::Closed->canClose())->toBeFalse();
});

it('determines if can cancel', function () {
    expect(DocumentStatus::Open->canCancel())->toBeTrue();
    expect(DocumentStatus::Cancelled->canCancel())->toBeFalse();
});
