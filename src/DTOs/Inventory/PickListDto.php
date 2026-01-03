<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs\Inventory;

use SapB1\Toolkit\DTOs\Base\BaseDto;

/**
 * @phpstan-consistent-constructor
 */
final class PickListDto extends BaseDto
{
    /**
     * @param  array<PickListLineDto>  $pickListsLines
     */
    public function __construct(
        public readonly ?int $absoluteEntry = null,
        public readonly ?string $name = null,
        public readonly ?string $ownerCode = null,
        public readonly ?string $ownerName = null,
        public readonly ?string $pickDate = null,
        public readonly ?string $remarks = null,
        public readonly ?string $status = null,
        public readonly ?string $objectType = null,
        public readonly array $pickListsLines = [],
    ) {}

    protected static function mapFromArray(array $data): array
    {
        $lines = [];
        if (isset($data['PickListsLines']) && is_array($data['PickListsLines'])) {
            foreach ($data['PickListsLines'] as $line) {
                $lines[] = PickListLineDto::fromArray($line);
            }
        }

        return [
            'absoluteEntry' => isset($data['AbsoluteEntry']) ? (int) $data['AbsoluteEntry'] : null,
            'name' => $data['Name'] ?? null,
            'ownerCode' => $data['OwnerCode'] ?? null,
            'ownerName' => $data['OwnerName'] ?? null,
            'pickDate' => $data['PickDate'] ?? null,
            'remarks' => $data['Remarks'] ?? null,
            'status' => $data['Status'] ?? null,
            'objectType' => $data['ObjectType'] ?? null,
            'pickListsLines' => $lines,
        ];
    }

    public function toArray(): array
    {
        $data = array_filter([
            'AbsoluteEntry' => $this->absoluteEntry,
            'Name' => $this->name,
            'OwnerCode' => $this->ownerCode,
            'OwnerName' => $this->ownerName,
            'PickDate' => $this->pickDate,
            'Remarks' => $this->remarks,
            'Status' => $this->status,
            'ObjectType' => $this->objectType,
        ], fn ($value) => $value !== null);

        if (! empty($this->pickListsLines)) {
            $data['PickListsLines'] = array_map(fn (PickListLineDto $line) => $line->toArray(), $this->pickListsLines);
        }

        return $data;
    }
}
