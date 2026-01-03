<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs\Service;

use SapB1\Toolkit\DTOs\Base\BaseDto;

/**
 * @phpstan-consistent-constructor
 */
final class ServiceContractLineDto extends BaseDto
{
    public function __construct(
        public readonly ?int $lineNum = null,
        public readonly ?string $manufacturerSerialNum = null,
        public readonly ?string $internalSerialNum = null,
        public readonly ?string $itemCode = null,
        public readonly ?string $itemDescription = null,
        public readonly ?int $itemGroup = null,
        public readonly ?string $startDate = null,
        public readonly ?string $endDate = null,
        public readonly ?string $itemName = null,
    ) {}

    protected static function mapFromArray(array $data): array
    {
        return [
            'lineNum' => $data['LineNum'] ?? null,
            'manufacturerSerialNum' => $data['ManufacturerSerialNum'] ?? null,
            'internalSerialNum' => $data['InternalSerialNum'] ?? null,
            'itemCode' => $data['ItemCode'] ?? null,
            'itemDescription' => $data['ItemDescription'] ?? null,
            'itemGroup' => $data['ItemGroup'] ?? null,
            'startDate' => $data['StartDate'] ?? null,
            'endDate' => $data['EndDate'] ?? null,
            'itemName' => $data['ItemName'] ?? null,
        ];
    }

    public function toArray(): array
    {
        return array_filter([
            'LineNum' => $this->lineNum,
            'ManufacturerSerialNum' => $this->manufacturerSerialNum,
            'InternalSerialNum' => $this->internalSerialNum,
            'ItemCode' => $this->itemCode,
            'ItemDescription' => $this->itemDescription,
            'ItemGroup' => $this->itemGroup,
            'StartDate' => $this->startDate,
            'EndDate' => $this->endDate,
            'ItemName' => $this->itemName,
        ], fn ($value) => $value !== null);
    }
}
