<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs\Production;

use SapB1\Toolkit\DTOs\Base\BaseDto;
use SapB1\Toolkit\Enums\BoYesNo;
use SapB1\Toolkit\Enums\ResourceType;

/**
 * @phpstan-consistent-constructor
 */
final class ResourceDto extends BaseDto
{
    public function __construct(
        public readonly ?string $code = null,
        public readonly ?string $visCode = null,
        public readonly ?int $series = null,
        public readonly ?int $number = null,
        public readonly ?string $codeBar = null,
        public readonly ?string $name = null,
        public readonly ?string $foreignName = null,
        public readonly ?ResourceType $type = null,
        public readonly ?int $group = null,
        public readonly ?string $unitOfMeasure = null,
        public readonly ?string $issueMethod = null,
        public readonly ?float $cost1 = null,
        public readonly ?float $cost2 = null,
        public readonly ?float $cost3 = null,
        public readonly ?float $cost4 = null,
        public readonly ?float $cost5 = null,
        public readonly ?float $cost6 = null,
        public readonly ?float $cost7 = null,
        public readonly ?float $cost8 = null,
        public readonly ?float $cost9 = null,
        public readonly ?float $cost10 = null,
        public readonly ?BoYesNo $active = null,
        public readonly ?int $activeFrom = null,
        public readonly ?int $activeTo = null,
        public readonly ?string $inactiveFrom = null,
        public readonly ?string $inactiveTo = null,
        public readonly ?string $defaultWarehouse = null,
        public readonly ?string $remarks = null,
    ) {}

    protected static function mapFromArray(array $data): array
    {
        return [
            'code' => $data['Code'] ?? null,
            'visCode' => $data['VisCode'] ?? null,
            'series' => $data['Series'] ?? null,
            'number' => $data['Number'] ?? null,
            'codeBar' => $data['CodeBar'] ?? null,
            'name' => $data['Name'] ?? null,
            'foreignName' => $data['ForeignName'] ?? null,
            'type' => isset($data['Type']) ? ResourceType::tryFrom($data['Type']) : null,
            'group' => $data['Group'] ?? null,
            'unitOfMeasure' => $data['UnitOfMeasure'] ?? null,
            'issueMethod' => $data['IssueMethod'] ?? null,
            'cost1' => isset($data['Cost1']) ? (float) $data['Cost1'] : null,
            'cost2' => isset($data['Cost2']) ? (float) $data['Cost2'] : null,
            'cost3' => isset($data['Cost3']) ? (float) $data['Cost3'] : null,
            'cost4' => isset($data['Cost4']) ? (float) $data['Cost4'] : null,
            'cost5' => isset($data['Cost5']) ? (float) $data['Cost5'] : null,
            'cost6' => isset($data['Cost6']) ? (float) $data['Cost6'] : null,
            'cost7' => isset($data['Cost7']) ? (float) $data['Cost7'] : null,
            'cost8' => isset($data['Cost8']) ? (float) $data['Cost8'] : null,
            'cost9' => isset($data['Cost9']) ? (float) $data['Cost9'] : null,
            'cost10' => isset($data['Cost10']) ? (float) $data['Cost10'] : null,
            'active' => isset($data['Active']) ? BoYesNo::tryFrom($data['Active']) : null,
            'activeFrom' => $data['ActiveFrom'] ?? null,
            'activeTo' => $data['ActiveTo'] ?? null,
            'inactiveFrom' => $data['InactiveFrom'] ?? null,
            'inactiveTo' => $data['InactiveTo'] ?? null,
            'defaultWarehouse' => $data['DefaultWarehouse'] ?? null,
            'remarks' => $data['Remarks'] ?? null,
        ];
    }

    public function toArray(): array
    {
        return array_filter([
            'Code' => $this->code,
            'VisCode' => $this->visCode,
            'Series' => $this->series,
            'Number' => $this->number,
            'CodeBar' => $this->codeBar,
            'Name' => $this->name,
            'ForeignName' => $this->foreignName,
            'Type' => $this->type?->value,
            'Group' => $this->group,
            'UnitOfMeasure' => $this->unitOfMeasure,
            'IssueMethod' => $this->issueMethod,
            'Cost1' => $this->cost1,
            'Cost2' => $this->cost2,
            'Cost3' => $this->cost3,
            'Cost4' => $this->cost4,
            'Cost5' => $this->cost5,
            'Cost6' => $this->cost6,
            'Cost7' => $this->cost7,
            'Cost8' => $this->cost8,
            'Cost9' => $this->cost9,
            'Cost10' => $this->cost10,
            'Active' => $this->active?->value,
            'ActiveFrom' => $this->activeFrom,
            'ActiveTo' => $this->activeTo,
            'InactiveFrom' => $this->inactiveFrom,
            'InactiveTo' => $this->inactiveTo,
            'DefaultWarehouse' => $this->defaultWarehouse,
            'Remarks' => $this->remarks,
        ], fn ($value) => $value !== null);
    }
}
