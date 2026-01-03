<?php

declare(strict_types=1);

namespace SapB1\Toolkit\Builders;

/**
 * Builder for document lines.
 *
 * @phpstan-consistent-constructor
 */
class DocumentLineBuilder extends BaseBuilder
{
    public function itemCode(string $code): static
    {
        return $this->set('ItemCode', $code);
    }

    public function itemDescription(string $description): static
    {
        return $this->set('ItemDescription', $description);
    }

    public function quantity(float $quantity): static
    {
        return $this->set('Quantity', $quantity);
    }

    public function price(float $price): static
    {
        return $this->set('Price', $price);
    }

    public function priceAfterVat(float $price): static
    {
        return $this->set('PriceAfterVAT', $price);
    }

    public function currency(string $currency): static
    {
        return $this->set('Currency', $currency);
    }

    public function discountPercent(float $percent): static
    {
        return $this->set('DiscountPercent', $percent);
    }

    public function warehouseCode(string $code): static
    {
        return $this->set('WarehouseCode', $code);
    }

    public function accountCode(string $code): static
    {
        return $this->set('AccountCode', $code);
    }

    public function taxCode(string $code): static
    {
        return $this->set('TaxCode', $code);
    }

    public function baseEntry(int $entry): static
    {
        return $this->set('BaseEntry', $entry);
    }

    public function baseLine(int $line): static
    {
        return $this->set('BaseLine', $line);
    }

    public function baseType(int $type): static
    {
        return $this->set('BaseType', $type);
    }

    public function batchNumber(string $batch): static
    {
        return $this->set('BatchNumber', $batch);
    }

    public function serialNumber(string $serial): static
    {
        return $this->set('SerialNumber', $serial);
    }

    public function costingCode(string $code): static
    {
        return $this->set('CostingCode', $code);
    }

    public function costingCode2(string $code): static
    {
        return $this->set('CostingCode2', $code);
    }

    public function costingCode3(string $code): static
    {
        return $this->set('CostingCode3', $code);
    }

    public function projectCode(string $code): static
    {
        return $this->set('ProjectCode', $code);
    }

    public function freeText(string $text): static
    {
        return $this->set('FreeText', $text);
    }

    /**
     * @return array<string, mixed>
     */
    public function build(): array
    {
        return array_filter($this->data, fn ($value) => $value !== null);
    }
}
