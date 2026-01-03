<?php

declare(strict_types=1);

use SapB1\Toolkit\Enums\CorrectionInvoiceItem;

it('has correct values', function () {
    expect(CorrectionInvoiceItem::ShouldBe->value)->toBe('ciis_ShouldBe');
    expect(CorrectionInvoiceItem::Was->value)->toBe('ciis_Was');
});

it('returns correct labels', function () {
    expect(CorrectionInvoiceItem::ShouldBe->label())->toBe('Should Be');
    expect(CorrectionInvoiceItem::Was->label())->toBe('Was');
});

it('can check if should be', function () {
    expect(CorrectionInvoiceItem::ShouldBe->isShouldBe())->toBeTrue();
    expect(CorrectionInvoiceItem::Was->isShouldBe())->toBeFalse();
});

it('can check if was', function () {
    expect(CorrectionInvoiceItem::Was->isWas())->toBeTrue();
    expect(CorrectionInvoiceItem::ShouldBe->isWas())->toBeFalse();
});

it('can be created from value', function () {
    expect(CorrectionInvoiceItem::from('ciis_ShouldBe'))->toBe(CorrectionInvoiceItem::ShouldBe);
    expect(CorrectionInvoiceItem::tryFrom('ciis_Invalid'))->toBeNull();
});
