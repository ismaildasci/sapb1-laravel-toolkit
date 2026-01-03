<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs\Inventory;

use SapB1\Toolkit\DTOs\Base\BaseDto;
use SapB1\Toolkit\Enums\BoYesNo;

/**
 * Bin Location DTO.
 *
 * @phpstan-consistent-constructor
 */
final class BinLocationDto extends BaseDto
{
    public function __construct(
        public readonly ?int $absEntry = null,
        public readonly ?string $binCode = null,
        public readonly ?string $warehouseCode = null,
        public readonly ?int $sublevelOne = null,
        public readonly ?int $sublevelTwo = null,
        public readonly ?int $sublevelThree = null,
        public readonly ?int $sublevelFour = null,
        public readonly ?float $minimumQty = null,
        public readonly ?float $maximumQty = null,
        public readonly ?BoYesNo $inactive = null,
        public readonly ?string $description = null,
        public readonly ?string $alternativeSortCode = null,
        public readonly ?string $barCode = null,
        public readonly ?int $attribute1 = null,
        public readonly ?int $attribute2 = null,
        public readonly ?int $attribute3 = null,
        public readonly ?int $attribute4 = null,
        public readonly ?int $attribute5 = null,
        public readonly ?int $attribute6 = null,
        public readonly ?int $attribute7 = null,
        public readonly ?int $attribute8 = null,
        public readonly ?int $attribute9 = null,
        public readonly ?int $attribute10 = null,
        public readonly ?BoYesNo $restrictedItemType = null,
        public readonly ?string $specificItem = null,
        public readonly ?int $specificItemGroup = null,
        public readonly ?int $batchRestrictions = null,
        public readonly ?BoYesNo $restrictedTransType = null,
        public readonly ?string $restrictionReason = null,
        public readonly ?string $dateRestrictionChanged = null,
        public readonly ?float $maximumWeight = null,
        public readonly ?int $maximumWeight1 = null,
        public readonly ?float $maximumWeightUnit = null,
        public readonly ?int $maximumWeightUnit1 = null,
        public readonly ?BoYesNo $receivingBinLocation = null,
        public readonly ?BoYesNo $excludeAutoAllocOnIssue = null,
    ) {}

