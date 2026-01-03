<?php

declare(strict_types=1);

namespace SapB1\Toolkit\DTOs\Inventory;

use SapB1\Toolkit\DTOs\Base\BaseDto;
use SapB1\Toolkit\Enums\BoYesNo;

/**
 * @phpstan-consistent-constructor
 */
final class BatchDto extends BaseDto
{
    public function __construct(
        public readonly ?int $absEntry = null,
        public readonly ?string $itemCode = null,
        public readonly ?string $batchNumber = null,
        public readonly ?string $status = null,
        public readonly ?float $quantity = null,
        public readonly ?string $manufacturerSerialNumber = null,
        public readonly ?string $internalSerialNumber = null,
        public readonly ?string $expiryDate = null,
        public readonly ?string $manufacturingDate = null,
        public readonly ?string $admissionDate = null,
        public readonly ?string $location = null,
        public readonly ?string $notes = null,
        public readonly ?BoYesNo $locked = null,
    ) {}

    protected static function mapFromArray(array $data): array
    {
        return [
            'absEntry' => isset($data['AbsEntry']) ? (int) $data['AbsEntry'] : null,
            'itemCode' => $data['ItemCode'] ?? null,
            'batchNumber' => $data['BatchNumber'] ?? null,
            'status' => $data['Status'] ?? null,
            'quantity' => isset($data['Quantity']) ? (float) $data['Quantity'] : null,
            'manufacturerSerialNumber' => $data['ManufacturerSerialNumber'] ?? null,
            'internalSerialNumber' => $data['InternalSerialNumber'] ?? null,
            'expiryDate' => $data['ExpiryDate'] ?? null,
            'manufacturingDate' => $data['ManufacturingDate'] ?? null,
            'admissionDate' => $data['AdmissionDate'] ?? null,
            'location' => $data['Location'] ?? null,
            'notes' => $data['Notes'] ?? null,
            'locked' => isset($data['Locked']) ? BoYesNo::tryFrom($data['Locked']) : null,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter([
            'AbsEntry' => $this->absEntry,
            'ItemCode' => $this->itemCode,
            'BatchNumber' => $this->batchNumber,
            'Status' => $this->status,
            'Quantity' => $this->quantity,
            'ManufacturerSerialNumber' => $this->manufacturerSerialNumber,
            'InternalSerialNumber' => $this->internalSerialNumber,
            'ExpiryDate' => $this->expiryDate,
            'ManufacturingDate' => $this->manufacturingDate,
            'AdmissionDate' => $this->admissionDate,
            'Location' => $this->location,
            'Notes' => $this->notes,
            'Locked' => $this->locked?->value,
        ], fn ($value) => $value !== null);
    }
}
