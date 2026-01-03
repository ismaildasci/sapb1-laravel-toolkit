<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Builders\Inventory;

use SapB1\Toolkit\Builders\BaseBuilder;

/**
 * Builder for Serial Number Details.
 *
 * @phpstan-consistent-constructor
 */
final class SerialNumberDetailBuilder extends BaseBuilder
{
    public function itemCode(string $code): static
    {
        return $this->set('ItemCode', $code);
    }

    public function serialNumber(string $serial): static
    {
        return $this->set('SerialNumber', $serial);
    }

    public function status(string $status): static
    {
        return $this->set('Status', $status);
    }

    public function manufacturerSerialNumber(string $number): static
    {
        return $this->set('ManufacturerSerialNumber', $number);
    }

    public function internalSerialNumber(string $number): static
    {
        return $this->set('InternalSerialNumber', $number);
    }

    public function expiryDate(string $date): static
    {
        return $this->set('ExpiryDate', $date);
    }

    public function manufactureDate(string $date): static
    {
        return $this->set('ManufactureDate', $date);
    }

    public function admissionDate(string $date): static
    {
        return $this->set('AdmissionDate', $date);
    }

    public function warehouseCode(string $code): static
    {
        return $this->set('WarehouseCode', $code);
    }

    public function location(string $location): static
    {
        return $this->set('Location', $location);
    }

    public function notes(string $notes): static
    {
        return $this->set('Notes', $notes);
    }

    public function attribute1(string $value): static
    {
        return $this->set('Attribute1', $value);
    }

    public function attribute2(string $value): static
    {
        return $this->set('Attribute2', $value);
    }

    public function trackingNote(string $note): static
    {
        return $this->set('TrackingNote', $note);
    }

    public function trackingNoteLine(int $line): static
    {
        return $this->set('TrackingNoteLine', $line);
    }

    /**
     * @return array<string, mixed>
     */
    public function build(): array
    {
        return array_filter($this->data, fn ($value) => $value !== null);
    }
}
