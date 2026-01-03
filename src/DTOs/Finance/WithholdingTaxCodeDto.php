<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs\Finance;

use SapB1\Toolkit\DTOs\Base\BaseDto;

/**
 * @phpstan-consistent-constructor
 */
final class WithholdingTaxCodeDto extends BaseDto
{
    public function __construct(
        public readonly ?string $wTCode = null,
        public readonly ?string $wTName = null,
        public readonly ?string $officialCode = null,
        public readonly ?string $category = null,
        public readonly ?string $baseType = null,
        public readonly ?float $baseAmount = null,
        public readonly ?int $account = null,
        public readonly ?float $withholdingRate = null,
        public readonly ?string $effectiveDateFrom = null,
        public readonly ?string $effectiveDateTo = null,
        public readonly ?int $minAmount = null,
        public readonly ?int $maxAmount = null,
        public readonly ?float $returnType = null,
        public readonly ?string $inactive = null,
    ) {}

    protected static function mapFromArray(array $data): array
    {
        return [
            'wTCode' => $data['WTCode'] ?? null,
            'wTName' => $data['WTName'] ?? null,
            'officialCode' => $data['OfficialCode'] ?? null,
            'category' => $data['Category'] ?? null,
            'baseType' => $data['BaseType'] ?? null,
            'baseAmount' => isset($data['BaseAmount']) ? (float) $data['BaseAmount'] : null,
            'account' => isset($data['Account']) ? (int) $data['Account'] : null,
            'withholdingRate' => isset($data['WithholdingRate']) ? (float) $data['WithholdingRate'] : null,
            'effectiveDateFrom' => $data['EffectiveDateFrom'] ?? null,
            'effectiveDateTo' => $data['EffectiveDateTo'] ?? null,
            'minAmount' => isset($data['MinAmount']) ? (int) $data['MinAmount'] : null,
            'maxAmount' => isset($data['MaxAmount']) ? (int) $data['MaxAmount'] : null,
            'returnType' => isset($data['ReturnType']) ? (float) $data['ReturnType'] : null,
            'inactive' => $data['Inactive'] ?? null,
        ];
    }

    public function toArray(): array
    {
        return array_filter([
            'WTCode' => $this->wTCode,
            'WTName' => $this->wTName,
            'OfficialCode' => $this->officialCode,
            'Category' => $this->category,
            'BaseType' => $this->baseType,
            'BaseAmount' => $this->baseAmount,
            'Account' => $this->account,
            'WithholdingRate' => $this->withholdingRate,
            'EffectiveDateFrom' => $this->effectiveDateFrom,
            'EffectiveDateTo' => $this->effectiveDateTo,
            'MinAmount' => $this->minAmount,
            'MaxAmount' => $this->maxAmount,
            'ReturnType' => $this->returnType,
            'Inactive' => $this->inactive,
        ], fn ($value) => $value !== null);
    }
}
