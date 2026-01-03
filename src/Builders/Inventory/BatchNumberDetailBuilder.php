<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Builders\Inventory;

use SapB1\Toolkit\Builders\BaseBuilder;

/**
 * Builder for Batch Number Details.
 *
 * @phpstan-consistent-constructor
 */
final class BatchNumberDetailBuilder extends BaseBuilder
{
    public function itemCode(string $code): static
    {
        return $this->set('ItemCode', $code);
    }

    public function batchNumber(string $batch): static
    {
        return $this->set('Batch', $batch);
    }

    public function status(string $status): static
    {
        return $this->set('Status', $status);
    }

    public function quantity(float $quantity): static
    {
        return $this->set('Quantity', $quantity);
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
