<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs\BusinessPartner;

use SapB1\Toolkit\DTOs\Base\BaseDto;
use SapB1\Toolkit\Enums\BoYesNo;

/**
 * @phpstan-consistent-constructor
 */
final class SalesStageDto extends BaseDto
{
    public function __construct(
        public readonly ?int $sequenceNo = null,
        public readonly ?string $name = null,
        public readonly ?int $stageno = null,
        public readonly ?float $closingPercentage = null,
        public readonly ?BoYesNo $cancelled = null,
    ) {}

    protected static function mapFromArray(array $data): array
    {
        $cancelled = null;
        if (isset($data['Cancelled'])) {
            $cancelled = BoYesNo::tryFrom($data['Cancelled']);
        }

        return [
            'sequenceNo' => $data['SequenceNo'] ?? null,
            'name' => $data['Name'] ?? null,
            'stageno' => $data['Stageno'] ?? null,
            'closingPercentage' => isset($data['ClosingPercentage']) ? (float) $data['ClosingPercentage'] : null,
            'cancelled' => $cancelled,
        ];
    }

    public function toArray(): array
    {
        return array_filter([
            'SequenceNo' => $this->sequenceNo,
            'Name' => $this->name,
            'Stageno' => $this->stageno,
            'ClosingPercentage' => $this->closingPercentage,
            'Cancelled' => $this->cancelled?->value,
        ], fn ($value) => $value !== null);
    }
}
