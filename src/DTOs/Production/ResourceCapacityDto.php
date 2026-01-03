<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs\Production;

use SapB1\Toolkit\DTOs\Base\BaseDto;

/**
 * @phpstan-consistent-constructor
 */
final class ResourceCapacityDto extends BaseDto
{
    public function __construct(
        public readonly ?int $id = null,
        public readonly ?string $code = null,
        public readonly ?string $warehouse = null,
        public readonly ?string $date = null,
        public readonly ?string $type = null,
        public readonly ?float $capacity = null,
        public readonly ?string $sourceType = null,
        public readonly ?int $sourceEntry = null,
        public readonly ?int $sourceLineNum = null,
        public readonly ?string $baseType = null,
        public readonly ?int $baseEntry = null,
        public readonly ?int $baseLineNum = null,
        public readonly ?string $action = null,
        public readonly ?int $owningType = null,
        public readonly ?int $owningEntry = null,
        public readonly ?int $owningLineNum = null,
        public readonly ?float $revertedCapacity = null,
        public readonly ?string $memo = null,
    ) {}

    protected static function mapFromArray(array $data): array
    {
        return [
            'id' => $data['Id'] ?? null,
            'code' => $data['Code'] ?? null,
            'warehouse' => $data['Warehouse'] ?? null,
            'date' => $data['Date'] ?? null,
            'type' => $data['Type'] ?? null,
            'capacity' => isset($data['Capacity']) ? (float) $data['Capacity'] : null,
            'sourceType' => $data['SourceType'] ?? null,
            'sourceEntry' => $data['SourceEntry'] ?? null,
            'sourceLineNum' => $data['SourceLineNum'] ?? null,
            'baseType' => $data['BaseType'] ?? null,
            'baseEntry' => $data['BaseEntry'] ?? null,
            'baseLineNum' => $data['BaseLineNum'] ?? null,
            'action' => $data['Action'] ?? null,
            'owningType' => $data['OwningType'] ?? null,
            'owningEntry' => $data['OwningEntry'] ?? null,
            'owningLineNum' => $data['OwningLineNum'] ?? null,
            'revertedCapacity' => isset($data['RevertedCapacity']) ? (float) $data['RevertedCapacity'] : null,
            'memo' => $data['Memo'] ?? null,
        ];
    }

    public function toArray(): array
    {
        return array_filter([
            'Id' => $this->id,
            'Code' => $this->code,
            'Warehouse' => $this->warehouse,
            'Date' => $this->date,
            'Type' => $this->type,
            'Capacity' => $this->capacity,
            'SourceType' => $this->sourceType,
            'SourceEntry' => $this->sourceEntry,
            'SourceLineNum' => $this->sourceLineNum,
            'BaseType' => $this->baseType,
            'BaseEntry' => $this->baseEntry,
            'BaseLineNum' => $this->baseLineNum,
            'Action' => $this->action,
            'OwningType' => $this->owningType,
            'OwningEntry' => $this->owningEntry,
            'OwningLineNum' => $this->owningLineNum,
            'RevertedCapacity' => $this->revertedCapacity,
            'Memo' => $this->memo,
        ], fn ($value) => $value !== null);
    }
}
