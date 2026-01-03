<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs\Finance;

use SapB1\Toolkit\DTOs\Base\BaseDto;

/**
 * @phpstan-consistent-constructor
 */
final class CurrencyDto extends BaseDto
{
    public function __construct(
        public readonly ?string $code = null,
        public readonly ?string $name = null,
        public readonly ?string $documentsCode = null,
        public readonly ?string $internationalDescription = null,
        public readonly ?string $hundredthName = null,
        public readonly ?string $englishName = null,
        public readonly ?string $englishHundredthName = null,
        public readonly ?string $pluralInternationalDescription = null,
        public readonly ?string $pluralHundredthName = null,
        public readonly ?string $pluralEnglishName = null,
        public readonly ?string $pluralEnglishHundredthName = null,
        public readonly ?string $decimals = null,
        public readonly ?string $rounding = null,
        public readonly ?int $roundingInPayment = null,
        public readonly ?int $maxIncomingAmtDiff = null,
        public readonly ?int $maxOutgoingAmtDiff = null,
        public readonly ?int $maxIncomingAmtDiffPercent = null,
        public readonly ?int $maxOutgoingAmtDiffPercent = null,
    ) {}

    protected static function mapFromArray(array $data): array
    {
        return [
            'code' => $data['Code'] ?? null,
            'name' => $data['Name'] ?? null,
            'documentsCode' => $data['DocumentsCode'] ?? null,
            'internationalDescription' => $data['InternationalDescription'] ?? null,
            'hundredthName' => $data['HundredthName'] ?? null,
            'englishName' => $data['EnglishName'] ?? null,
            'englishHundredthName' => $data['EnglishHundredthName'] ?? null,
            'pluralInternationalDescription' => $data['PluralInternationalDescription'] ?? null,
            'pluralHundredthName' => $data['PluralHundredthName'] ?? null,
            'pluralEnglishName' => $data['PluralEnglishName'] ?? null,
            'pluralEnglishHundredthName' => $data['PluralEnglishHundredthName'] ?? null,
            'decimals' => $data['Decimals'] ?? null,
            'rounding' => $data['Rounding'] ?? null,
            'roundingInPayment' => isset($data['RoundingInPayment']) ? (int) $data['RoundingInPayment'] : null,
            'maxIncomingAmtDiff' => isset($data['MaxIncomingAmtDiff']) ? (int) $data['MaxIncomingAmtDiff'] : null,
            'maxOutgoingAmtDiff' => isset($data['MaxOutgoingAmtDiff']) ? (int) $data['MaxOutgoingAmtDiff'] : null,
            'maxIncomingAmtDiffPercent' => isset($data['MaxIncomingAmtDiffPercent']) ? (int) $data['MaxIncomingAmtDiffPercent'] : null,
            'maxOutgoingAmtDiffPercent' => isset($data['MaxOutgoingAmtDiffPercent']) ? (int) $data['MaxOutgoingAmtDiffPercent'] : null,
        ];
    }

    public function toArray(): array
    {
        return array_filter([
            'Code' => $this->code,
            'Name' => $this->name,
            'DocumentsCode' => $this->documentsCode,
            'InternationalDescription' => $this->internationalDescription,
            'HundredthName' => $this->hundredthName,
            'EnglishName' => $this->englishName,
            'EnglishHundredthName' => $this->englishHundredthName,
            'PluralInternationalDescription' => $this->pluralInternationalDescription,
            'PluralHundredthName' => $this->pluralHundredthName,
            'PluralEnglishName' => $this->pluralEnglishName,
            'PluralEnglishHundredthName' => $this->pluralEnglishHundredthName,
            'Decimals' => $this->decimals,
            'Rounding' => $this->rounding,
            'RoundingInPayment' => $this->roundingInPayment,
            'MaxIncomingAmtDiff' => $this->maxIncomingAmtDiff,
            'MaxOutgoingAmtDiff' => $this->maxOutgoingAmtDiff,
            'MaxIncomingAmtDiffPercent' => $this->maxIncomingAmtDiffPercent,
            'MaxOutgoingAmtDiffPercent' => $this->maxOutgoingAmtDiffPercent,
        ], fn ($value) => $value !== null);
    }
}
