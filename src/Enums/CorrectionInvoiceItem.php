<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Enums;

enum CorrectionInvoiceItem: string
{
    case ShouldBe = 'ciis_ShouldBe';
    case Was = 'ciis_Was';

    public function label(): string
    {
        return match ($this) {
            self::ShouldBe => 'Should Be',
            self::Was => 'Was',
        };
    }

    public function isShouldBe(): bool
    {
        return $this === self::ShouldBe;
    }

    public function isWas(): bool
    {
        return $this === self::Was;
    }
}
