<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs\Admin;

use SapB1\Toolkit\DTOs\Base\BaseDto;
use SapB1\Toolkit\Enums\BoYesNo;

/**
 * @phpstan-consistent-constructor
 */
final class PriceListDto extends BaseDto
{
    public function __construct(
        public readonly ?int $priceListNo = null,
        public readonly ?string $priceListName = null,
        public readonly ?BoYesNo $isGrossPrice = null,
        public readonly ?BoYesNo $active = null,
        public readonly ?string $validFrom = null,
        public readonly ?string $validTo = null,
        public readonly ?string $defaultPrimeCurrency = null,
        public readonly ?string $defaultAdditionalCurrency1 = null,
        public readonly ?string $defaultAdditionalCurrency2 = null,
        public readonly ?int $roundingRule = null,
        public readonly ?int $basePriceList = null,
        public readonly ?float $factor = null,
    ) {}

    protected static function mapFromArray(array $data): array
    {
        return [
            'priceListNo' => $data['PriceListNo'] ?? null,
            'priceListName' => $data['PriceListName'] ?? null,
            'isGrossPrice' => isset($data['IsGrossPrice']) ? BoYesNo::tryFrom($data['IsGrossPrice']) : null,
            'active' => isset($data['Active']) ? BoYesNo::tryFrom($data['Active']) : null,
            'validFrom' => $data['ValidFrom'] ?? null,
            'validTo' => $data['ValidTo'] ?? null,
            'defaultPrimeCurrency' => $data['DefaultPrimeCurrency'] ?? null,
            'defaultAdditionalCurrency1' => $data['DefaultAdditionalCurrency1'] ?? null,
            'defaultAdditionalCurrency2' => $data['DefaultAdditionalCurrency2'] ?? null,
            'roundingRule' => $data['RoundingRule'] ?? null,
            'basePriceList' => $data['BasePriceList'] ?? null,
            'factor' => isset($data['Factor']) ? (float) $data['Factor'] : null,
        ];
    }

    public function toArray(): array
    {
        return array_filter([
            'PriceListNo' => $this->priceListNo,
            'PriceListName' => $this->priceListName,
            'IsGrossPrice' => $this->isGrossPrice?->value,
            'Active' => $this->active?->value,
            'ValidFrom' => $this->validFrom,
            'ValidTo' => $this->validTo,
            'DefaultPrimeCurrency' => $this->defaultPrimeCurrency,
            'DefaultAdditionalCurrency1' => $this->defaultAdditionalCurrency1,
            'DefaultAdditionalCurrency2' => $this->defaultAdditionalCurrency2,
            'RoundingRule' => $this->roundingRule,
            'BasePriceList' => $this->basePriceList,
            'Factor' => $this->factor,
        ], fn ($value) => $value !== null);
    }

    public function isActive(): bool
    {
        return $this->active === BoYesNo::Yes;
    }
}
