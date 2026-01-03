<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs\Admin;

use SapB1\Toolkit\DTOs\Base\BaseDto;

/**
 * @phpstan-consistent-constructor
 */
final class SpecialPriceDto extends BaseDto
{
    public function __construct(
        public readonly ?string $itemCode = null,
        public readonly ?string $cardCode = null,
        public readonly ?float $price = null,
        public readonly ?string $currency = null,
        public readonly ?float $discountPercent = null,
        public readonly ?int $priceListNum = null,
        public readonly ?string $validFrom = null,
        public readonly ?string $validTo = null,
        public readonly ?int $autoUpdate = null,
        public readonly ?int $sourcePrice = null,
    ) {}

    protected static function mapFromArray(array $data): array
    {
        return [
            'itemCode' => $data['ItemCode'] ?? null,
            'cardCode' => $data['CardCode'] ?? null,
            'price' => isset($data['Price']) ? (float) $data['Price'] : null,
            'currency' => $data['Currency'] ?? null,
            'discountPercent' => isset($data['DiscountPercent']) ? (float) $data['DiscountPercent'] : null,
            'priceListNum' => $data['PriceListNum'] ?? null,
            'validFrom' => $data['ValidFrom'] ?? null,
            'validTo' => $data['ValidTo'] ?? null,
            'autoUpdate' => $data['AutoUpdate'] ?? null,
            'sourcePrice' => $data['SourcePrice'] ?? null,
        ];
    }

    public function toArray(): array
    {
        return array_filter([
            'ItemCode' => $this->itemCode,
            'CardCode' => $this->cardCode,
            'Price' => $this->price,
            'Currency' => $this->currency,
            'DiscountPercent' => $this->discountPercent,
            'PriceListNum' => $this->priceListNum,
            'ValidFrom' => $this->validFrom,
            'ValidTo' => $this->validTo,
            'AutoUpdate' => $this->autoUpdate,
            'SourcePrice' => $this->sourcePrice,
        ], fn ($value) => $value !== null);
    }
}
