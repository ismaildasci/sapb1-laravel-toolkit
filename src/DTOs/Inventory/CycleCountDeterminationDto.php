<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs\Inventory;

use SapB1\Toolkit\DTOs\Base\BaseDto;

/**
 * @phpstan-consistent-constructor
 */
final class CycleCountDeterminationDto extends BaseDto
{
    public function __construct(
        public readonly ?string $warehouseCode = null,
        public readonly ?int $cycleCode = null,
        public readonly ?string $alert = null,
        public readonly ?string $destinationUser = null,
        public readonly ?string $nextCountingDate = null,
        public readonly ?float $time = null,
        public readonly ?string $excludeItemsWithZeroQuantity = null,
        public readonly ?string $changeExistingTimeAndAlert = null,
    ) {}

    protected static function mapFromArray(array $data): array
    {
        return [
            'warehouseCode' => $data['WarehouseCode'] ?? null,
            'cycleCode' => isset($data['CycleCode']) ? (int) $data['CycleCode'] : null,
            'alert' => $data['Alert'] ?? null,
            'destinationUser' => $data['DestinationUser'] ?? null,
            'nextCountingDate' => $data['NextCountingDate'] ?? null,
            'time' => isset($data['Time']) ? (float) $data['Time'] : null,
            'excludeItemsWithZeroQuantity' => $data['ExcludeItemsWithZeroQuantity'] ?? null,
            'changeExistingTimeAndAlert' => $data['ChangeExistingTimeAndAlert'] ?? null,
        ];
    }

    public function toArray(): array
    {
        return array_filter([
            'WarehouseCode' => $this->warehouseCode,
            'CycleCode' => $this->cycleCode,
            'Alert' => $this->alert,
            'DestinationUser' => $this->destinationUser,
            'NextCountingDate' => $this->nextCountingDate,
            'Time' => $this->time,
            'ExcludeItemsWithZeroQuantity' => $this->excludeItemsWithZeroQuantity,
            'ChangeExistingTimeAndAlert' => $this->changeExistingTimeAndAlert,
        ], fn ($value) => $value !== null);
    }
}
