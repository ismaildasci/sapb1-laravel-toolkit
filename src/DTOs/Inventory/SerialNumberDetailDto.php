<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs\Inventory;

use SapB1\Toolkit\DTOs\Base\BaseDto;
use SapB1\Toolkit\Enums\BoYesNo;

/**
 * Serial Number Detail DTO.
 *
 * @phpstan-consistent-constructor
 */
final class SerialNumberDetailDto extends BaseDto
{
    public function __construct(
        public readonly ?int $docEntry = null,
        public readonly ?string $itemCode = null,
        public readonly ?string $itemDescription = null,
        public readonly ?string $serialNumber = null,
        public readonly ?string $status = null,
        public readonly ?string $manufacturerSerialNumber = null,
        public readonly ?string $internalSerialNumber = null,
        public readonly ?string $expiryDate = null,
        public readonly ?string $manufactureDate = null,
        public readonly ?string $admissionDate = null,
        public readonly ?string $warehouseCode = null,
        public readonly ?string $location = null,
        public readonly ?string $notes = null,
        public readonly ?int $systemNumber = null,
        public readonly ?string $attribute1 = null,
        public readonly ?string $attribute2 = null,
        public readonly ?string $trackingNote = null,
        public readonly ?int $trackingNoteLine = null,
    ) {}

    protected static function mapFromArray(array $data): array
    {
        return [
            'docEntry' => isset($data['DocEntry']) ? (int) $data['DocEntry'] : null,
            'itemCode' => $data['ItemCode'] ?? null,
            'itemDescription' => $data['ItemDescription'] ?? null,
            'serialNumber' => $data['SerialNumber'] ?? $data['DistNumber'] ?? null,
            'status' => $data['Status'] ?? null,
            'manufacturerSerialNumber' => $data['ManufacturerSerialNumber'] ?? null,
            'internalSerialNumber' => $data['InternalSerialNumber'] ?? null,
            'expiryDate' => $data['ExpiryDate'] ?? null,
            'manufactureDate' => $data['ManufactureDate'] ?? null,
            'admissionDate' => $data['AdmissionDate'] ?? null,
            'warehouseCode' => $data['WarehouseCode'] ?? null,
            'location' => $data['Location'] ?? null,
            'notes' => $data['Notes'] ?? null,
            'systemNumber' => isset($data['SystemNumber']) ? (int) $data['SystemNumber'] : null,
            'attribute1' => $data['Attribute1'] ?? null,
            'attribute2' => $data['Attribute2'] ?? null,
            'trackingNote' => $data['TrackingNote'] ?? null,
            'trackingNoteLine' => isset($data['TrackingNoteLine']) ? (int) $data['TrackingNoteLine'] : null,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter([
            'DocEntry' => $this->docEntry,
            'ItemCode' => $this->itemCode,
            'ItemDescription' => $this->itemDescription,
            'SerialNumber' => $this->serialNumber,
            'Status' => $this->status,
            'ManufacturerSerialNumber' => $this->manufacturerSerialNumber,
            'InternalSerialNumber' => $this->internalSerialNumber,
            'ExpiryDate' => $this->expiryDate,
            'ManufactureDate' => $this->manufactureDate,
            'AdmissionDate' => $this->admissionDate,
            'WarehouseCode' => $this->warehouseCode,
            'Location' => $this->location,
            'Notes' => $this->notes,
            'SystemNumber' => $this->systemNumber,
            'Attribute1' => $this->attribute1,
            'Attribute2' => $this->attribute2,
            'TrackingNote' => $this->trackingNote,
            'TrackingNoteLine' => $this->trackingNoteLine,
        ], fn ($value) => $value !== null);
    }
}