    protected static function mapFromArray(array $data): array
    {
        return [
            'absEntry' => isset($data['AbsEntry']) ? (int) $data['AbsEntry'] : null,
            'binCode' => $data['BinCode'] ?? null,
            'warehouseCode' => $data['Warehouse'] ?? null,
            'sublevelOne' => isset($data['SL1Code']) ? (int) $data['SL1Code'] : null,
            'sublevelTwo' => isset($data['SL2Code']) ? (int) $data['SL2Code'] : null,
            'sublevelThree' => isset($data['SL3Code']) ? (int) $data['SL3Code'] : null,
            'sublevelFour' => isset($data['SL4Code']) ? (int) $data['SL4Code'] : null,
            'minimumQty' => isset($data['MinimumQty']) ? (float) $data['MinimumQty'] : null,
            'maximumQty' => isset($data['MaximumQty']) ? (float) $data['MaximumQty'] : null,
            'inactive' => isset($data['Inactive']) ? BoYesNo::tryFrom($data['Inactive']) : null,
            'description' => $data['Description'] ?? null,
            'alternativeSortCode' => $data['AlternativeSortCode'] ?? null,
            'barCode' => $data['BarCode'] ?? null,
            'attribute1' => isset($data['Attr1Val']) ? (int) $data['Attr1Val'] : null,
            'attribute2' => isset($data['Attr2Val']) ? (int) $data['Attr2Val'] : null,
            'attribute3' => isset($data['Attr3Val']) ? (int) $data['Attr3Val'] : null,
            'attribute4' => isset($data['Attr4Val']) ? (int) $data['Attr4Val'] : null,
            'attribute5' => isset($data['Attr5Val']) ? (int) $data['Attr5Val'] : null,
            'attribute6' => isset($data['Attr6Val']) ? (int) $data['Attr6Val'] : null,
            'attribute7' => isset($data['Attr7Val']) ? (int) $data['Attr7Val'] : null,
            'attribute8' => isset($data['Attr8Val']) ? (int) $data['Attr8Val'] : null,
            'attribute9' => isset($data['Attr9Val']) ? (int) $data['Attr9Val'] : null,
            'attribute10' => isset($data['Attr10Val']) ? (int) $data['Attr10Val'] : null,
            'restrictedItemType' => isset($data['RestrictedItemType']) ? BoYesNo::tryFrom($data['RestrictedItemType']) : null,
            'specificItem' => $data['SpecificItem'] ?? null,
            'specificItemGroup' => isset($data['SpecificItemGroup']) ? (int) $data['SpecificItemGroup'] : null,
            'batchRestrictions' => isset($data['BatchRestrictions']) ? (int) $data['BatchRestrictions'] : null,
            'restrictedTransType' => isset($data['RestrictedTransType']) ? BoYesNo::tryFrom($data['RestrictedTransType']) : null,
            'restrictionReason' => $data['RestrictionReason'] ?? null,
            'dateRestrictionChanged' => $data['DateRestrictionChanged'] ?? null,
            'maximumWeight' => isset($data['MaximumWeight']) ? (float) $data['MaximumWeight'] : null,
            'maximumWeight1' => isset($data['MaximumWeight1']) ? (int) $data['MaximumWeight1'] : null,
            'maximumWeightUnit' => isset($data['MaximumWeightUnit']) ? (float) $data['MaximumWeightUnit'] : null,
            'maximumWeightUnit1' => isset($data['MaximumWeightUnit1']) ? (int) $data['MaximumWeightUnit1'] : null,
            'receivingBinLocation' => isset($data['ReceivingBinLocation']) ? BoYesNo::tryFrom($data['ReceivingBinLocation']) : null,
            'excludeAutoAllocOnIssue' => isset($data['ExcludeAutoAllocOnIssue']) ? BoYesNo::tryFrom($data['ExcludeAutoAllocOnIssue']) : null,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter([
            'AbsEntry' => $this->absEntry,
            'BinCode' => $this->binCode,
            'Warehouse' => $this->warehouseCode,
            'SL1Code' => $this->sublevelOne,
            'SL2Code' => $this->sublevelTwo,
            'SL3Code' => $this->sublevelThree,
            'SL4Code' => $this->sublevelFour,
            'MinimumQty' => $this->minimumQty,
            'MaximumQty' => $this->maximumQty,
            'Inactive' => $this->inactive?->value,
            'Description' => $this->description,
            'AlternativeSortCode' => $this->alternativeSortCode,
            'BarCode' => $this->barCode,
            'Attr1Val' => $this->attribute1,
            'Attr2Val' => $this->attribute2,
            'Attr3Val' => $this->attribute3,
            'Attr4Val' => $this->attribute4,
            'Attr5Val' => $this->attribute5,
            'Attr6Val' => $this->attribute6,
            'Attr7Val' => $this->attribute7,
            'Attr8Val' => $this->attribute8,
            'Attr9Val' => $this->attribute9,
            'Attr10Val' => $this->attribute10,
            'RestrictedItemType' => $this->restrictedItemType?->value,
            'SpecificItem' => $this->specificItem,
            'SpecificItemGroup' => $this->specificItemGroup,
            'BatchRestrictions' => $this->batchRestrictions,
            'RestrictedTransType' => $this->restrictedTransType?->value,
            'RestrictionReason' => $this->restrictionReason,
            'DateRestrictionChanged' => $this->dateRestrictionChanged,
            'MaximumWeight' => $this->maximumWeight,
            'MaximumWeight1' => $this->maximumWeight1,
            'MaximumWeightUnit' => $this->maximumWeightUnit,
            'MaximumWeightUnit1' => $this->maximumWeightUnit1,
            'ReceivingBinLocation' => $this->receivingBinLocation?->value,
            'ExcludeAutoAllocOnIssue' => $this->excludeAutoAllocOnIssue?->value,
        ], fn ($value) => $value !== null);
    }
}
